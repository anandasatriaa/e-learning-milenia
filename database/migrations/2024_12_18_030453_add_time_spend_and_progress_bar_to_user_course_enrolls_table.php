<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeSpendAndProgressBarToUserCourseEnrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_course_enrolls', function (Blueprint $table) {
            $table->integer('time_spend')->default(0)->after('status');
            $table->integer('progress_bar')->default(0)->after('time_spend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_course_enrolls', function (Blueprint $table) {
            $table->dropColumn(['time_spend', 'progress_bar']);
        });
    }
}
