<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function evaluations()
    {
        return $this->belongsTo(Evaluation::class);
    } // end of evaluations
}
