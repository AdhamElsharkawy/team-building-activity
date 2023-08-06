<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    } // end of criteria

    public function level()
    {
        return $this->belongsTo(Level::class);
    } // end of level
}
