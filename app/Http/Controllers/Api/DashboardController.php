<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\SeoTrait;
use App\Models\Seo;
use App\Models\Team;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function index()
    {
        $teams = Team::select('id', 'name', 'image', 'color')->with('levels.evaluations.criteria')->get();

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
            $team->levels->first()->evaluations->makeHidden(["level_id"]);
            $team->levels->first()->evaluations->each(function ($evaluation) {
                $evaluation->criteria->makeHidden(["evaluation_id"]);
            });
        });
        return $teams;
    } // end of hideUnnecessaryData
}
