<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationEvaluation extends Model
{
    protected $fillable = [
        'job_application_id',
        'evaluation_group_id',
        'evaluator_id',
        'total_score',
        'overall_comment',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function group()
    {
        return $this->belongsTo(EvaluationGroup::class, 'evaluation_group_id');
    }

    public function scores()
    {
        return $this->hasMany(ApplicationEvaluationScore::class, 'application_evaluation_id');
    }
}

