<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;

class SettingController extends Controller
{
    use ImageTrait;

    public function index()
    {
        return ['settings' => Setting::first()];
    }
    public function deleteAudio($audio)
    {
        if (file_exists(public_path($audio))) {
            unlink(public_path($audio));
        }
    }

    public function uploadAudio($audio, $path ,$old_file = 'test.mp3')
    {
        $audio_name = time() . '_' .uniqid(). '.'. $audio->getClientOriginalExtension();
        $publicPath = public_path($path);
        if (!File::isDirectory($publicPath)) {
            File::makeDirectory($publicPath, 0777, true, true);
        }
        $old_file ??= 'test.mp3';
        $this->deleteAudio($old_file);
        

        $audio->move($publicPath, $audio_name);
        return $path . $audio_name;
    }

    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        $form_data = $request->validated();
        if ($request->hasFile('sound_1')) {
            $setting->sound_1 ? $this->deleteAudio($setting->sound_1) : '';
            $form_data['sound_1'] = $this->uploadAudio($request->file('sound_1'), 'sounds/sound1/');
        }
        if ($request->hasFile('sound_2')) {
            $setting->sound_2 ? $this->deleteAudio($setting->sound_2) : '';
            $form_data['sound_2'] = $this->uploadAudio($request->file('sound_2'), 'sounds/sound2/');
        }
        if ($request->hasFile('sound_3')) {
            $setting->sound_3 ? $this->deleteAudio($setting->sound_3) : '';
            $form_data['sound_3'] = $this->uploadAudio($request->file('sound_3'), 'sounds/sound3/');
        }

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
