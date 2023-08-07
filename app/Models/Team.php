<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'image_path',
        'score',
    ];

    public function getImagePathAttribute()
    {
        return asset($this->image);
    } // end of getImagePathAttribute

    public function getScoreAttribute()
    {
        return $this->levels()->sum('score');
    } // end of getScoreAttribute

    public function users()
    {
        return $this->hasMany(User::class);
    } // end of users

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'teams_levels')->withPivot('id', 'score');
    } // end of levels
}
