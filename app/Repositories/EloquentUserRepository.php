<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Implémentation Eloquent du UserRepository.
 * Gère les opérations CRUD pour les utilisateurs.
 */
class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * Retourne tous les utilisateurs ayant le rôle "agent".
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allAgents()
    {
        return User::where('role', 'agent')->get();
    }

    /**
     * Récupère un utilisateur par son ID ou lance une exception.
     */
    public function findOrFail(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Crée un nouvel utilisateur avec les données fournies.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Met à jour un utilisateur existant et retourne la version fraîche.
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    /**
     * Supprime un utilisateur.
     */
    public function delete(User $user): void
    {
        $user->delete();
    }
}
