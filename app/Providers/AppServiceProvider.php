<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrap();

        Gate::define('administrasi', function(User $user){
            $value = 0;
            $role_value = session('role');
            if($role_value == "administrasi"){
                $value = $role_value;
            }            
            return $value;
        });

        Gate::define('supervisor', function(User $user){
            $value = 0;
            $role_value = session('role');
            if($role_value == "supervisor"){
                $value = $role_value;
            }            
            return $value;
        });
        Gate::define('owner', function(User $user){
            $value = 0;
            $role_value = session('role');
            if($role_value == "owner"){
                $value = $role_value;
            }            
            return $value;
        });
        $url=\Request::capture()->getHttpHost();

        if (str_contains($url, 'suralaya.')) {
            if (str_contains($url, 'salesman-dev.')) {
                $this->app->bind('path.public', function() {
                    return base_path().'/../../public_html/project/salesman-dev';
                });
            }else if (str_contains($url, 'salesman-jtrg.')) {
                $this->app->bind('path.public', function() {
                    return base_path().'/../../public_html/project/salesman-jtrg';
                });
            }else if (str_contains($url, 'salesman-surya.')) {
                $this->app->bind('path.public', function() {
                    return base_path().'/../../public_html/project/salesman-surya';
                });
            }else if (str_contains($url, 'salesman-mandiri.')) {
                $this->app->bind('path.public', function() {
                    return base_path().'/../../public_html/project/salesman-mandiri';
                });
            }
        }
    }
}
