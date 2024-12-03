<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modul_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_modul_id')->constrained()->onDelete('cascade');
            $table->longText('pertanyaan');
            $table->string('kunci_jawaban');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('modul_quizzes');
    }
}
