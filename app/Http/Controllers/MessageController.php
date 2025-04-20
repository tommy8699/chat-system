<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Chat $chat)
    {
        // Overenie, že používateľ je účastníkom chatu
        if (!$chat->users->contains(Auth::id())) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $messages = $chat->messages()->with('user', 'replies')->latest()->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'nullable|string', // Textová správa je voliteľná
            'file' => 'nullable|file', // Súbor je voliteľný
            'reply_to' => 'nullable|exists:messages,id', // Odpoveď na existujúcu správu
            'chat_id' => 'required|exists:chats,id', // Chat musí existovať
        ]);

        // Uloženie správy do databázy
        $message = new Message();
        $message->user_id = auth()->id();
        $message->chat_id = $request->chat_id;
        $message->message = $request->message; // Ak je text správy, uložíme ho
        $message->reply_to = $request->reply_to;

        // Ak je pripojený súbor, uložte ho
        if ($request->hasFile('file')) {
            $message->file_path = $request->file('file')->store('messages/files');
        }

        // Uložíme správu
        $message->save();

        return response()->json($message, 201);
    }

    public function reply(Request $request, Message $message)
    {
        $chat = $message->chat;

        if (!$chat->users->contains(Auth::id())) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'message'   => 'required|string',
            'file'      => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('chat_files', 'public');
        }

        $reply = $chat->messages()->create([
            'user_id'   => Auth::id(),
            'message'   => $validated['message'],
            'file_path' => $filePath,
            'reply_to'  => $message->id,
        ]);

        return response()->json($reply, 201);
    }
}

