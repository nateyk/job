<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkExperienceFieldsToJobApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Work experience fields (optional unless enabled by job.required_columns).
            $table->double('total_work_experience_years')->nullable()->after('zip_code');
            $table->string('employer_name')->nullable()->after('total_work_experience_years');
            $table->text('employer_address')->nullable()->after('employer_name');
            $table->string('job_position')->nullable()->after('employer_address');
            $table->double('employer_salary')->nullable()->after('job_position');
            $table->string('supervisor_name')->nullable()->after('employer_salary');
            $table->string('supervisor_mobile')->nullable()->after('supervisor_name');
            $table->double('expected_monthly_salary')->nullable()->after('supervisor_mobile');
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'total_work_experience_years',
                'employer_name',
                'employer_address',
                'job_position',
                'employer_salary',
                'supervisor_name',
                'supervisor_mobile',
                'expected_monthly_salary',
            ]);
        });
    }
}

