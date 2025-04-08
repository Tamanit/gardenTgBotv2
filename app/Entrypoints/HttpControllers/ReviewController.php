<?php

namespace App\Entrypoints\HttpControllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\useCases\NotifyAboutNewReviewsUseCase;
use Telegram\Bot\Exceptions\TelegramSDKException;

class ReviewController extends Controller
{
    /**

     * Create a new controller instance.

     */

    public function __construct(
        protected NotifyAboutNewReviewsUseCase $notifyAboutNewReviews,
    ) {}

    /**
     * @throws TelegramSDKException
     */
    public function sync()
    {
        $this->notifyAboutNewReviews->use();
    }
}
