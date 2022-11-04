<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/users/username/{user:username}', [UserController::class, 'showByUsername']);
Route::get('/users/username/{user:username}/stories', [UserController::class, 'showByUsernameWithStories']);

Route::resource('categories', CategoryController::class)->only(['index', 'show']);
Route::get('/categories/slug/{category:slug}', [CategoryController::class, 'showBySlug']);
Route::get('/categories/slug/{category:slug}/stories', [CategoryController::class, 'showBySlugWithStories']);

Route::resource('stories', StoryController::class)->only(['index', 'show']);
Route::get('/stories/slug/{story:slug}', [StoryController::class, 'showBySlug']);

// Private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class)->except('post');
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::resource('stories', StoryController::class)->except(['index', 'show']);
});

// Fallback route
Route::fallback(function() {
    return response()->json([
        'message' => 'Route not found'
    ], 404);
});