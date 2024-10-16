<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $permissions = [
            'user_management.index' => '1,2',
            'user_management.create' => '1,2',
            'user_management.edit' => '1,2',
            'user_management.delete' => '1,2',

            'master_city.index' => '1,2,3',
            'master_city.create' => '1,2',
            'master_city.edit' => '1,2',
            'master_city.delete' => '1,2',

            'duty_trip.index' => '1,2,3',
            'duty_trip.create' => '1,2,3',
            'duty_trip.reslove' => '1,2',
        ];

        foreach (array_keys($permissions) as $permission) {
            
            Gate::define($permission, function ($user) use ($permissions, $permission) {

                $splited = explode(',', $permissions[$permission]);
                return array_search($user->role_id, $splited);
            });
        }
    }
}
