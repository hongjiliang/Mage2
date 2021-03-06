<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        foreach (glob(app_path().'/Helpers/Admin/*.php') as $filename){
            require_once($filename);

        }

        foreach (glob(app_path() . '/Helpers/Front/*.php') as $filename) {
            require_once($filename);

        }


    }
}
