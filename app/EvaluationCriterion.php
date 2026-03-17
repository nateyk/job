<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationCriterion extends Model
{
    protected $fillable = [
        'evaluation_group_id',
        'name',
        'weight',
        'max_score',
        'position',
    ];

    public function group()
    {
        return $this->belongsTo(EvaluationGroup::class, 'evaluation_group_id');
    }

    public function scores()
    {
        return $this->hasMany(ApplicationEvaluationScore::class, 'evaluation_criterion_id');
    }
}

