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

        return inertia('chat/index', [
            'conversations' => $conversations,
        ]);
    }

    public function store(Request $request)
    {
        $conversation = ChatConversation::create([
            'user_id' => $request->user()->id,
        ]);

        return redirect()->back();
    }

    public function show(ChatConversation $chat)
    {
        $conversations = ChatConversation::with('messages')->with('user')->get();
        $conversation = ChatConversation::with('messages')->with('user')->where('id', $chat->id)->first();
        return inertia('chat/index', [
            'conversations' => $conversations,
            'conversation' => $conversation, // Pass the specific conversation to the view
        ]);
    }

    public function update(Request $request, ChatConversation $chat)
    {
        $old_messages = $chat->messages()->get()->map(function ($message) {
            $role = $message->role;
            return match ($role) {
                'user' => new UserMessage($message),
                'assistant' => new AssistantMessage($message),
            };
        })->toArray();

        $new_message = new UserMessage($request->input('message'));

        $all_messages = array_merge($old_messages, [$new_message]);

        $data = Prism::text()
            ->using(
                config('services.ai.provider'),
                config('services.ai.model')
            )
            ->withTools(
                $this->getToolsAsPrism()
            )
            ->withMessages($all_messages)
            ->asText();


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
