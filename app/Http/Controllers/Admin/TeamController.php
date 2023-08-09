<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Team\StoreTeamRequest;
use App\Http\Requests\Admin\Team\UpdateTeamRequest;
use App\Http\Traits\ImageTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    use ImageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['teams' => Team::latest()->withCount('users')->get()]);
    } // end of index

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['users' => User::latest()->get()]);
    } // end of create

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamRequest $request)
    {
        //image uploading
        $form_data = $request->except(['image', 'user_ids']);
        $request->image ? $form_data['image'] = $this->img($request->image, 'images/teams/') : '';
        DB::beginTransaction();
        try {
            $team = Team::create($form_data);
            if ($request->user_ids) {
                // associate users to team
                $users = User::whereIn('id', $request->user_ids)->get();
                foreach ($users as $user) {
                    $user->update(['team_id' => $team->id]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $team->image ? $this->deleteImg($team->image) : '';
            return response()->json(['message' => __('Something went wrong')], 500);
        }
        return response()->json(['message' => __('Team Created Successfully')]);
    } // end of store

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $team->load(['users', 'levels.evaluations.criteria']);
        return response()->json(['team' => $team]);
    } // end of show

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        return response()->json(['team' => $team->load('users'), 'users' => User::latest()->get()]);
    } // end of edit

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        //image uploading
        $form_data = $request->except(['image', 'user_ids']);
        $request->image ? $form_data['image'] = $this->img($request->image, 'images/teams/') : '';
        DB::beginTransaction();
        try {
            $team->update($form_data);
            if ($request->user_ids) {
                // associate users to team
                $users = User::whereIn('id', $request->user_ids)->get();
                foreach ($users as $user) {
                    $user->update(['team_id' => $team->id]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $team->image ? $this->deleteImg($team->image) : '';
            return response()->json(['message' => __('Something went wrong')], 500);
        }
        return response()->json(['message' => __('Team Updated Successfully')]);
    } // end of update

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $team->image != 'assets/images/team.png' ? $this->deleteImg($team->image) : '';
        // $team->users()->delete();
        $team->delete();

        return response()->json(['message' => __('Team Deleted Successfully')], 200);
    } // end of destroy

    /**
     * Remove the specified resources from storage.
     */
    public function destroyAll(Request $request)
    {
        $teams = Team::whereIn('id', $request->teams)->get();
        foreach ($teams as $team) {
            $team->image != 'assets/images/team.png' ? $this->deleteImg($team->image) : '';
            // $team->users()->delete();
            $team->delete();
        }

        return response()->json(['message' => __('Teams Deleted Successfully')]);
    } // end of destroyAll
}
