<?php

namespace App\Http\Controllers\Api;

use App\Models\Seo;
use App\Models\Level;
use Illuminate\Http\Request;
use App\Http\Traits\SeoTrait;
use App\Http\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use App\Models\Team;

class LevelController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function index()
    {
        $levels = Level::with('evaluations.criteria')->orderBy('order')->get();

        $levels = $this->hideLevelsUnnecessaryData($levels);

        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["levels" => $levels],
            $this->seo('Users', 'home-page', $seo->description, $seo->keywords),
            'Levels retreived successfully',
        );
    } // end of index

    private function hideLevelsUnnecessaryData($levels)
    {
        $levels->makeHidden(["order", "created_at", "updated_at"]);
        $levels->each(function ($level) {
            $level->evaluations->makeHidden(["level_id", "created_at", "updated_at"]);
            $level->evaluations->each(function ($evaluation) {
                $evaluation->criteria->makeHidden(["evaluation_id", "created_at", "updated_at"]);
            });
        });
        return $levels;
    } // end of hideLevelsUnnecessaryData

    public function show($id)
    {
        $level = Level::with('evaluations.criteria')->find($id);
        if (!$level) return response()->json(["status" => "error", "message" => "Level not found"], 404);
        $level = $this->hideLevelUnnecessaryData($level);
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["level" => $level],
            $this->seo('Users', 'home-page', $seo->description, $seo->keywords),
            'Levels retreived successfully',
        );
    } // end of show

    private function hideLevelUnnecessaryData($level)
    {
        $level->makeHidden(["order", "created_at", "updated_at"]);
        $level->evaluations->makeHidden(["level_id", "created_at", "updated_at"]);
        $level->evaluations->each(function ($evaluation) {
            $evaluation->criteria->makeHidden(["evaluation_id", "created_at", "updated_at"]);
        });
        return $level;
    } // end of hideLevelUnnecessaryData

    public function update(Request $request)
    {
        $validator = $this->validateUpdateLevel($request);
        if ($validator) return $validator;

        $level = Level::find($request->level_id);
        if ($this->submitTeamsLevel($request->teams, $level)) return $this->submitTeamsLevel($request->teams, $level);

        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ['level' => $level->load('evaluations.criteria')],
            $this->seo('Users', 'home-page', $seo->description, $seo->keywords),
            'level updated successfully',
        );
    } // end of update

    private function validateUpdateLevel($request)
    {
        $validator = $this->apiValidationTrait($request->all(), [
            'level_id' => 'required|exists:levels,id',
            'teams' => 'required|array',
            'teams.*.id' => 'required|exists:teams,id',
            'teams.*.score' => 'nullable|numeric',
            'teams.*.evaluations' => 'required_if:teams.*.score,null|array',
            'teams.*.evaluations.*.id' => 'required|exists:evaluations,id',
            'teams.*.evaluations.*.criteria' => 'required|array',
            'teams.*.evaluations.*.criteria.*.id' => 'required|exists:criterias,id',
            'teams.*.evaluations.*.criteria.*.score' => 'required|numeric|min:0|max:5',
        ]);
        return $validator;
    } // end of validateUpdateLevel

    private function submitTeamsLevel($teams, $level)
    {
        foreach ($teams as $team_index => $team) {
            $team = Team::find($team['id']);
            if (!$team) return response()->json(["status" => "error", "message" => "Team not found"], 404);
            if ($level->type == 'score' && isset($teams[$team_index]['score'])) {
                $level->teams()->updateExistingPivot($team->id, ['score' => $teams[$team_index]['score']]);
            } else if ($level->type == 'evaluation' && isset($teams[$team_index]['evaluations'])) {
                $evaluations = $teams[$team_index]['evaluations'];
                $score = 0;
                foreach ($evaluations as $evaluation_index => $evaluation) {
                    $evaluation = $level->evaluations()->find($evaluation['id']);
                    if (!$evaluation) return response()->json(["status" => "error", "message" => "Evaluation not found"], 404);
                    $evaluation_score = 0;
                    $criteria = $teams[$team_index]['evaluations'][$evaluation_index]['criteria'];
                    foreach ($criteria as $index => $criterion) {
                        $criterion = $evaluation->criteria()->find($criterion['id']);
                        if (!$criterion) return response()->json(["status" => "error", "message" => "Criterion not found"], 404);
                        $evaluation_score += $teams[$team_index]['evaluations'][$evaluation_index]['criteria'][$index]['score'] * $criterion->weight / 100;
                        $criterion->teams()->updateExistingPivot($team->id, ['score' => $teams[$team_index]['evaluations'][$evaluation_index]['criteria'][$index]['score']]);
                    }
                    $score += $evaluation_score;
                }
                $level->teams()->updateExistingPivot($team->id, ['score' => $score]);
                // update the team overall score
                $team->update(['score' => $team->levels()->sum('score')]);
            } else {
                return response()->json(["status" => "error", "message" => "Invalid data, debuging: check the level type or you may sent inapproprate data that is not compatible with the level type"], 422);
            }
        }
    } // end of submitTeamsLevel
}
