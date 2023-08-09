<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'calculated_score',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    } // end of evaluations

    public function getCalculatedScoreAttribute()
    {
        return $this->type == 'percentage' ? $this->score * $this->weight / 100 : $this->score;
    } // end of getCalculatedScoreAttribute
}
