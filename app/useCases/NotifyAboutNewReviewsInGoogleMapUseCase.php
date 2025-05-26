<?php

namespace App\useCases;

use App\Services\GoogleMapApiService;
use App\Services\ReviewService;
use App\Services\TelegramService;
use Telegram\Bot\Exceptions\TelegramSDKException;

class NotifyAboutNewReviewsInGoogleMapUseCase
{
    public function __construct(
        protected GoogleMapApiService $googleMapApiService,
        protected ReviewService $reviewService,
        protected TelegramService $telegramService,
    ) {
    }

    /**
     * @throws TelegramSDKException
     */
    public function use(): void
    {
        $reviews = $this->googleMapApiService->requestReviewsFromApi();
        $reviews = $this->reviewService->removeExistedReviews($reviews);
        $reviews = $this->reviewService->storeReviews($reviews);
        $this->telegramService->notifyAboutReviews($reviews);
    }
}
