<?php

namespace App\useCases;

use App\Services\ReviewService;

class NotifyAboutNewReviews
{
    public function __construct(
        protected ReviewService $reviewService,
    ) {}

    public function use(): void
    {
        $rawReviews = $this->reviewService->getReviewsFromTwoGis();
        $this->reviewService->storeReviews($rawReviews);
    }
}

