<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Services\OpenRouter;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function index()
    {

    }

    public function store(
        Request $request,
        Chat $chat,
        OpenRouter $openRouter
    )
    {
        ini_set('max_execution_time', 300);
        $data = $request->validate([
            'message' => 'required|string',
        ]);

        $messages = $chat->messages()->orderBy('created_at')->get()->map(function ($message) {
            return [
                'role' => $message->role,
                'content' => $message->message,
            ];
        })->toArray();

        $messages[] = [
            'role' => 'user',
            'content' => $data['message'],
        ];

        $chat->messages()->create([
            'role' => 'user',
            'message' => $data['message'],
        ]);

        $response = $openRouter->sendMessage(
            messages: $messages,
            useTools: true,
            enableThoughts: true,
            maxTokens: 10000,
            cacheKey: null
        );

        // Store user message

        // Store assistant message
        $chat->messages()->create([
            'role' => 'assistant',
            'message' => $response['explanation']['choices'][0]['message']['content'],
            'provider' =>  $response['explanation']['provider'],
            'cost' =>   $response['explanation']['usage']['cost'],

        ]);

        return redirect()->back();
    }
}
