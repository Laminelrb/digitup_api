<?php

namespace App\Repositories;

use App\Models\Property;
use App\DTOs\FilterPropertiesDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PropertyRepositoryInterface
{
    public function create(array $data): Property;
    public function update(Property $property, array $data): Property;
    public function delete(Property $property): void;
    public function findOrFail(int $id): Property;
    public function paginateFiltered(FilterPropertiesDTO $dto, int $perPage = 15): LengthAwarePaginator;

    //méthodes pour soft delete
    public function forceDelete(Property $property): void;
    public function restore(int $id): Property;
    public function findWithTrashedOrFail(int $id): Property;
    public function paginateWithTrashed(FilterPropertiesDTO $dto, int $perPage = 15): LengthAwarePaginator;
    public function paginateOnlyTrashed(int $perPage = 15): LengthAwarePaginator;
}
