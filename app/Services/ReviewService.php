<?php

namespace App\Services;

use App\Models\Brunch;
use App\Models\Review;

class ReviewService extends BaseService
{
    const TWO_GIS_API_URL = 'https://public-api.reviews.2gis.com/2.0/branches/';
    const TWO_GIS_API_REVIEWS = 'reviews';

    public function getReviewsFromTwoGis(): array
    {
        $params = [
            'key' => env('TWO_GIS_API_KEY'),
            'locale' => 'ru_RU',
            'limit' => 50,
            'sort_by' => 'date_created'
        ];

        $oCUrlSession = curl_init();
        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCUrlSession, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($oCUrlSession, CURLOPT_RETURNTRANSFER, true);


        $SverdlovaGarden = Brunch::get()[0];
        curl_setopt(
            $oCUrlSession,
            CURLOPT_URL,
            $SverdlovaGarden->getUrlApiForReviews() . '?' . http_build_query($params)
        );

        $response = curl_exec($oCUrlSession);
        $mHttpCode = curl_getinfo($oCUrlSession, CURLINFO_HTTP_CODE);

        curl_close($oCUrlSession);

        $responseJsonDecode = json_decode($response, true);
        return $responseJsonDecode['reviews'];
    }

    public function storeReviews(array $reviews): void
    {
        $lastReviewIds = array_map(
            function ($review) {
                return $review['id'];
            },
            $reviews
        );

        $soredReviewIds = Review::select('twoGisId')->whereIn('twoGisId', $lastReviewIds)->get()->toArray();
        $soredReviewIds = array_column($soredReviewIds, 'twoGisId');
        foreach ($reviews as $review) {
            if (in_array($review['id'], $soredReviewIds)) {
                continue;
            }
            Review::create([
                'twoGisId' => $review['id'],
            ]);
            echo $this->formatMessage($review);
        }
    }

    protected function formatMessage(array $twiGisReview): string
    {
        $BrunchId = $twiGisReview['object']['id'];
        $time = $twiGisReview['date_edited'] ?? $twiGisReview['date_created'];
        $rating = $twiGisReview['rating'];
        $text = $twiGisReview['text'];

        $Brunch = Brunch::where('two_gis_id', $BrunchId)->first();


        return "Кофейня {$Brunch->name}</br>
            , {$time}</br>
            {$rating} из 5,
            '{$text}'";
    }
}
