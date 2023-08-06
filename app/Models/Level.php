<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    } // end of evaluations

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('id', 'score');
    } // end of teams
}
