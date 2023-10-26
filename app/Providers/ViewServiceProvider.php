<?php

namespace App\Providers;

use App\Http\View\Composers\ProfileComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // Using class based composers...
        View::composer('profile', ProfileComposer::class);
        View::composer('sidebar', function ($view) {
            $counts = Cache::remember('counts', 3600, function() {
                return [ 'tools_1' => Tools1::count(), 'users' => User::count() ];
            });
        
            return $view->with('counts', $counts);
        });
        
        // Using closure based composers...
        View::composer('dashboard', function ($view) {
            //
        });
    }
}