<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Chat;

class EnsureChatParticipant
{
    public function handle(Request $request, Closure $next): Response
    {
        $chatId = $request->route('chat'); // predpokladáme route parameter {chat}

        $chat = Chat::find($chatId);

        if (!$chat) {
            return response()->json(['message' => 'Chat neexistuje.'], 404);
        }

        if (!$chat->users()->where('user_id', auth()->id())->exists()) {
            return response()->json(['message' => 'Nemáš prístup k tomuto chatu.'], 403);
        }

        return $next($request);
    }
}
