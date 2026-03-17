<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationEvaluationScoresTable extends Migration
{
    public function up()
    {
        Schema::create('application_evaluation_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('application_evaluation_id');
            $table->unsignedBigInteger('evaluation_criterion_id');
            $table->unsignedInteger('score')->nullable();
            $table->timestamps();

            $table->unique(
                ['application_evaluation_id', 'evaluation_criterion_id'],
                'app_eval_criterion_unique'
            );

            $table->foreign('application_evaluation_id')
                ->references('id')->on('application_evaluations')
                ->onDelete('cascade');

            $table->foreign('evaluation_criterion_id')
                ->references('id')->on('evaluation_criteria')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_evaluation_scores');
    }
}

