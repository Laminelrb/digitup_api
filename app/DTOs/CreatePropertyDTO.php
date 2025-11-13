<?php

namespace App\DTOs;

class CreatePropertyDTO
{
    public function __construct(
        public ?int $userId,
        public string $type,
        public ?int $nbr_piece = null,
        public ?int $surface = null,
        public ?float $price = null,
        public ?string $city = null,
        public ?string $description = null,
        public string $status = 'disponible',
        public bool $published = false,
        public ?string $title = null,
        public array $images = []
    ) {}
}
