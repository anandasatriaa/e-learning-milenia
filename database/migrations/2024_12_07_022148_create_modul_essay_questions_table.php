<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulEssayQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modul_essay_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_modul_id');
            $table->longText('pertanyaan');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('course_modul_id')->references('id')->on('course_moduls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul_essay_questions');
    }
}
