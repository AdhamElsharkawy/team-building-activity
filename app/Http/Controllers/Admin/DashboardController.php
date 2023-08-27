<?php

namespace App\Http\Controllers\Admin;
use App\Models\Team;
use App\Models\User;
use App\Models\Level;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // count of Users
        $users = User::count();
        // count of Levels
        $levels = Level::count();
        // teams count 
        // get teams with levels and evaluations
        $teams = Team::with(['levels' => function ($q){
            $q->with(['evaluations' => function ($q){
                $q->with('criteria');
            }]);
        }])
        ->withCount('users')
        ->orderBy('score', 'DESC')->get();
        $teams_count = $teams->count();

        return response()->json([
            'levels_count' => $levels,
            'users_count' => $users,
            'teams_count' => $teams_count,
            'teams' => $teams,
        ]);        

       
    } //end of index
}
