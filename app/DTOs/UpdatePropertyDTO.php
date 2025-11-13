<?php

namespace App\DTOs;

class UpdatePropertyDTO
{
    public function __construct(
        public ?string $type = null,
        public ?int $nbr_piece = null,
        public ?int $surface = null,
        public ?float $price = null,
        public ?string $city = null,
        public ?string $description = null,
        public ?string $status = null,
        public ?bool $published = null,
        public ?string $title = null,
        public array $images = [] // nouveau fichiers à ajouter
    ) {}
}
