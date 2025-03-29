<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Services\ReviewService;
use App\useCases\NotifyAboutNewReviews;

class ReviewController extends Controller
{
    /**

     * Create a new controller instance.

     */

    public function __construct(

        protected NotifyAboutNewReviews $notifyAboutNewReviews,

    ) {}

    public function sync()
    {
        $this->notifyAboutNewReviews->use();
    }
}
