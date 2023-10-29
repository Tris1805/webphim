<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Info;
use Illuminate\View\View;

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
        $info = Info::find(1);
        view()->share('info', $info);
    }
}
