<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    public function view(User $user, Chat $chat): bool
    {
        return $chat->users->contains($user);
    }

    public function update(User $user, Chat $chat): bool
    {
        return $chat->created_by === $user->id;
    }

    public function delete(User $user, Chat $chat): bool
    {
        return $chat->created_by === $user->id;
    }

    public function manage(User $user, Chat $chat): bool
    {
        return $chat->users->contains($user);
    }
}
