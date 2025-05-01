<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

        Route::macro('resourceWithDeleteSelected', function ($name, $controller, array $options = []) {

            Route::resource($name, $controller, $options);

            Route::post("$name/delete-selected", [
                'uses' => "$controller@deleteSelected",
                'as' => "$name.deleteSelected",
            ]);

            Route::post("$name/update-column-selected", [
                'uses' => "$controller@updateColumnSelected",
                'as' => "$name.updateColumnSelected",
            ]);
        });


        Schema::defaultStringLength(191);
        View::composer('*', function ($view) {

            $setting = Setting::all();
            $view->with('setting', $setting);
        });
    }
}
