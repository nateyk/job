<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeCoverLetterColumnNullableJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `job_applications` CHANGE `cover_letter` `cover_letter` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;');
        } else {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->text('cover_letter')->nullable()->change();
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
            DB::statement('ALTER TABLE `job_applications` CHANGE `cover_letter` `cover_letter` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;');
        } else {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->text('cover_letter')->nullable(false)->change();
            });
        }
    }
}
