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
        $teams = Team::select('id', 'name', 'image', 'color')->orderBy('order')->get();
        // hide image
        $teams->makeHidden(["image"]);
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["teams" => $teams],
            $this->seo('Teams', 'home-page', $seo->description, $seo->keywords),
            'Teams retreived successfully',
        );
    } // end of index

    public function showLevel($id, $level_id)
    {
        $team = Team::with(['levels' => function ($query) use ($level_id) {
            $query->where('level_id', $level_id)->with('evaluations.criteria');
        }])->find($id);
        if (!$team) return response()->json(["status" => "error", "message" => "Team not found"], 404);

        // hide unnecessary data
        $team->makeHidden(["image"]);
        $team->levels->makeHidden(["pivot", "created_at", "updated_at"]);
        $team->levels->first()->evaluations->makeHidden(["level_id"]);
        $team->levels->first()->evaluations->each(function ($evaluation) {
            $evaluation->criteria->makeHidden(["evaluation_id"]);
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
