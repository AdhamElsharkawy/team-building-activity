<?php

use App\Http\Controllers\Admin\InformationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;

//don't forget it has an admin prefix
require __DIR__ . '/auth.php';
Route::group(['middleware' => ['admin:sanctum'], 'as' => 'admin.'], function () {
    // dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
    // users
    Route::resource('users', UserController::class)->except(['show', 'create']);
    Route::delete('users/delete/many', [UserController::class, 'destroyMany'])->name('users.destroy.many');
    // teams
    Route::resource('teams', TeamController::class);
    Route::delete('teams/delete/many', [TeamController::class, 'destroyMany'])->name('teams.destroy.many');
    // Flush Scores
    Route::post('teams/score/reset', [TeamController::class, 'destroyScore'])->name('teams.destroy.score');


    // levels
    Route::resource('levels', LevelController::class)->except(['show', 'create']);
    Route::delete('levels/delete/many', [LevelController::class, 'destroyMany'])->name('levels.destroy.many');
    //seos
    Route::resource('seos', SeoController::class)->only(['index', 'update']);
    //settings
    Route::resource('settings', SettingController::class)->only(['index', 'update']);
    // information
    Route::resource('informations', InformationController::class)->only(['index', 'update']);
});
