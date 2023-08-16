<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

require __DIR__ . '/auth.php';

Route::group(['as' => 'api.', 'middleware' => 'jwt:api'], function () {
    // user apis
    Route::get("users", [UserController::class, 'index'])->name('users');
    Route::get("users/{user}", [UserController::class, 'show'])->name('users.show');
    
    // levels apis
    // levels
    // levels 
    Route::get("levels", [LevelController::class, 'index'])->name('levels');
    Route::get("levels/{id}", [LevelController::class, 'show'])->name('levels.show');
    Route::put("levels", [LevelController::class, 'update'])->name('levels.update');

    // teams
    Route::get("teams", [TeamController::class, 'index'])->name('teams');
    Route::get("teams/{id}", [TeamController::class, 'show'])->name('teams.show');
    Route::get("teams/{id}/level/{level_id}", [TeamController::class, 'showLevel'])->name('teams.showLevel');

    // update teamSorce in level
    Route::post("levels/{level}/updateTeamScore", [LevelController::class, 'updateTeamScore'])->name('levels.updateTeamScore');

    // settings 
    Route::get("settings", [SettingController::class, 'index'])->name('settings');
    
    // update teamSorce in alllevels
    // Route::post("levels/updateTeamScoreAllLevels", [LevelController::class, 'updateTeamScoreAllLevels'])->name('levels.updateTeamScoreAllLevels');
    
    // Route::get("levels/{level}", [LevelController::class, 'show'])->name('levels.show');

    // dashboard
    Route::get("leaderboard", [DashboardController::class, 'index'])->name('leaderboard');
});
