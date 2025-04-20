<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function view(User $user, Message $message): bool
    {
        return $message->chat->users->contains($user);
    }

    public function create(User $user, Message $message): bool
    {
        return $message->chat->users->contains($user);
    }

    public function reply(User $user, Message $message): bool
    {
        return $message->chat->users->contains($user);
    }
}
