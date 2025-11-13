<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use App\DTOs\CreateUserDTO;
use App\DTOs\UpdateUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Service pour la gestion des utilisateurs.

 */
class UserService
{
    public function __construct(private UserRepositoryInterface $repo) {}

    /**
     * Crée un utilisateur à partir d'un DTO et hash le mot de passe.
     */
    public function createFromDTO(CreateUserDTO $dto): User
    {
        return $this->repo->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role
        ]);
    }


    /**
     * Met à jour un utilisateur à partir d'un DTO.
     * Hash le mot de passe si défini.
     */
    public function updateFromDTO(User $user, UpdateUserDTO $dto): User
    {
        $data = array_filter([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password ? Hash::make($dto->password) : null,
            'role' => $dto->role
        ], fn($v) => !is_null($v));

        return $this->repo->update($user, $data);
    }

    /**
     * Supprime un utilisateur.
     */
    public function delete(User $user): void
    {
        $this->repo->delete($user);
    }
}
