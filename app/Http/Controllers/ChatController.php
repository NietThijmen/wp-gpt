<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::where('user_id', auth()->id())->get();
        return inertia('Chat', compact('chats'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
        ]);

        $chat = new Chat();
        $chat->user_id = auth()->id();
        $chat->title = $validated['title'] ?? null;
        $chat->save();


        return redirect()->route('chat.index')->with('success', 'Chat created successfully');
    }

    public function show(Chat $chat)
    {

        $data = [
            'id' => $chat->id,
            'title' => $chat->title,
            'messages' => $chat->messages()->orderBy('created_at')->get(),
        ];

        $chats = Chat::where('user_id', auth()->id())->get();
        return inertia('Chat', [
            'chats' => $chats,
            'chat' => $data
        ]);
    }

    public function edit(Chat $chat)
    {
    }

    public function update(Request $request, Chat $chat)
    {
    }

    public function destroy(Chat $chat)
    {
    }
}
