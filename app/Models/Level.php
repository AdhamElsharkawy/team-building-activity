<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'type',
    ];

    public function getTypeAttribute()
    {
        return $this->evaluations()->exists() ? 'evaluation' : 'score';
    } // end of getTypeAttribute

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    } // end of evaluations

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'teams_levels')->withPivot('id', 'score');
    } // end of teams
}
