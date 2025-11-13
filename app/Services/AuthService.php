<?php

namespace App\Services;

use App\DTOs\LoginUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Service pour l'authentification des utilisateurs.*/


class AuthService
{
    /**
     * Authentifie un utilisateur et retourne ses informations et un token API.
     */
    public function login(LoginUserDTO $dto): array
    {
        // Récupération de l'utilisateur par email
        $user = User::where('email', $dto->email)->first();

        // Vérification des identifiants
        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        // Génération d'un token via Sanctum
        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
