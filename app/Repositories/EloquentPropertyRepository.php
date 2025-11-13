<?php

namespace App\Repositories;

use App\Models\Property;
use App\DTOs\FilterPropertiesDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPropertyRepository implements PropertyRepositoryInterface
{
    public function create(array $data): Property
    {
        return Property::create($data);
    }

    public function update(Property $property, array $data): Property
    {
        $property->update($data);
        return $property->fresh();
    }

    /** Soft delete de la propriété */
    public function delete(Property $property): void
    {
        $property->delete(); // Utilise automatiquement le soft delete
    }

    /** Suppression définitive (force delete) */
    public function forceDelete(Property $property): void
    {
        $property->forceDelete();
    }

    /** Restaurer une propriété soft-deleted */
    public function restore(int $id): Property
    {
        $property = Property::withTrashed()->findOrFail($id);
        $property->restore();
        return $property->fresh();
    }

    /** Récupérer une propriété (exclut les soft-deleted par défaut) */
    public function findOrFail(int $id): Property
    {
        return Property::with('images', 'owner')->findOrFail($id);
    }

    /** Récupérer une propriété même si soft-deleted */
    public function findWithTrashedOrFail(int $id): Property
    {
        return Property::withTrashed()->with('images', 'owner')->findOrFail($id);
    }

    /** Pagination filtrée (exclut les soft-deleted) */
    public function paginateFiltered(FilterPropertiesDTO $dto, int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::with('images', 'owner');

        // Filtres existants...
        if ($dto->city) {
            $query->where('city', $dto->city);
        }

        if ($dto->type) {
            $query->where('type', $dto->type);
        }

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if (!is_null($dto->minPrice)) {
            $query->where('price', '>=', (float) $dto->minPrice);
        }

        if (!is_null($dto->maxPrice)) {
            $query->where('price', '<=', (float) $dto->maxPrice);
        }

        if ($dto->q) {
            $query->where(function ($subQuery) use ($dto) {
                $subQuery->whereRaw(
                    'MATCH(title, description) AGAINST(? IN BOOLEAN MODE)',
                    [$dto->q]
                )->orWhere('title', 'like', "%{$dto->q}%")
                    ->orWhere('description', 'like', "%{$dto->q}%");
            });
        }

        if ($dto->sortBy) {
            $query->orderBy($dto->sortBy, $dto->sortDir ?? 'desc');
        }

        return $query->paginate($perPage);
    }

    /** Pagination incluant les propriétés soft-deleted (pour admins) */
    public function paginateWithTrashed(FilterPropertiesDTO $dto, int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::withTrashed()->with('images', 'owner');

        // Appliquer les mêmes filtres...
        if ($dto->city) {
            $query->where('city', $dto->city);
        }

        if ($dto->type) {
            $query->where('type', $dto->type);
        }

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if (!is_null($dto->minPrice)) {
            $query->where('price', '>=', (float) $dto->minPrice);
        }

        if (!is_null($dto->maxPrice)) {
            $query->where('price', '<=', (float) $dto->maxPrice);
        }

        if ($dto->sortBy) {
            $query->orderBy($dto->sortBy, $dto->sortDir ?? 'desc');
        }

        return $query->paginate($perPage);
    }

    /** Liste uniquement des propriétés soft-deleted */
    public function paginateOnlyTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return Property::onlyTrashed()
            ->with('images', 'owner')
            ->orderBy('deleted_at', 'desc')
            ->paginate($perPage);
    }
}
