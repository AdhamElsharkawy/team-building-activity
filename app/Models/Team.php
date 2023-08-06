<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    } // end of users

    public function levels()
    {
        return $this->belongsToMany(Level::class)->withPivot('id', 'score');
    } // end of levels
}
