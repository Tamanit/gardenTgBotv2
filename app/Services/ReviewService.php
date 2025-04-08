<?php

namespace App\Services;

use App\Dtos\Models\ReviewDto;
use App\Models\Brunch;
use App\Models\Review;
use App\Models\TelegramChats;
use Telegram\Bot\Api;

class ReviewService extends BaseService
{
    /**
     * @param array<ReviewDto> $reviewDtos
     * @return array<ReviewDto>
     */
    public function removeExistedReviews(array $reviewDtos): array
    {
        $reviewIds = array_map(function (ReviewDto $reviewDto) {
            return $reviewDto->id;
        }, $reviewDtos);

        $existedReviewIds = Review::select('twoGisId')->whereIn('twoGisId', $reviewIds)->get()->toArray();
        $existedReviewIds = array_column($existedReviewIds, 'twoGisId');

        foreach ($reviewDtos as $key => $reviewDto) {
            if (in_array($reviewDto->id, $existedReviewIds)) {
                unset($reviewDtos[$key]);
            }
        }

        return $reviewDtos;
    }

    /**
     * @param array<ReviewDto> $reviewDtos
     * @return array<ReviewDto>
     */
    public function storeReviews(array $reviewDtos): array
    {
        $reviews = [];
        foreach ($reviewDtos as $reviewDto) {
            $reviews[] = ['twoGisId' => $reviewDto->id];
        }
        Review::insert($reviews);

        return $reviewDtos;
    }

}
