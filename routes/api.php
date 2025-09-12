<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('users')->group(function () {
    Route::post('signin', [UserController::class, 'signin']);
});

Route::middleware('auth:api')->group(function () {
    Route::apiResources([
        'items' => ItemController::class,
        'categories' => CategoryController::class,
        'stores' => StoreController::class
    ]);
});

