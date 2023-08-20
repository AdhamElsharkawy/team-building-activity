<?php

namespace App\Http\Controllers\Api;

use App\Models\Seo;
use App\Models\Setting;
use App\Models\Information;
use App\Http\Traits\SeoTrait;
use App\Http\Traits\GeneralTrait;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function index()
    {
        $settings = Setting::first();
        $info = Information::first();
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            [
                "settings" => $settings,
                "info" => $info
            ],
            $this->seo($seo->title, 'settings-page', $seo->description, $seo->keywords, $seo->image),
        );
    }
}
