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

    // public function getScoreAttribute()
    // {
    //     $levels = $this->levels()->get();
    //     if ($levels->count() == 0) return 0;
    //     $score = 0;
    //     foreach ($levels as $level) {
    //         if ($level->type == 'evaluation') {
    //             $evaluations = $level->evaluations()->get();
    //             if ($evaluations->count() == 0) return 0;
    //             $evaluation_score = 0;
    //             foreach ($evaluations as $evaluation) {
    //                 $criteria = $evaluation->criteria()->get();
    //                 if ($criteria->count() == 0) return 0;
    //                 $criteria_score = 0;
    //                 foreach ($criteria as $criterion) {
    //                     $criteria_score += ($criterion->teams()->find($this->id)->pivot->score) * ($criterion->weight / 100);
    //                 }
    //                 $evaluation_score += $criteria_score;
    //             }
    //             $score += $evaluation_score;
    //         } else {
    //             $score += $level->pivot->score;
    //         }
    //     }
    //     return $score;
    // } // end of getScoreAttribute

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
