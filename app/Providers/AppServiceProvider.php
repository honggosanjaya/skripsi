<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Gate::define('administrasi', function(User $user){
            $value = 0;
            $role_value = $user->role;
            if($role_value == 1){
                $value = $role_value;
            }            
            return $value;
        });

        Gate::define('supervisor', function(User $user){
            $value = 0;
            $role_value = $user->role;
            if($role_value == 2){
                $value = $role_value;
            }            
            return $value;
        });
    }
}
