<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;

class SettingController extends Controller
{
    use ImageTrait;
  
    public function index()
    {
        return ['settings' => Setting::first()];
    }



    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        $form_data = $request->validated();
        // decode the title json data from the request and convert it to array
        //image uploading
        if ($request->image) {
            $setting->image ? $this->deleteImg($setting->image) : '';
            $form_data['image'] = $this->img($request->image, 'images/settings/');
        } else {
            $form_data['image'] = $setting->image;
        }

        $setting->update($form_data);
        return response(['message' => 'Settings updated successfully', 'setting' => $setting], 200);
    }

}
