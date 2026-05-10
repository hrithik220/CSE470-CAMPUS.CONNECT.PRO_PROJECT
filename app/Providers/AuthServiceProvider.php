<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Item;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('update-item', function (User $user, Item $item) {
            return $user->id === $item->seller_id;
        });

        Gate::define('delete-item', function (User $user, Item $item) {
            return $user->id === $item->seller_id || $user->isAdmin();
        });

        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });
    }
}
