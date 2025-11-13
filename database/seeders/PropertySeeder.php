<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        Property::create([

            'type' => 'Appartement',
            'nbr_piece' => 3,
            'surface' => 85,
            'price' => 120000,
            'city' => 'Alger',
            'description' => 'Appartement lumineux avec balcon',
            'status' => 'disponible',
            'published' => true,
            'user_id' => 2,
        ]);

        Property::create([

            'type' => 'Villa',
            'nbr_piece' => 5,
            'surface' => 250,
            'price' => 350000,
            'city' => 'Oran',
            'description' => 'Villa avec jardin et piscine',
            'status' => 'disponible',
            'published' => true,
            'user_id' => 2,
        ]);
    }
}
