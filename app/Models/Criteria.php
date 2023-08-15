<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'score',
    ];

    public function getScoreAttribute()
    {
        $teams = $this->teams()->get();
        if ($teams->count() == 0) return 0;
        $score = 0;
        foreach ($teams as $team) {
            $score += $team->pivot->score;
        }
        return $score;
    } // end of getScoreAttribute

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    } // end of evaluations

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'teams_criterias')->withPivot('id', 'score');
    } // end of teams
}
