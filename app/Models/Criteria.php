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

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    } // end of evaluations

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'teams_criterias')->withPivot('id', 'score');
    } // end of teams

    // trigger a function on update
    public static function boot()
    {
        parent::boot();
        static::updated(function ($criteria) {
            $teams = $criteria->teams()->get();
            $score = 0;
            foreach ($teams as $team) {
                $score += $team->pivot->score;
            }
            // update the level score
            $criteria->evaluation->level->teams()->updateExistingPivot($team->id, ['score' => $score]);
        });
    } // end of boot
}
