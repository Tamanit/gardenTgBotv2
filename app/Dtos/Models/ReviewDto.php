<?php

namespace App\Dtos\Models;

class ReviewDto
{
    public function __construct(
        public string $id,
        public string $text,
        public string $rating,
        public string $sender,
        public \DateTime $time,
        public string $resource,
        public ?array $photos = null,
        public bool $isEdited = false,
        public ?BranchDto $branchDto = null,
    ) {}
}
