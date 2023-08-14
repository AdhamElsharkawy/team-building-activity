<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LevelController;
use Illuminate\Support\Facades\Route;

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
    Route::get("levels/{level}", [LevelController::class, 'show'])->name('levels.show');

    // teams

    // update teamSorce in level
    Route::post("levels/{level}/updateTeamScore", [LevelController::class, 'updateTeamScore'])->name('levels.updateTeamScore');
});
