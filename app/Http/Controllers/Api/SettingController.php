<?php

namespace App\Http\Controllers\Api;

use App\Models\Information;
use App\Models\Setting;
use App\Http\Traits\GeneralTrait;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        $settings = Setting::first();
        $info = Information::first();
        return $this->apiSuccessResponse(
            ["settings" => $settings,
            "info" => $info
            ],
            'Settings retreived successfully',
        );
    }
}
