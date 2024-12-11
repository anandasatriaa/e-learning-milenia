<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulQuizUserAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modul_quiz_user_answers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('course_modul_id'); // Foreign key dari course_moduls
            $table->integer('user_id'); // Menggunakan integer untuk foreign key
            $table->unsignedBigInteger('modul_quizzes_id'); // Foreign key dari modul_quizzes
            $table->longText('jawaban'); // Jawaban dari quiz
            $table->string('kode_jawaban'); // Kode jawaban
            $table->timestamps(); // created_at dan updated_at

            // Define foreign keys
            $table->foreign('course_modul_id')->references('id')->on('course_moduls')->onDelete('cascade');
            $table->foreign('user_id')->references('ID')->on('users')->onDelete('cascade');
            $table->foreign('modul_quizzes_id')->references('id')->on('modul_quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul_quiz_user_answers');
    }
}
