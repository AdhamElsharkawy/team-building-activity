<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];
    // hide image from json response
    protected $hidden = ['image', 'created_at', 'updated_at', 'id'];

    // add image path to json response
    protected $appends = ['image_path', 'winner_sound', 'sound_up', 'sound_down'];



    public function getWinnerSoundAttribute($value)
    {
        return asset($this->sound_1);
    }
    public function getSoundUpAttribute($value)
    {
        return asset($this->sound_2);
    }
    public function getSoundDownAttribute($value)
    {
        return asset($this->sound_3);
    }

    public function getImagePathAttribute($value)
    {
        return asset($this->image);
    }
}
