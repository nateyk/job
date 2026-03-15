<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeInterviewTypeColumnNullableInInterviewSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `interview_schedules` CHANGE `interview_type` `interview_type` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;');
        } else {
            Schema::table('interview_schedules', function (Blueprint $table) {
                $table->text('interview_type')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `interview_schedules` CHANGE `interview_type` `interview_type` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;');
        } else {
            Schema::table('interview_schedules', function (Blueprint $table) {
                $table->text('interview_type')->nullable(false)->change();
            });
        }
    }
}
