<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreign('user_id')->references('ID')->on('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_modul_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('nilai_quiz')->nullable();
            $table->unsignedTinyInteger('nilai_essay')->nullable();
            $table->text('komentar')->nullable();
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
        Schema::dropIfExists('nilai');
    }
}
