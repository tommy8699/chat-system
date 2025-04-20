<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\MessageReply;
use App\Models\Chat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MessageService
{
    /**
     * Posiela novú správu do chatu.
     *
     * @param int $chatId
     * @param int $userId
     * @param string|null $text
     * @param \Illuminate\Http\UploadedFile|null $file
     * @return Message
     */
    public function sendMessage(int $chatId, int $userId, ?string $text, ?\Illuminate\Http\UploadedFile $file): Message
    {
        $chat = Chat::findOrFail($chatId);

        // Skontroluj, či je používateľ účastníkom chatu
        if (!$chat->users()->where('user_id', $userId)->exists()) {
            throw new \Exception("Používateľ nie je účastníkom chatu.");
        }

        // Začneme transakciu
        DB::beginTransaction();

        try {
            // Vytvoríme správu
            $message = Message::create([
                'chat_id' => $chatId,
                'user_id' => $userId,
                'text' => $text
            ]);

            // Ak existuje súbor, uložíme ho
            if ($file) {
                $filePath = $file->store('attachments', 'public');
                MessageAttachment::create([
                    'message_id' => $message->id,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ]);
            }

            DB::commit();
            return $message;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Chyba pri posielaní správy: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Odpovedá na správu.
     *
     * @param int $messageId
     * @param int $userId
     * @param string $replyText
     * @return MessageReply
     */
    public function replyToMessage(int $messageId, int $userId, string $replyText): MessageReply
    {
        // Skontrolujeme, či správa existuje
        $message = Message::findOrFail($messageId);

        // Skontrolujeme, či je používateľ účastníkom chatu
        if (!$message->chat->users()->where('user_id', $userId)->exists()) {
            throw new \Exception("Používateľ nie je účastníkom chatu.");
        }

        // Vytvoríme odpoveď
        return MessageReply::create([
            'message_id' => $messageId,
            'user_id' => $userId,
            'reply_text' => $replyText,
        ]);
    }
}
