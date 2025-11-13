<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        // authorization handled with Policy, allow here and check later or check role:
        return auth::check();
    }

    public function rules()
    {
        return [
            'type' => 'required|string|max:100',
            'nbr_piece' => 'required|integer|min:0|max:50',
            'surface' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'status' => 'in:disponible,vendu,location',
            'published' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120'

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
