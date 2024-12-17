<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryAndLearningColumnsToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
            $table->unsignedBigInteger('divisi_category_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('learning_cat_id')->after('divisi_category_id');

            // Tambahkan foreign key jika diperlukan
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('divisi_category_id')->references('id')->on('divisi_categories')->onDelete('set null');
            $table->foreign('learning_cat_id')->references('id')->on('learning_cat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['category_id']);
            $table->dropForeign(['divisi_category_id']);
            $table->dropForeign(['learning_cat_id']);

            // Drop kolom
            $table->dropColumn(['category_id', 'divisi_category_id', 'learning_cat_id']);
        });
    }
}
