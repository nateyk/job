<?php

use Illuminate\Database\Migrations\Migration;

class AlterOnboardStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (\Illuminate\Support\Facades\Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `on_board_details` CHANGE `hired_status` `hired_status` ENUM('offered','accepted','rejected','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
