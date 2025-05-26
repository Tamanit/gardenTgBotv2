<?php

namespace App\Dtos\Factories;

use App\Dtos\Models\ReviewDto;
use App\Models\Brunch;
use DateInterval;
use DateTime;

use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isEmpty;

class ReviewDtoFactory
{
    public function createFromTwoGis(array $data): ReviewDto
    {
        $dateString = $data['date_edited'] ?? $data['date_created'];
        $match = [];

        preg_match_all('/\d\d\d\d-\d\d-\d\d/', $dateString, $match);
        $date = $match[0][0];
        preg_match_all('/T\d\d:\d\d/', $dateString, $match);
        $time = trim($match[0][0], 'T');

        $dateTime = (new DateTime("{$date} {$time}"))
            ->sub(DateInterval::createFromDateString('2 hours'));

        if (Brunch::where('twoGisId', $data['object']['id'])->exists()) {
            $branch = Brunch::where('twoGisId', $data['object']['id'])->first();
        } else {
            $branchFaked = new Brunch;
            $branchFaked->twoGisId = $data['object']['id'];
            $branchFaked->name = $data['object']['id'];
            $branch = $branchFaked;
        }

        $photosUrls = null;
        if (!empty($data['photos'])) {
            if (count($data['photos']) > 10) {
                $data['photos'] = array_slice($data['photos'], 0, 10);
            }

            foreach ($data['photos'] as $photo) {
                $photosUrls[] = [
                    'media' => $photo['preview_urls']['url'],
                    'type' => 'photo',
                ];
            }
        }

        return new ReviewDto(
            id: $data['id'],
            text: $data['text'],
            rating: $data['rating'],
            sender: $data['user']['name'],
            time: $dateTime,
            resource: '2Гис',
            photos: $photosUrls,
            isEdited: $data['date_edited'] != null,
            branchDto: BranchDtoFactory::create($branch),
        );
    }

    public function createFromGoogleMapApi(array $data): ReviewDto
    {
        $dateTime = (new DateTime(date('d-m-Y H:i', $data['time'])))->sub(
            DateInterval::createFromDateString('2 hours')
        );

        return new ReviewDto(
            id: Hash::make($data['text']),
            text: $data['text'],
            rating: $data['rating'],
            sender: $data['author_name'],
            time: $dateTime,
            resource: 'Google',
            photos: null,
            isEdited: false,
            branchDto: BranchDtoFactory::create($data['brunch']),
        );
    }
}
