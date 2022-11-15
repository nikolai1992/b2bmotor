<?php

namespace App\Providers;

use App\User;
use App\Order;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPostPolicies();
        $this->registerManagersPolicies();
        $this->registerClientsPolicies();
        $this->registerOrderPolicies();
        $this->registerCategoriesPolicies();
    }

    public function registerPostPolicies()
    {

//        Gate::define('update-client', function ($user, Post $post) {
//            return $user->hasAccess(['update-client']) or $user->id == $post->user_id;
//        });

    }

    public function registerOrderPolicies()
    {

        Gate::define('see-orders-tab', function ($user) {
            return $user->hasAccess(['create-order']) or $user->hasAccess(['update-order']);
        });

        Gate::define('show-all-orders', function ($user) {
            return $user->hasAccess(['show-all-orders']);
        });

        Gate::define('create-order', function ($user) {
            return $user->hasAccess(['create-order']);
        });

        Gate::define('update-orders', function ($user) {
            return $user->hasAccess(['update-orders']);
        });
        Gate::define('update-order', function ($user, Order $order) {
            return $user->hasAccess(['update-order']) or $user->id == $order->user->id;
        });

    }

    public function registerClientsPolicies()
    {

        Gate::define('see-clients-tab', function ($user) {
            return $user->hasAccess(['update-client']);
        });

        Gate::define('update-clients', function ($user) {
            return $user->hasAccess(['update-clients']);
        });
        Gate::define('update-client', function ($user, User $client) {
            return $user->hasAccess(['update-client']) or $user->id == $client->id;
        });

    }

    public function registerManagersPolicies()
    {

        Gate::define('see-managers-tab', function ($user) {
            return $user->hasAccess(['create-manager']) or $user->hasAccess(['update-manager']);
        });

        Gate::define('create-manager', function ($user) {
            return $user->hasAccess(['create-manager']);
        });

        Gate::define('update-manager', function ($user, User $manager) {
            return $user->hasAccess(['update-manager']) or $user->id == $manager->id;
        });

    }

    public function registerCategoriesPolicies()
    {

        Gate::define('show_catalog', function ($user) {
            return $user->hasAccess(['show_catalog']);
        });

    }
}
