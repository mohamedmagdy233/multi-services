<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\GeneralOfferController;
use App\Http\Controllers\Admin\LeaderController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\SettingController;

use App\Http\Controllers\Admin\SubServiceTypeController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::get('login', [AuthController::class, 'index'])->name('admin.login');
        Route::POST('login', [AuthController::class, 'login'])->name('admin.login');

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/', function () {
                return view('admin/index');
            })->name('adminHome');

            #============================ Admin ====================================
            Route::resource('admins', AdminController::class);
            #============================ service_types ====================================

            Route::resourceWithDeleteSelected('service_types', ServiceTypeController::class);
            #============================ sub_service_types ====================================


            Route::resourceWithDeleteSelected('sub_service_types', SubServiceTypeController::class);
            #============================ general_offers ====================================

            Route::resourceWithDeleteSelected('general_offers', GeneralOfferController::class);
            #============================ users ====================================

            Route::resourceWithDeleteSelected('users', UserController::class);
            #============================ leaders ====================================

            Route::resourceWithDeleteSelected('leaders', LeaderController::class);
            #============================ offers ====================================

            Route::resourceWithDeleteSelected('offers', OfferController::class);






            Route::get('my_profile', [AdminController::class, 'myProfile'])->name('myProfile');
            Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

            #============================ Setting ==================================
            Route::get('setting', [SettingController::class, 'index'])->name('settingIndex');
            Route::POST('setting/update/{id}', [SettingController::class, 'update'])->name('settingUpdate');

        });
    });

#=======================================================================
#============================ ROOT =====================================
#=======================================================================
    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('key:generate');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');
        return response()->json(['status' => 'success', 'code' => 1000000000]);
    });

    Route::get('/device-tokens', function () {
        $tokens = \App\Models\DeviceToken::all();
        return response()->json($tokens);
    });
});





