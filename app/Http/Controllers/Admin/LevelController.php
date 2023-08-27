<?php

namespace App\Http\Controllers\Admin;

use App\Models\Level;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Level\StoreLevelRequest;
use App\Http\Requests\Admin\Level\UpdateLevelRequest;
use App\Models\Team;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::orderBy('order')->get();

        return response()->json([
            'levels' => $levels,
        ]);
    } // end of index

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLevelRequest $request)
    {
        $form_data = $request->except('type', 'evaluations');
        if ($request->type === 'score') {
            $level = Level::create($form_data);
        } else if ($request->type === 'evaluation') {
            if ($this->checkCriteriaWeights($request)) {
                return $this->checkCriteriaWeights($request);
            }
            $level = Level::create($form_data);
            $this->storeEvaluations($request, $level);
        } else {
            return response()->json([
                'message' => 'Invalid type',
            ], 422);
        }

        $this->associateTeams($level);

        return response()->json([
            'message' => __('Level Created Successfully'),
            'level' => $level,
        ]);
    } // end of store

    /**
     * Check if criteria weights are valid.
     */
    private function checkCriteriaWeights($request)
    {
        $evaluations_form_data = $request->evaluations;
        foreach ($evaluations_form_data as $evaluation_form_data) {
            $criteria_form_data = $evaluation_form_data['criteria'];
            $total_weight = 0;
            foreach ($criteria_form_data as $criterion_form_data) {
                $total_weight += $criterion_form_data['weight'];
            }
            if ($total_weight !== 100) {
                return response()->json([
                    'message' => __('Criteria weights must add up to 100'),
                ], 422);
            }
        }
    } // end of checkCriteriaWeights

    /**
     * Store evaluations and criteria for a level.
     */
    private function storeEvaluations($request, $level)
    {
        $evaluations_form_data = $request->evaluations;
        foreach ($evaluations_form_data as $evaluation_form_data) {
            $evaluation = $level->evaluations()->create([
                'name' => $evaluation_form_data['name'],
            ]);

            $criteria_form_data = $evaluation_form_data['criteria'];
            foreach ($criteria_form_data as $criterion_form_data) {
                $evaluation->criteria()->create($criterion_form_data);
            }
        }
    } // end of storeEvaluations

    /**
     * Associate teams with a level.
     */
    private function associateTeams(Level $level)
    {
        $teams = Team::all();
        $criteria = $level->evaluations->flatMap->criteria;
        
        foreach ($teams as $team) {
            $team->levels()->detach($level->id);
            $team->criteria()->detach($criteria->pluck('id'));

            $team->levels()->attach($level->id);
            $team->criteria()->attach($criteria->pluck('id'));
        }
    } // end of associateTeams

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        $level->load('evaluations.criteria');
        return response()->json([
            'level' => $level,
        ]);
    } // end of edit

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLevelRequest $request, Level $level)
    {
        $form_data = $request->except('type', 'evaluations');
        if ($request->type === 'score') {
            if ($level->type === 'evaluation') $level->evaluations()->delete();
            $level->update($form_data);
        } else if ($request->type === 'evaluation') {
            if ($this->checkCriteriaWeights($request)) {
                return $this->checkCriteriaWeights($request);
            }
            $level->update($form_data);
            $this->updateEvaluations($request, $level);
        } else {
            return response()->json([
                'message' => 'Invalid type',
            ], 422);
        }

        $this->associateTeams($level);

        return response()->json([
            'message' => __('Level Updated Successfully'),
            'level' => $level,
        ]);
    } // end of update

    /**
     * Update evaluations and criteria for a level.
     */
    private function updateEvaluations($request, $level)
    {
        $evaluations_form_data = $request->evaluations;
        $level->evaluations()->delete();
        foreach ($evaluations_form_data as $evaluation_form_data) {
            $evaluation = $level->evaluations()->create([
                'name' => $evaluation_form_data['name'],
            ]);

            $criteria_form_data = $evaluation_form_data['criteria'];
            foreach ($criteria_form_data as $criterion_form_data) {
                $evaluation->criteria()->create([
                    'name' => $criterion_form_data['name'],
                    'weight' => $criterion_form_data['weight'],
                    'order' => $criterion_form_data['order'],
                ]);
            }
        }
    } // end of updateEvaluations

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        $level->delete();
        return response()->json([
            'message' => __('Level Deleted Successfully'),
        ]);
    } // end of destroy

    /**
     * Remove the specified resources from storage.
     */
    public function destroyMany(Request $request)
    {
        $request->validate([
            'levels' => 'required|array|min:1|exists:levels,id',
        ]);
        Level::whereIn('id', $request->levels)->delete();
        return response()->json([
            'message' => __('Levels Deleted Successfully'),
        ]);
    } // end of destroyMany
}
