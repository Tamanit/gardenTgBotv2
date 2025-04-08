<?php

namespace App\Dtos\Factories;

use App\Dtos\Models\BranchDto;
use App\Models\Brunch;

class BranchDtoFactory
{
    public static function create(Brunch $model): BranchDto
    {
        return new BranchDto(
            id: $model->twoGisId,
            name: $model->name,
        );
    }
}
