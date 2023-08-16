<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    // hide image from json response
    protected $hidden = ['image', 'created_at', 'updated_at'];

    // add image path to json response
    protected $appends = ['image_path'];

    public function getImagePathAttribute($value)
    {
        return asset($this->image);
    }
}
