<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\SeoTrait;
use App\Models\Seo;
use App\Models\Team;

class DashboardController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function index()
    {
        $teams = Team::with(['levels' => function ($q){
            $q->with(['evaluations' => function ($q){
                $q->with('criteria');
            }]);
        }])
        ->withCount('users')
        ->orderBy('score', 'DESC')->get();

        $teams = $this->hideUnnecessaryData($teams);
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["teams" => $teams],
            $this->seo('teams', 'home-page', $seo->description, $seo->keywords),
            'Dashboard retrieved successfully',
        );
    } // end of index

    private function hideUnnecessaryData($teams)
    {
        $teams->makeHidden(["created_at", "updated_at", "image"]);
        $teams->each(function ($team) {
            $team->levels->makeHidden(["pivot", "created_at", "updated_at"]);
            // append the score form the pivot table to the level
            $team->levels->each(function ($level) {
                $level->score = ceil($level->pivot->score);
            });
            $team->levels->first()->evaluations->makeHidden(["level_id"]);
            $team->levels->first()->evaluations->each(function ($evaluation) {
                $evaluation->criteria->makeHidden(["evaluation_id"]);
            });
        });
        return $teams;
    } // end of hideUnnecessaryData
}
