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

    public function viewAny(?User $user): bool
    {
        // Tout le monde peut voir la liste (visiteurs inclus)
        return true;
    }


    public function view(?User $user, Property $property): bool
    {
        // Tout le monde peut voir les détails
        return true;
    }


    public function create(User $user): bool
    {
        // Seuls les agents ou admins peuvent créer des propriétés
        return in_array($user->role, ['agent', 'admin']);
    }


    public function update(User $user, Property $property): bool
    {
        if ($user->role === 'admin') {
            // Les admins peuvent modifier toutes les propriétés
            return true;
        }

        if ($user->role === 'agent' && $user->id === $property->user_id) {
            // Un agent peut modifier ses propres propriétés
            return true;
        }

        return false; // Autres cas refusés
    }


    public function delete(User $user, Property $property): bool
    {
        if ($user->role === 'admin') {
            // Les admins peuvent supprimer toutes les propriétés
            return true;
        }

        if ($user->role === 'agent' && $user->id === $property->user_id) {
            // Un agent peut supprimer ses propres propriétés
            return true;
        }

        return false; // Autres cas refusés
    }
}
