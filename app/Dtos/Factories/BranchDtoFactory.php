<?php

namespace App\Dtos\Factories;

use App\Dtos\Models\BranchDto;
use App\Models\Brunch;

class BranchDtoFactory
{
    public static function create(?Brunch $model = null): BranchDto
    {
        return new BranchDto(
            id: $model->twoGisId ?? 0,
            name: $model->name ?? 'Неизвестная кофейня',
        );
    }
}
