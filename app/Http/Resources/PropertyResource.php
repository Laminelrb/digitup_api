<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transforme la ressource Property en tableau JSON.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'nbr_piece' => $this->nbr_piece,
            'surface' => $this->surface,
            'price' => $this->price,
            'city' => $this->city,
            'description' => $this->description,
            'status' => $this->status,
            'published' => (bool) $this->published,

            // Liste des images associées à la propriété
            'images' => $this->images->map(fn($img) => [
                'url' => $img->path ? asset('storage/' . $img->path) : null,
                'filename' => $img->filename,
            ]),

            // Informations de base sur le propriétaire
            'owner' => [
                'id' => $this->owner->id,
                'name' => $this->owner->name,
            ],

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
