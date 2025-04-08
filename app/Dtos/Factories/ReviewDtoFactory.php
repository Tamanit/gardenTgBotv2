<?php

namespace App\Dtos\Factories;

use App\Dtos\Models\ReviewDto;
use App\Models\Brunch;

class ReviewDtoFactory
{
    public function create(array $data): ReviewDto
    {
        $dateString = $data['date_edited'] ?? $data['date_created'];
        $match = [];

        preg_match_all('/\d\d\d\d-\d\d-\d\d/', $dateString, $match);
        $date = $match[0][0];
        preg_match_all('/T\d\d:\d\d/', $dateString, $match);
        $time = trim($match[0][0], 'T');

        $dateTime = new \DateTime("{$date} {$time}");

        return new ReviewDto(
            id: $data['id'],
            text: $data['text'],
            rating: $data['rating'],
            sender: $data['user']['name'],
            time: $dateTime,
            branchDto: BranchDtoFactory::create(
                Brunch::where('twoGisId', $data['object']['id'])->first()
            ),
        );
    }
}
