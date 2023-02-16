<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login'])->name('login');
// Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
// Route::post('reset-password/{uuid}', [AuthController::class, 'resetPassword'])->name('reset-password');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('me')->name('me.')->group(function () {
        Route::get('', [AuthController::class, 'user'])->name('user');
    });

    Route::apiResource('permissions', PermissionController::class);

    Route::apiResource('roles', RoleController::class);

    Route::apiResource('article-categories', ArticleCategoryController::class);

    Route::apiResource('users', UserController::class)->except('update');
    Route::post('users/{id}/update', [UserController::class, 'update'])->name('users.update');

    Route::apiResource('articles', ArticleController::class)->except('update');
    Route::post('articles/{id}/update', [ArticleController::class, 'update'])->name('articles.update');
});
