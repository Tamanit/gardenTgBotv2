<?php

namespace App\Services;

use App\Dtos\Models\ReviewDto;
use App\Models\Brunch;
use App\Models\Review;
use App\Models\TelegramChats;
use DateTime;
use Telegram\Bot\Api;

class ReviewService extends BaseService
{
    /**
     * @param array<ReviewDto> $reviewDtos
     * @return array<ReviewDto>
     */
    public function removeExistedReviews(array $reviewDtos): array
    {
        $resource = $reviewDtos[0]->resource;
        $reviewIds = array_map(function (ReviewDto $reviewDto) {
            return $reviewDto->id;
        }, $reviewDtos);

        $existedReviews = Review::select('twoGisId, postedAt')
            ->whereIn('twoGisId', $reviewIds)
            ->where('resource', $resource)
            ->get()
            ->toArray();
        $existedReviewIds = array_column($existedReviews, 'twoGisId');
        $existedReviewsWithTwoGisIdKey = array_column($existedReviews, 'twoGisId', 0);

        dump($existedReviewIds);
        dd($existedReviewsWithTwoGisIdKey);
        foreach ($reviewDtos as $key => $reviewDto) {
            if (
                (in_array($reviewDto->id, $existedReviewIds)) &&
                (
                    $reviewDto->isEdited &&
                    DateTime::createFromFormat(
                        'Y-m-d H:i:s',
                        $existedReviewsWithTwoGisIdKey[$reviewDto->id]->postedAt
                    ) <= $reviewDto->time)
            ) {
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
        foreach ($reviewDtos as $reviewDto) {
            Review::updateOrCreate(
                [
                    'twoGisId' => $reviewDto->id
                ],
                [
                    'resource' => $reviewDto->resource,
                    'postedAt' => $reviewDto->time
                ]
            )
                ->save();
        }

        return $reviewDtos;
    }

}
