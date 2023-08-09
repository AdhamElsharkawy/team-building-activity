<?php

namespace App\Http\Controllers\Api;

use App\Models\Seo;
use App\Models\Team;
use App\Models\Level;
use App\Models\Criteria;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Http\Traits\SeoTrait;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LevelController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function index()
    {
        $levels = Level::with('teams.evaluations.criteria')->orderBy('order')->latest()->get();
        $levels->makeHidden(["created_at", "updated_at"]);
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["levels" => $levels],
            $this->seo('Users', 'home-page', $seo->description, $seo->keywords),
            'Levels retreived successfully',
        );
    }

    public function updateTeamScore(Request $request, $id)
    {
        $level = Level::where('id',$id)->with('evaluations', 'teams')->first();
        // $level = Level::find($id)->load('evaluations', 'teams');
        if (!$level) return response()->json(["status" => "error", "message" => "Level not found"], 404);
        // suppose request->teams is array of team ids and score of each team in that level

        if ($request->type == 'score') {          
            foreach ($request->teams as $team) {
                // validation on score
                if ($team['score'] < 0 || $team['score'] > 500) {
                    return response()->json(["status" => "error", "message" => "Score must be between 0 and 500"], 422);
                }
                $level->teams()->updateExistingPivot(
                    $team['id'],
                    ['score' => $team['score']]
                );
                $data[] = [
                    'team_id' => $team['id'],
                    'level_id' => $id,
                    'score' => $team['score'],
                ];
            }
        }
        if ($request->type == 'evaluation') {
            // return $request->teams;
            foreach ($request->teams as $team) {
                $teamId = $team['id'];
                $evaluationCount = 0;
                $score = 0;
                foreach ($team['evaluations'] as $evaluation) {
                    foreach ($evaluation['criteria'] as $criterion) {
                        $criterionId = $criterion['id'];
                        $scoreFromRequest = $criterion['score'];
                        // validate
                        if ($scoreFromRequest < 0 || $scoreFromRequest > 5) {
                            return response()->json(["status" => "error", "message" => "Score must be between 0 and 5"], 422);
                        }
                        // Update score in criteria table                        
                        Criteria::where('id', $criterionId)
                            ->update(['score' => $scoreFromRequest]);
                        // Get weight of criterion                        
                        $weight = Criteria::find($criterionId)->weight;
                        $score += $scoreFromRequest * $weight;
                        // echo $score . "<br>";

                    }
                    $evaluationCount++;
                }
                // dd($score);
                $avgScore = $score / $evaluationCount;
                // Update pivot table
                
                $level->teams()
                    ->updateExistingPivot($teamId, ['score' => $avgScore]);

                //  echo $avgScore . " " . $teamId . "<br>".

                $data[] = [
                    'team_id' => $teamId,
                    'level_id' => $id,
                    'score' => $avgScore
                ];
            }
        }
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            $data,
            $this->seo('Users', 'home-page', $seo->description, $seo->keywords),
            'TeamScore in level updated successfully',
        );
    }

    public function updateTeamScoreAllLevels()
    {
    }
}
