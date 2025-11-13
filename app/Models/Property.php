<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    /**
     * Champs pouvant être assignés en masse
     */
    protected $fillable = [
        'user_id',
        'type',
        'nbr_piece',
        'surface',
        'price',
        'city',
        'description',
        'status',
        'published',
        'title'
    ];

    /**
     * Booted: Hook pour gérer la génération automatique du title
     */
    public static function booted()
    {
        // Avant création
        static::creating(function ($property) {
            if (empty($property->title)) {
                $property->title = self::generateTitle($property);
            }
        });

        // Avant mise à jour
        static::updating(function ($property) {
            // Regénérer le title si vide ou basé sur l'ancien format
            if (empty($property->title) || str_contains($property->title, $property->getOriginal('city'))) {
                $property->title = self::generateTitle($property);
            }
        });
    }

    /**
     * Génération automatique du title basé sur type, nombre de pièces et ville
     */
    public static function generateTitle($p): string
    {
        $parts = [];
        if (!empty($p->type)) $parts[] = ucfirst($p->type);
        if (!empty($p->nbr_piece)) $parts[] = $p->nbr_piece . ' pièces';
        if (!empty($p->city)) $parts[] = 'à ' . $p->city;

        return implode(' ', $parts);
    }

    /**
     * Relation 1:n avec les images
     */
    public function images()
    {
        return $this->hasMany(Property_images::class);
    }

    /**
     * Relation n:1 avec le propriétaire
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
