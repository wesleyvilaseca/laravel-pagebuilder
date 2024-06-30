<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // 'App\Models\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Permission::with('roles')->get()->map(function ($permission) {
        //     Gate::define($permission->label, function (User $user) use ($permission) {
        //         return $user->hasPermission($permission);
        //     });
        // });

        // Gate::before(function (User $user, $ability) {
        //     if ($user->hasAnyRoles('admin'))
        //         return true;
        // });
    }
}
