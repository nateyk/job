<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddEvaluationPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            ['name' => 'add_evaluations', 'display_name' => 'Add Evaluations', 'module_id' => 7],
            ['name' => 'view_evaluations', 'display_name' => 'View Evaluations', 'module_id' => 7],
            ['name' => 'edit_evaluations', 'display_name' => 'Edit Evaluations', 'module_id' => 7],
            ['name' => 'delete_evaluations', 'display_name' => 'Delete Evaluations', 'module_id' => 7],
        ];

        $admin = Role::where('name', 'admin')->first();

        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );

            if ($admin && ! $admin->perms->contains('id', $perm->id)) {
                $admin->attachPermission($perm);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::whereIn('name', [
            'add_evaluations',
            'view_evaluations',
            'edit_evaluations',
            'delete_evaluations',
        ])->delete();
    }
}
