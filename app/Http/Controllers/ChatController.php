<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Auth::user()->chats()->with('users')->get();
        return response()->json($chats);
    }

    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);
        $chat->load('users', 'messages');
        return response()->json($chat);
    }

    public function getUsers($chatId)
    {
        // Načítame chat podľa ID
        $chat = Chat::findOrFail($chatId);

        // Získame všetkých používateľov v chate
        $users = $chat->users;

        // Vrátime zoznam používateľov
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|not_in:' . Auth::id(),
        ]);

        $chat = Chat::create([
            'name' => null,
            'created_by' => Auth::id(),
        ]);

        $chat->users()->attach([Auth::id(), $request->user_id]);

        return response()->json($chat, 201);
    }

    public function invite(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $chat->users()->syncWithoutDetaching($request->user_ids);

        return response()->json(['message' => 'Users invited.']);
    }

    public function leave(Chat $chat)
    {
        $user = Auth::user();
        $chat->users()->detach($user->id);

        if ($chat->users()->count() === 0) {
            $chat->delete();
        }

        return response()->json(['message' => 'You left the chat.']);
    }

    public function update(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $chat->update(['name' => $request->name]);

        return response()->json(['message' => 'Chat updated.']);
    }

    public function removeUser(Chat $chat, User $user)
    {
        $this->authorize('update', $chat);

        $chat->users()->detach($user->id);

        if ($chat->users()->count() === 0) {
            $chat->delete();
        }

        return response()->json(['message' => 'User removed from chat.']);
    }
}
