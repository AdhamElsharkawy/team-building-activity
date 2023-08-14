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
}
