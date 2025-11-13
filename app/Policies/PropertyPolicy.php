<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

/**
 * Policy pour la gestion des autorisations sur les propriétés.
 * Définit qui peut voir, créer, mettre à jour ou supprimer une propriété.
 */
class PropertyPolicy
{
    /**
     * Tout le monde peut voir la liste des propriétés (visiteurs inclus)
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Tout le monde peut voir les détails d'une propriété
     */
    public function view(?User $user, Property $property): bool
    {
        return true;
    }

    /**
     * Seuls les agents ou admins peuvent créer des propriétés
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['agent', 'admin']);
    }

    /**
     * Mise à jour : admin peut tout, agent peut modifier ses propres propriétés
     */
    public function update(User $user, Property $property): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'agent' && $user->id === $property->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Suppression (soft delete) : admin peut tout, agent peut supprimer ses propres propriétés
     */
    public function delete(User $user, Property $property): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'agent' && $user->id === $property->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Voir les propriétés supprimées (corbeille) : admins uniquement
     */
    public function viewTrashed(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Restaurer une propriété supprimée : ADMINS UNIQUEMENT
     */
    public function restore(User $user, Property $property): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Suppression définitive (force delete) : ADMINS UNIQUEMENT
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return $user->role === 'admin';
    }
}
