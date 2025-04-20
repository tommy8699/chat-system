<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = auth()->user()->chats;
        return view('chats.index', compact('chats'));
    }

    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);

        return view('chats.show', compact('chat'));
    }

    public function create()
    {
        return view('chats.create');
    }
}
