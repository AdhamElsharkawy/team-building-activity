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
}
