<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\SeoTrait;
use App\Models\Seo;

class TeamController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function index()
    {
        $teams = Team::select('id', 'name', 'image', 'color')->get();
        // hide unnecessary data
        $teams->makeHidden(["created_at", "updated_at", "image"]);

        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["teams" => $teams],
            $this->seo('Teams', 'home-page', $seo->description, $seo->keywords),
            'Teams retreived successfully',
        );
    } // end of index

    public function show($id)
    {
        $team = Team::with('levels.evaluations.criteria')->find($id);
        if (!$team) return response()->json(["status" => "error", "message" => "Team not found"], 404);

        // load team critera score from the pivot


        // hide unnecessary data
        $team->makeHidden(["created_at", "updated_at", "image"]);
        $team->levels->makeHidden(["pivot", "created_at", "updated_at"]);
        $team->levels->first()->evaluations->makeHidden(["level_id"]);
        $team->levels->first()->evaluations->each(function ($evaluation) {
            $evaluation->criteria->makeHidden(["evaluation_id"]);
        });

        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["team" => $team],
            $this->seo('Teams', 'home-page', $seo->description, $seo->keywords),
            'Teams retreived successfully',
        );
    } // end of show

    public function showLevel($id, $level_id)
    {
        $team = Team::with(['levels' => function ($q) use ($level_id, $id) {
            $q->where('level_id', $level_id)->with(['evaluations' => function ($q) use ($id) {
                $q->with(['criteria' => function ($q) use ($id) {
                    $q->with(['teams' => function ($q) use ($id) {
                        $q->where('team_id', $id);
                    }]);
                }]);
            }]);
        }])->find($id);
        if (!$team) return response()->json(["status" => "error", "message" => "Team not found"], 404);

        // hide unnecessary data
        $team->makeHidden(["image"]);
        $team->levels->makeHidden(["pivot", "created_at", "updated_at"]);
        $team->levels->first()->evaluations->makeHidden(["level_id"]);
        $team->levels->first()->evaluations->each(function ($evaluation) {
            $evaluation->criteria->makeHidden(["evaluation_id"]);
            $evaluation->criteria->each(function ($criteria) {
                // append the first team score to the criteria
                $criteria->score = $criteria->teams->first()->pivot->score;
                $criteria->calculated_score = $criteria->score * $criteria->weight / 100;
                $criteria->makeHidden(["teams"]);
            });
        });
        $team['level'] = $team->levels->first();
        $team->makeHidden(["levels"]);

        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["team" => $team],
            $this->seo('Teams', 'home-page', $seo->description, $seo->keywords),
            'Teams retreived successfully',
        );
    } // end of show
}
