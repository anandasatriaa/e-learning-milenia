<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulEssayAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modul_essay_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('modul_essay_question_id');
            $table->unsignedInteger('user_id');
            $table->longText('jawaban');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('modul_essay_question_id')->references('id')->on('modul_essay_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul_essay_answers');
    }
}