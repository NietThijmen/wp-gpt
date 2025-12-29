<?php

namespace App\Http\Controllers;

use App\Mcp\Tools\Administrative\IndexPackage;
use App\Mcp\Tools\Administrative\SearchPackages;
use App\Mcp\Tools\File\SearchClass;
use App\Mcp\Tools\File\SearchFile;
use App\Mcp\Tools\File\SearchMethod;
use App\Mcp\Tools\Hooks\DoesHookExist;
use App\Mcp\Tools\Hooks\GetHookUsages;
use App\Mcp\Tools\Hooks\SearchHook;
use App\Models\ChatConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Prism\Prism\Enums\ToolChoice;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Facades\Tool;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class ChatConversationController extends Controller
{

    private function getToolsAsPrism(): array
    {
        return [
            Tool::make(IndexPackage::class),
            Tool::make(SearchPackages::class),
            Tool::make(SearchClass::class),
            Tool::make(SearchFile::class),
            Tool::make(SearchMethod::class),
            Tool::make(DoesHookExist::class),
            Tool::make(GetHookUsages::class),
            Tool::make(SearchHook::class)
        ];
    }

    public function index()
    {
        $conversations = ChatConversation::with('messages')->with('user')->get();

        return inertia('Chat/Index', [
            'conversations' => $conversations,
        ]);
    }

    public function store(Request $request)
    {
        $conversation = ChatConversation::create([
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('chat.show', ['chat' => $conversation->id]);
    }

    public function show(ChatConversation $chat)
    {
        $conversations = ChatConversation::with('messages')->with('user')->get();
        $conversation = ChatConversation::with('messages')->with('user')->where('id', $chat->id)->first();
        return inertia('Chat/Index', [
            'conversations' => $conversations,
            'conversation' => $conversation, // Pass the specific conversation to the view
        ]);
    }

    public function update(Request $request, ChatConversation $chat)
    {
        ini_set('memory_limit', 1024 . 'M');
        ini_set('max_execution_time', 600);

        $old_messages = $chat->messages()->get()->map(function ($message) {
            $role = $message->role;
            return match ($role) {
                'user' => new UserMessage($message),
                'assistant' => new AssistantMessage($message),
            };
        })->toArray();

        $new_message = new UserMessage($request->input('message'));

        $all_messages = array_merge($old_messages, [$new_message]);

        $rand_id = Str::uuid()->toString();
        \Log::info("New chat message", [
            'chat_id' => $chat->id,
            'message_id' => $rand_id,
            'message' => $request->input('message'),
        ]);

        $data = Prism::text()
            ->using(
                config('services.ai.provider'),
                config('services.ai.model')
            )
            ->withTools(
                $this->getToolsAsPrism()
            )
            ->withMaxSteps(
                5
            )
            ->withSystemPrompt(
                "You are an AI assistant helping a developer understand and work with a large WordPress codebase. You have access to various tools to search for classes, methods, files, and WordPress hook usages within the codebase. Use these tools as needed to provide accurate and helpful responses."
            )
            ->withMessages($all_messages)
            ->asText();


        if(count($data->toolCalls) > 0) {
            \Log::info("Tool calls made during chat", [
                'chat_id' => $chat->id,
                'message_id' => $rand_id,
                'tool_calls' => $data->toolCalls,
            ]);
        }

        if(count($data->toolResults) > 0) {
            \Log::info("Tool results received during chat", [
                'chat_id' => $chat->id,
                'message_id' => $rand_id,
                'tool_results' => $data->toolResults,
            ]);
        }

        \Log::info("New AI response", [
            'chat_id' => $chat->id,
            'message_id' => $rand_id,
            'response' => $data->text,
            'finishReason' => $data->finishReason,
            'meta' => $data->meta,
        ]);


        $chat->messages()->createMany([
            [
                'role' => 'user',
                'content' => $request->input('message'),
            ],
            [
                'role' => 'assistant',
                'content' => $data->text,
            ],
        ]);

        return redirect()->back();

    }
}
