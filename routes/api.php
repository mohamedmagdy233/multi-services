<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LeaderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/store-fcm', [AuthController::class, 'storeFcm']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);

        Route::post('/login-with-social', [AuthController::class, 'loginWithSocial']);

        Route::group(['middleware' => JwtMiddleware::class], function () {
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('update-profile', [AuthController::class, 'updateProfile']);
            Route::post('delete-account', [AuthController::class, 'deleteAccount']);
            Route::post('change-password', [AuthController::class, 'changePassword']);
            Route::post('update-phone', [AuthController::class, 'updatePhone']);


            Route::post('add-offer', [UserController::class, 'addOffer']);
            Route::get('get-my-offers', [UserController::class, 'getMyOffers']);
            Route::get('close-offer/{id}', [UserController::class, 'closeOffer']);
            Route::post('add-or-delete-fav', [UserController::class, 'addOrDeleteFav']);
            Route::get('get-my-fav', [UserController::class, 'getFav']);

            Route::get('get-my-chats', [UserController::class, 'getMyChats']);
            Route::post('create-room', [UserController::class, 'createRoom']);
            Route::post('send-message', [UserController::class, 'sendMessage']);
            Route::get('get-room-messages/{id}', [UserController::class, 'getRoomMessages']);

            Route::get('get-notifications', [UserController::class, 'getNotifications']);
            Route::post('see-notification', [UserController::class, 'seeNotification']);


        });

        Route::group(['middleware' => 'optional.auth'], function () {
            Route::get('/get-service_types', [UserController::class, 'getServiceTypes']);
            Route::get('/get-sub-service_types', [UserController::class, 'getSubServiceTypes']);

            //home
            Route::get('/get-home', [UserController::class, 'getHome']);
            Route::get('get-offers', [UserController::class, 'getOffers']);
            Route::get('get-offers-on-map', [UserController::class, 'getOffersOnMap']);
            Route::get('get-offer-details/{id}', [UserController::class, 'getOfferDetails']);
            Route::get('get-filtered-offers', [UserController::class, 'getFilteredOffers']);

            //settings
            Route::get('get-settings', [UserController::class, 'getSettings']);





        });
    });
    Route::group(['middleware' => JwtMiddleware::class], function () {

        Route::group(['prefix' => 'leader'], function () {

    Route::get('get-offers', [LeaderController::class, 'getOffers']);
    Route::get('get-offer-details/{id}', [LeaderController::class, 'getOfferDetails']);
    Route::post('accept-or-reject-offer', [LeaderController::class, 'acceptOrRejectOffer']);
     });

    });

// Command to run migrate fresh seed
Route::get('/seed', function () {
    if (app()->environment('local')) {
        Artisan::call('migrate:fresh --seed');
        return response()->json(['message' => 'Database migrated and seeded successfully.']);
    } elseif (app()->environment('production')) {
        $key = request()->query('key');
        if ($key === 'amer') {
            return response()->json(['message' => 'Database migrated and seeded successfully']);
        } else {
            return response()->json(['message' => ' your key is wrong'], 403);
        }
    }
});
});
