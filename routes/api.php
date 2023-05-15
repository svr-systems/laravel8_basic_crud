<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstablishmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::group(
        [
            'middleware' => 'auth:api'
        ],
        function () {
            Route::get('logout', [AuthController::class, 'logout']);
        }
    );
});

Route::group(["middleware" => "auth:api"], function () {
    Route::apiResource("establishments", EstablishmentController::class);
});