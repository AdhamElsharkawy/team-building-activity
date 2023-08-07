<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Team\StoreTeamRequest;
use App\Http\Requests\Admin\Team\UpdateTeamRequest;
use App\Http\Traits\ImageTrait;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    use ImageTrait;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['teams' => Team::latest()->get()]);
    } // end of index

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamRequest $request)
    {
        //image uploading
        $form_data = $request->except(['image']);
        $request->image ? $form_data['image'] = $this->img($request->image, 'images/teams/') : '';

        Team::create($form_data);

        return response()->json(['message' => __('Team Created Successfully')]);
    } // end of store

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        return response()->json(['team' => $team]);
    } // end of edit

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        //image uploading
        $form_data = $request->except(['image']);
        if ($request->image) {
            $team->image ? $this->deleteImg($team->image) : '';
            $form_data['image'] = $this->img($request->image, 'images/teams/');
        } else {
            $form_data['image'] = $team->image;
        }
        $team->update($form_data);

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
