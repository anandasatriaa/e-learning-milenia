<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisiCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisi_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_cat_id')->constrained('learning_cat')->onDelete('cascade');
            $table->string('nama');
            $table->string('image');
            $table->longText('deskripsi')->nullable();
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('divisi_categories');
    }
}
