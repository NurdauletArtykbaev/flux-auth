<?php


use Illuminate\Support\Facades\Route;
use Nurdaulet\FluxAuth\Http\Controllers\UserRatingController;
use Nurdaulet\FluxAuth\Http\Controllers\ComplaintController;
use Nurdaulet\FluxAuth\Http\Controllers\UserController;
use Nurdaulet\FluxAuth\Http\Controllers\AuthController;
use Nurdaulet\FluxAuth\Http\Controllers\RoleController;
use Nurdaulet\FluxAuth\Http\Controllers\IdentificationController;
use Nurdaulet\FluxAuth\Http\Controllers\UserAddressController;

Route::group(['prefix' => 'api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('otp', [AuthController::class, 'requestOtp'])->middleware(['throttle:5']);
        Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware(['throttle:5', 'auth:sanctum']);
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
    });

    Route::group(['prefix' => 'user', 'middleware' => 'auth:sanctum'], function () {
        Route::get('', [UserController::class, 'me']);
        Route::put('', [UserController::class, 'update']);
        Route::delete('', [UserController::class, 'destroy']);
        Route::post('avatar', [UserController::class, 'uploadAvatar']);
//    Route::get('delivery', [UserController::class, 'getDelivery']);
//    Route::post('delivery', [UserController::class, 'updateDelivery']);

        if (config('flux-auth.identify.enabled')) {

            Route::post('identify', [IdentificationController::class, 'identifyUser']);
            Route::post('get-identify-number', [IdentificationController::class, 'getIdentifyNumber']);
            Route::post('identify-number', [IdentificationController::class, 'saveIdentifyNumber']);
        }
        Route::post('verify', [UserController::class, 'uploadVerifyImage']);
        Route::post('complete-cooperation', [UserController::class, 'completeCooperation']);

        if (config('flux-auth.identify.enabled')) {
            Route::post('contract', [UserController::class, 'saveContract']);
            Route::delete('contract', [UserController::class, 'destroyContract']);
        }

        Route::prefix('address')->group(function () {
            Route::get('', [UserAddressController::class, 'index']);
            Route::post('', [UserAddressController::class, 'store']);
            Route::put('{address}', [UserAddressController::class, 'update']);
            Route::put('{address}/main', [UserAddressController::class, 'updateMainAddress']);
            Route::delete('{address}', [UserAddressController::class, 'destroy']);
        });
        Route::post('last-online', [UserController::class, 'online']);

        /** other users */
        Route::post('{id}/complain', [ComplaintController::class, 'store']);
        Route::post('{id}/rate', [UserRatingController::class, 'store']);

    });
    Route::get('user/{id}/about', [UserController::class, 'aboutUser']);
    Route::get('roles', RoleController::class)->middleware('auth:sanctum');

});
