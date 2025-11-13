<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy pour la gestion des utilisateurs.
 * Seuls les administrateurs peuvent voir, créer, modifier ou supprimer des utilisateurs.
 */
class UserPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        // Seuls les admins peuvent voir tous les utilisateurs
        return $user->role === 'admin';
    }


    public function view(User $user, User $model): bool
    {
        // Seuls les admins peuvent voir les détails des utilisateurs
        return $user->role === 'admin';
    }


    public function create(User $user): bool
    {
        // Seuls les admins peuvent creer des utilisateurs
        return $user->role === 'admin';
    }


    public function update(User $user, User $model): bool
    {
        // Seuls les admins peuvent modifier des utilisateurs
        return $user->role === 'admin';
    }


    public function delete(User $user, User $model): bool
    {
        // Seuls les admins peuvent suprimer des utilisateurs
        return $user->role === 'admin';
    }
}
