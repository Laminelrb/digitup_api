<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Property;
use App\Policies\UserPolicy;
use App\Policies\PropertyPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Les mappings modèle → policy
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Property::class => PropertyPolicy::class,
    ];

    /**
     * Enregistrement des policies et définition des Gates
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
