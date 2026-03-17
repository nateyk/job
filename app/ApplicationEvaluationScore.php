<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationEvaluationScore extends Model
{
    protected $fillable = [
        'application_evaluation_id',
        'evaluation_criterion_id',
        'score',
    ];

    public function evaluation()
    {
        return $this->belongsTo(ApplicationEvaluation::class, 'application_evaluation_id');
    }

    public function criterion()
    {
        return $this->belongsTo(EvaluationCriterion::class, 'evaluation_criterion_id');
    }
}

