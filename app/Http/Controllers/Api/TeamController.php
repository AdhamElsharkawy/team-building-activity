<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\SeoTrait;
use App\Models\Seo;

class TeamController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function index()
    {
        // order by the score in the pivot table with levels
        $teams = Team::withCount('levels')->orderBy('score', 'DESC')->get();
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
        $team = Team::with(['levels' => function ($q) use ($id) {
            $q->with(['evaluations' => function ($q) use ($id) {
                $q->with(['criteria' => function ($q) use ($id) {
                    $q->with(['teams' => function ($q) use ($id) {
                        $q->where('team_id', $id);
                    }]);
                }]);
            }]);
        }])->find($id);

        if (!$team) return response()->json(["status" => "error", "message" => "Team not found"], 404);

        $team = $this->hideTeamUnnecessaryData($team);
        $team->levels->each(function ($level) {
            $level->score = $level->pivot->score;
        });

        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["team" => $team],
            $this->seo('Teams', 'home-page', $seo->description, $seo->keywords),
            'Teams retreived successfully',
        );
    } // end of show

    private function hideTeamUnnecessaryData($team)
    {
        $team->makeHidden(["image"]);
        $team->levels->makeHidden(["pivot", "created_at", "updated_at"]);
        $team->levels->each(function ($level) {
            $level->evaluations->each(function ($evaluation) {
                $evaluation->makeHidden(["level_id", "created_at", "updated_at"]);
                $evaluation->criteria->makeHidden(["evaluation_id"]);
                $evaluation->criteria->each(function ($criteria) {
                    // append the first team score to the criteria
                    if ($criteria->teams->count() > 0) {
                        $criteria->score = $criteria->teams->first()->pivot->score;
                        $criteria->calculated_score = $criteria->score * $criteria->weight / 100;
                        $criteria->makeHidden(["teams"]);
                    }
                });
            });
        });
        return $team;
    } // end of hideTeamUnnecessaryData

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
        $team = $this->hideTeamUnnecessaryData($team);
        $team['level'] = $team->levels->first();
        $team['level']['score'] = $team->levels->first()->pivot->score;
        $team->makeHidden(["levels"]);

        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["team" => $team],
            $this->seo('Teams', 'home-page', $seo->description, $seo->keywords),
            'Teams retreived successfully',
        );
    } // end of show
}
