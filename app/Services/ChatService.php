<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatService
{
    /**
     * Vytvorí nový chat medzi dvoma používateľmi.
     *
     * @param int $userId1
     * @param int $userId2
     * @return Chat
     */
    public function createChat(int $userId1, int $userId2): Chat
    {
        // Začneme transakciu, aby sme zabezpečili, že všetky operácie prebehnú úspešne
        DB::beginTransaction();

        try {
            // Vytvoríme nový chat
            $chat = Chat::create();

            // Priradíme dvoch používateľov do chatu
            $chat->users()->attach([$userId1, $userId2]);

            DB::commit();
            return $chat;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Chyba pri vytváraní chatu: ' . $e->getMessage());
            throw $e; // Prehodenie chyby na controller
        }
    }

    /**
     * Pozve používateľa do existujúceho chatu.
     *
     * @param int $chatId
     * @param int $userId
     */
    public function inviteUserToChat(int $chatId, int $userId): void
    {
        // Skontrolujeme, či je používateľ už v chate
        $chat = Chat::findOrFail($chatId);
        $chatUserExists = $chat->users()->where('user_id', $userId)->exists();

        if (!$chatUserExists) {
            // Ak nie je, pozveme ho
            $chat->users()->attach($userId);
        }
    }

    /**
     * Odstráni používateľa z chatu.
     *
     * @param int $chatId
     * @param int $userId
     */
    public function removeUserFromChat(int $chatId, int $userId): void
    {
        $chat = Chat::findOrFail($chatId);

        // Odstránime používateľa
        $chat->users()->detach($userId);

        // Ak bol posledný používateľ, vymažeme chat
        if ($chat->users->isEmpty()) {
            $chat->delete();
        }
    }
}
