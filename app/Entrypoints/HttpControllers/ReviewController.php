<?php

namespace App\Entrypoints\HttpControllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Services\YandexMapApiService;
use App\useCases\NotifyAboutNewReviewsInGoogleMapUseCase;
use App\useCases\NotifyAboutNewReviewsInTwoGisUseCase;
use Telegram\Bot\Exceptions\TelegramSDKException;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     */

    public function __construct(
        protected NotifyAboutNewReviewsInTwoGisUseCase $notifyAboutNewReviewsInTwoGisUseCase,
        protected NotifyAboutNewReviewsInGoogleMapUseCase $notifyAboutNewReviewsInGoogleMapUseCase,
    ) {
    }

    /**
     * @throws TelegramSDKException
     */
    public function sync()
    {
        $this->notifyAboutNewReviewsInTwoGisUseCase->use();
        $this->notifyAboutNewReviewsInGoogleMapUseCase->use();
    }
}
