<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationGroup extends Model
{
    protected $fillable = ['name', 'description', 'active'];

    public function criteria()
    {
        return $this->hasMany(EvaluationCriterion::class, 'evaluation_group_id')
            ->orderBy('position')
            ->orderBy('id');
    }

    public function applicationEvaluations()
    {
        return $this->hasMany(ApplicationEvaluation::class, 'evaluation_group_id');
    }
}

