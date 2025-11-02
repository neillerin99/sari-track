<?php

use App\Http\Controllers\BottleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Public Routes
Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::post('signin', [UserController::class, 'signin']);
});


// Protected Routes
Route::middleware('auth:api')->group(function () {
    Route::apiResources([
        'items' => ItemController::class,
        'categories' => CategoryController::class,
        'stores' => StoreController::class,
        'credits' => CreditController::class,
        'contacts' => ContactController::class,
        'bottles' => BottleController::class,
        'restocks' => RestockController::class
    ]);
    Route::apiResource('users', UserController::class)->except('store', 'index');
});

