<?php

use App\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddEvaluationsModuleAndMovePermissions extends Migration
{
    public function up()
    {
        // Create a dedicated module so the Roles/Permissions matrix shows a clean "Evaluations" row.
        $evaluationsModuleId = Module::firstOrCreate(
            ['module_name' => 'evaluations'],
            ['description' => '']
        )->id;

        DB::table('permissions')
            ->whereIn('name', ['add_evaluations', 'view_evaluations', 'edit_evaluations', 'delete_evaluations'])
            ->update(['module_id' => $evaluationsModuleId]);
    }

    public function down()
    {
        // Move them back to "settings" module (id = 7) used by the legacy manage_settings permission.
        DB::table('permissions')
            ->whereIn('name', ['add_evaluations', 'view_evaluations', 'edit_evaluations', 'delete_evaluations'])
            ->update(['module_id' => 7]);
    }
}

