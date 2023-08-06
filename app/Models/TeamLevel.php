<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLevel extends Model
{
    use HasFactory;

    protected $table = 'teams_levels';

    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(Team::class);
    } // end of team

    public function level()
    {
        return $this->belongsTo(Level::class);
    } // end of level
}
