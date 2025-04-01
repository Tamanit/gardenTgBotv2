<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brunch extends Model
{
    public function getUrlApiForReviews(): string
    {
        return "https://public-api.reviews.2gis.com/2.0/branches/{$this->two_gis_id}/reviews";
    }

    public $timestamps = false;
}
