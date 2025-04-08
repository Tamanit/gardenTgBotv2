<?php

namespace App\useCases;

use App\Services\TelegramService;

class SubscribeForNotificationAboutNewReviewsUseCase
{
    public function __construct(
        protected TelegramService $telegramService
    ) {
    }

    public function use(string $key, string $chatId): string
    {
        if (!$this->telegramService->checkTelegramKey($key)) {
            return 'Ключ не валиден, подписка невозможна!';
        }

        return $this->telegramService->subscribeForReviews($chatId);
    }
}
