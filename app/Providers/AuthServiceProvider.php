<?php

namespace App\Providers;

use Domain\User\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENVP') == 'production') {
        }

        $this->registerPolicies();

        Gate::before(function (User $user) {
            if ($user->hasRole('sa')) {
                return true;
            }
        });

        Gate::define('ownResource', function (User $user, $resource = null) {
            if ($resource instanceof User) {
                return $resource->getKey() == $user->getKey() || $user->hasRole('sa');
            }
        });
    }
}
