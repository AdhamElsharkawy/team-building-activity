<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Admin\SeoController;

//don't forget it has an admin prefix
require __DIR__ . '/auth.php';
Route::group(['middleware' => ['admin:sanctum'], 'as' => 'admin.'], function () {
    //dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
    //users
    Route::resource('users', UserController::class)->except(['show', 'create']);
    Route::delete('users/delete/all', [UserController::class, 'destroyAll']);
    //Evaluation
    Route::resource('evaluations', EvaluationController::class)->except(['show', 'create']);
    Route::delete('evaluations/delete/all', [EvaluationController::class, 'destroyAll']);
    //seos
    Route::resource('seos', SeoController::class)->only(['index', 'update']);
});
