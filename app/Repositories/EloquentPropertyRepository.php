<?php

namespace App\Repositories;

use App\Models\Property;
use App\DTOs\FilterPropertiesDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Implémentation Eloquent du PropertyRepository.
 * Gère les opérations CRUD et la pagination filtrée des propriétés.
 */
class EloquentPropertyRepository implements PropertyRepositoryInterface
{
    /** Crée une nouvelle propriété */
    public function create(array $data): Property
    {
        return Property::create($data);
    }

    /** Met à jour une propriété existante et retourne la version fraîche */
    public function update(Property $property, array $data): Property
    {
        $property->update($data);
        return $property->fresh();
    }

    /** Supprime une propriété */
    public function delete(Property $property): void
    {
        $property->delete();
    }

    /** 
     * Récupère une propriété par son ID avec ses relations ou lance une exception
     */
    public function findOrFail(int $id): Property
    {
        return Property::with('images', 'owner')->findOrFail($id);
    }

    /** 
     * Retourne une liste paginée filtrée selon les critères du DTO
     */
    public function paginateFiltered(FilterPropertiesDTO $dto, int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::with('images', 'owner');

        // Filtres simples
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

        // Recherche fulltext (MySQL) ou fallback LIKE
        if ($dto->q) {
            $query->where(function ($subQuery) use ($dto) {
                $subQuery->whereRaw(
                    'MATCH(title, description) AGAINST(? IN BOOLEAN MODE)',
                    [$dto->q]
                )->orWhere('title', 'like', "%{$dto->q}%")
                    ->orWhere('description', 'like', "%{$dto->q}%");
            });
        }

        // Tri si précisé
        if ($dto->sortBy) {
            $query->orderBy($dto->sortBy, $dto->sortDir ?? 'desc');
        }

        return $query->paginate($perPage);
    }
}
