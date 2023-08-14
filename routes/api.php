<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LevelController;
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
    // levels 
    Route::get("levels", [LevelController::class, 'index'])->name('levels');

    // teams
    Route::get("teams/{id}/level/{level_id}", [TeamController::class, 'showLevel'])->name('teams.showLevel');

    // update teamSorce in level
    Route::post("levels/{level}/updateTeamScore", [LevelController::class, 'updateTeamScore'])->name('levels.updateTeamScore');
});
