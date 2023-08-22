<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $guarded = [];

    // hide
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'image_path',
        // 'score',
    ];

    public function getImagePathAttribute()
    {
        return asset($this->image);
    } // end of getImagePathAttribute
    
    public function getScoreAttribute($value){
        return ceil($value);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    } // end of users

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'teams_levels')->withPivot('id', 'score');
    } // end of levels

    public function criteria()
    {
        return $this->belongsToMany(Criteria::class, 'teams_criterias')->withPivot('id', 'score');
    } // end of criteria
}
