<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // La vérification réelle est faite via Policy dans le controller
        return true;
    }

    /**
     * Règles de validation pour la mise à jour d'un bien immobilier.
     */
    public function rules(): array
    {
        return [
            'type' => 'sometimes|string|max:255',
            'nbr_piece' => 'sometimes|integer|min:1',
            'surface' => 'sometimes|numeric|min:1',
            'price' => 'sometimes|numeric|min:0',
            'city' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:disponible,vendu,location',
            'published' => 'sometimes|boolean',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ];
    }

    /**
     * Messages personnalisés 
     */
    public function messages(): array
    {
        return [
            'nbr_piece.integer' => 'Le nombre de pièces doit être un entier.',
            'images.*.image' => 'Chaque fichier doit être une image valide.',
            'images.*.mimes' => 'Chaque image doit être jpeg, png, jpg, gif ou webp.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 5MB.',
        ];
    }
}
