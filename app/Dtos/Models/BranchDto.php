<?php

namespace App\Dtos\Models;

class BranchDto
{
    public function __construct(
        public string $id,
        public ?string $name = null,
    ) {}
}
