<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            // $user = request()->user();
            // $view->with('getColumnAttributes', $this->getColumnAttributes());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once base_path().'/app/Helpers/GlobalFunctions.php';
    }

}
