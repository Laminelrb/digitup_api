<?php

namespace App\DTOs;


class FilterPropertiesDTO
{
    public function __construct(
        public ?string $type = null,
        public ?string $city = null,
        public ?float $minPrice = null,
        public ?float $maxPrice = null,
        public ?int $minRooms = null,
        public ?int $maxRooms = null,
        public ?string $status = null,
        public ?bool $published = null,
        public ?string $q = null,         // recherche texte
        public ?string $sortBy = null,    // champ pour trier
        public ?string $sortDir = null    // direction du tri : asc/desc
    ) {}
}
