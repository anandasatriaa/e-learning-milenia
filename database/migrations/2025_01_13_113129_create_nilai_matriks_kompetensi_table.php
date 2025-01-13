<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiMatriksKompetensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_matriks_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreign('user_id')->references('ID')->on('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->float('nilai_quiz')->nullable();
            $table->float('nilai_essay')->nullable();
            $table->float('nilai_praktek')->nullable();
            $table->float('presentase_kompetensi')->nullable();
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
        Schema::dropIfExists('nilai_matriks_kompetensi');
    }
}
