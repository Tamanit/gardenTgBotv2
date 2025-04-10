<?php

namespace App\Services;

use App\Dtos\Factories\ReviewDtoFactory;
use App\Dtos\Models\ReviewDto;
use App\Models\Review;
use App\Services\BaseService;

class TwoGisApiService extends BaseService
{
    protected const TWO_GIS_API_REVIEWS = 'https://public-api.reviews.2gis.com/2.0/orgs/70000001020476016/reviews';

    public function __construct(
        protected ReviewDtoFactory $reviewDtoFactory,
    ) {
    }

    /**
     * @return array<ReviewDto>
     */
    public function requestReviewsFromApi(): array
    {
        $oCUrlSession = curl_init();

        curl_setopt($oCUrlSession, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCUrlSession, CURLOPT_RETURNTRANSFER, true);

        $params = [
            'key' => env('TWO_GIS_API_KEY'),
            'locale' => 'ru_RU',
            'limit' => 50,
            'sort_by' => 'date_created'
        ];

        curl_setopt($oCUrlSession, CURLOPT_URL, self::TWO_GIS_API_REVIEWS . '?' . http_build_query($params));

        $response = curl_exec($oCUrlSession);
        curl_close($oCUrlSession);

        $reviews = json_decode($response, true)['reviews'];

        return array_map(
            function ($review) {
                return $this->reviewDtoFactory->createFromTwoGis($review);
            },
            $reviews
        );
    }
}
