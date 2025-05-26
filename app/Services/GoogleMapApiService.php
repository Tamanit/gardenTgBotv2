<?php

namespace App\Services;

use App\Dtos\Factories\ReviewDtoFactory;
use App\Dtos\Models\ReviewDto;
use App\Models\Brunch;
use App\Models\Review;
use App\Services\BaseService;

class GoogleMapApiService extends BaseService
{
    protected const GOOGLE_MAP_API_URL = 'https://maps.googleapis.com/maps/api/place/details/json';

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
            'key' => env('GOOGLE_MAP_API_KEY'),
            'language' => 'ru',
            'reviews_sort' => 'newest',
            'without_my_first_review' => true,
            'fields' => 'reviews',
        ];

        $branches = Brunch::whereNotNull('GoogleMapBrunchId')->get();

        $reviews = [];
        foreach ($branches as $branch) {
            $params['placeid'] = $branch['GoogleMapBrunchId'];

            curl_setopt($oCUrlSession, CURLOPT_URL, self::GOOGLE_MAP_API_URL . '?' . http_build_query($params));

            $response = curl_exec($oCUrlSession);
            $resultFromResponse = json_decode($response, true)['result'];
            $reviewsFromResponse = key_exists('reviews', $resultFromResponse) ? $resultFromResponse['reviews'] : [];

            $reviewsFromResponse = array_map(function ($review) use ($branch) {
                $review['brunch'] = $branch;
                return $review;
            }, $reviewsFromResponse);
            $reviews = array_merge(
                $reviews,
                array_slice(
                    $reviewsFromResponse,
                    0,
                    5
                )
            );
        }

        curl_close($oCUrlSession);

        return array_map(
            function ($review) {
                return $this->reviewDtoFactory->createFromGoogleMapApi($review);
            },
            $reviews
        );
    }
}
