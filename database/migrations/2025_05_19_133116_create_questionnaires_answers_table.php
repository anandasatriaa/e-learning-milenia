<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnairesAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                ->constrained('questionnaires_questions')
                ->cascadeOnDelete();
            $table->foreignId('response_id')
                ->constrained('questionnaires_responses')
                ->cascadeOnDelete();
            $table->integer('scale_value')->nullable();
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
        Schema::dropIfExists('questionnaires_answers');
    }
}
