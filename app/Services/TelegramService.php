<?php

namespace App\Services;

use App\Dtos\Models\ReviewDto;
use App\Models\TelegramChats;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramService extends BaseService
{
    public function __construct(
        protected Api $telegram
    ) {
    }

    protected function formatMessage(ReviewDto $review): string
    {
        setlocale(LC_TIME, 'ru_RU', 'russian');
        return "☕ Кофейня: #{$review->branchDto?->name}\n📣 из: {$review->resource}\n📆 Дата: {$review->time->format('d F Y, H:i')}\n✏ Оценка: " . str_repeat(
                '⭐',
                (int)$review->rating
            ) . "({$review->rating} из 5)\n\n📝 Отзыв:\n {$review->text}";
    }

    /**
     * @param ReviewDto $review
     * @return void
     * @throws TelegramSDKException
     */
    protected function sendTelegramMessages(ReviewDto $review): void
    {
        $chats = TelegramChats::get();

        $reviewText = $this->formatMessage($review);

        if (!empty($review->photos) and mb_strlen($reviewText) < 1024) {
            $review->photos[0]['caption'] = $reviewText;
            foreach ($chats as $chat) {
                $this->telegram->sendMediaGroup([
                    'chat_id' => $chat->chatId,
                    'media' => json_encode($review->photos),
                ]);
            }
        } elseif (!empty($review->photos)) {
            foreach ($chats as $chat) {
                $this->telegram->sendMessage([
                    'chat_id' => $chat->chatId,
                    'text' => $this->formatMessage($review),
                ]);
                $this->telegram->sendMediaGroup([
                    'chat_id' => $chat->chatId,
                    'media' => json_encode($review->photos),
                ]);
            }
        } else {
            foreach ($chats as $chat) {
                $this->telegram->sendMessage([
                    'chat_id' => $chat->chatId,
                    'text' => $this->formatMessage($review),
                ]);
            }
        }
    }

    /**
     * @param array<ReviewDto> $reviewDtos
     * @return void
     * @throws TelegramSDKException
     */
    public function notifyAboutReviews(array $reviewDtos): void
    {
        foreach ($reviewDtos as $reviewDto) {
            $this->sendTelegramMessages($reviewDto);
        }
    }

    public function subscribeForReviews(string $chatID, string $name = null): string
    {
        if (TelegramChats::where('chatId', $chatID)->exists()) {
            return 'Вы уже подписаны';
        }

        TelegramChats::create([
            'chatId' => $chatID,
            'userName' => $name
        ]);

        return 'Вы подписались';
    }

    public function checkTelegramKey(string $key): bool
    {
        return $key == env('TELEGRAM_BOT_SUB_KEY');
    }
}
