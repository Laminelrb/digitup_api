<?php

namespace App\Services;

use App\Repositories\PropertyRepositoryInterface;
use App\DTOs\CreatePropertyDTO;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;

/**
 * Service pour la gestion des propriétés.

 */

class PropertyService
{
    public function __construct(private PropertyRepositoryInterface $repo) {}

    /**
     * Crée une propriété à partir d'un DTO et gère l'upload des images.
     */
    public function createFromDTO(CreatePropertyDTO $dto): Property
    {
        // Données principales
        $data = [
            'user_id' => $dto->userId,
            'type' => $dto->type,
            'nbr_piece' => $dto->nbr_piece,
            'surface' => $dto->surface,
            'price' => $dto->price,
            'city' => $dto->city,
            'description' => $dto->description,
            'status' => $dto->status,
            'published' => $dto->published,
        ];

        // Création via le repository
        $property = $this->repo->create($data);

        // Upload et association des images
        foreach ($dto->images as $uploadedFile) {
            $path = $uploadedFile->store('properties', 'public'); // validation faite avant
            $property->images()->create([
                'path' => $path,
                'filename' => $uploadedFile->getClientOriginalName()
            ]);
        }

        return $property->load('images', 'owner');
    }



    /**
     * Met à jour une propriété à partir d'un DTO et ajoute de nouvelles images si présentes.
     */
    public function updateFromDTO(Property $property, $dto)
    {
        // Ne mettre à jour que les champs définis
        $data = array_filter([
            'type' => $dto->type ?? null,
            'nbr_piece' => $dto->nbrPiece ?? null,
            'surface' => $dto->surface ?? null,
            'price' => $dto->price ?? null,
            'city' => $dto->city ?? null,
            'description' => $dto->description ?? null,
            'status' => $dto->status ?? null,
            'published' => $dto->published ?? null,
        ], fn($v) => !is_null($v));

        $property = $this->repo->update($property, $data);

        // Ajout de nouvelles images si présentes
        if (!empty($dto->images)) {
            foreach ($dto->images as $file) {
                $path = $file->store('properties', 'public');
                $property->images()->create([
                    'path' => $path,
                    'filename' => $file->getClientOriginalName()
                ]);
            }
        }

        return $property->fresh('images', 'owner');
    }


    /** Soft delete - ne supprime PAS les fichiers */
    public function delete(Property $property): void
    {
        // Soft delete uniquement (les fichiers restent)
        $this->repo->delete($property);
    }

    /** Force delete - supprime DÉFINITIVEMENT avec les fichiers */
    public function forceDelete(Property $property): void
    {
        // Supprimer les images du disque
        foreach ($property->images as $img) {
            Storage::disk('public')->delete($img->path);
        }

        // Suppression définitive
        $this->repo->forceDelete($property);
    }
}
