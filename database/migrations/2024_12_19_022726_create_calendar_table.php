<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar', function (Blueprint $table) {
            $table->id();
            $table->string('acara');
            $table->integer('user_id')->nullable(); // Menambahkan kolom user_id yang nullable
            $table->string('nama')->nullable(); // Kolom nama yang nullable
            $table->string('divisi');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('bg_color', 7); // Warna background dalam format HEX
            $table->timestamps(); // created_at & updated_at

            // Menambahkan foreign key constraint (asumsi tabel users memiliki kolom id)
            $table->foreign('user_id')->references('ID')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar');
    }
}
