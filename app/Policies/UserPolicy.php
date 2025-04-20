<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Určuje, či môže používateľ vykonať administratívne operácie.
     */
    public function admin(User $user)
    {
        // Napríklad: Ak je používateľ administrátor
        return $user->is_admin;
    }

    /**
     * Určuje, či môže používateľ upravovať svoj profil.
     */
    public function editProfile(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id;
    }
}
