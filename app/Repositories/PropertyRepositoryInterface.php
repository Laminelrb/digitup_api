<?php

namespace App\Repositories;

use App\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\DTOs\FilterPropertiesDTO;

interface PropertyRepositoryInterface
{
    public function create(array $data): Property;

    public function update(Property $property, array $data): Property;

    public function delete(Property $property): void;

    public function findOrFail(int $id): Property;

    public function paginateFiltered(FilterPropertiesDTO $dto, int $perPage = 15): LengthAwarePaginator;
}
