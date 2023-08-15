<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\SeoTrait;
use App\Models\Seo;

class UserController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function index()
    {
        $users = User::with('team')->get();
        $users->makeHidden(["image","email_verified_at","created_at","updated_at"]);
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            ["users" => $users],
            $this->seo('Users', 'home-page', $seo->description, $seo->keywords),
            'Users retreived successfully',
        );
    } //end of index

    public function show($user_id)
    {
        $user = User::where('id',$user_id)->with('team:id,name')->first();
        $user->makeHidden(["image","email_verified_at","created_at","updated_at"]);

        if (!$user) return response()->json(["status" => "error", "message" => "User not found"], 404);

        $seo = Seo::first();
        return $this->apiSuccessResponse(
            $user,
            $this->seo('User', 'home-page', $seo->description, $seo->keywords),
            'User retrieved successfully',
        );
    } //end of show

}
