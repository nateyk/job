<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationCriteriaTable extends Migration
{
    public function up()
    {
        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('evaluation_group_id');
            $table->string('name');
            $table->unsignedInteger('weight');
            $table->unsignedInteger('max_score')->default(100);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->foreign('evaluation_group_id')
                ->references('id')->on('evaluation_groups')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_criteria');
    }
}

