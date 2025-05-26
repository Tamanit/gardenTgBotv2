<?php

namespace App\useCases;

use App\Services\ReviewService;
use App\Services\TelegramService;
use App\Services\TwoGisApiService;
use Telegram\Bot\Exceptions\TelegramSDKException;

class NotifyAboutNewReviewsInTwoGisUseCase
{
    public function __construct(
        protected TwoGisApiService $twoGisApiService,
        protected ReviewService $reviewService,
        protected TelegramService $telegramService,
    ) {
    }

    /**
     * @throws TelegramSDKException
     */
    public function use(): void
    {
        $reviews = $this->twoGisApiService->requestReviewsFromApi();
        $reviews = $this->reviewService->removeExistedReviews($reviews);
        $reviews = $this->reviewService->storeReviews($reviews);
        $this->telegramService->notifyAboutReviews($reviews);
    }
}
