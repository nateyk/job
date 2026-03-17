<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationEvaluationsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('application_evaluations')) {
            // Table already exists (for example, created manually or by a previous migration).
            // Skip creating it again to avoid foreign key and duplicate table errors.
            return;
        }

        Schema::create('application_evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_application_id');
            $table->unsignedBigInteger('evaluation_group_id');
            $table->unsignedBigInteger('evaluator_id')->nullable();
            $table->unsignedInteger('total_score')->nullable();
            $table->text('overall_comment')->nullable();
            $table->timestamps();

            $table->unique(['job_application_id', 'evaluation_group_id'], 'app_group_unique');

            $table->foreign('job_application_id')
                ->references('id')->on('job_applications')
                ->onDelete('cascade');

            $table->foreign('evaluation_group_id')
                ->references('id')->on('evaluation_groups')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_evaluations');
    }
}

