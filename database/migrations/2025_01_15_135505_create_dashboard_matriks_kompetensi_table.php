<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardMatriksKompetensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_matriks_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_category_id')->references('id')->on('divisi_categories')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('kode_dashboard', 50)->nullable();
            $table->date('tgl_update');
            $table->string('image_ttd_1', 255)->nullable();
            $table->string('image_ttd_2', 255)->nullable();
            $table->string('image_ttd_3', 255)->nullable();
            $table->string('nama_ttd_1', 100)->nullable();
            $table->string('nama_ttd_2', 100)->nullable();
            $table->string('nama_ttd_3', 100)->nullable();
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
        Schema::dropIfExists('dashboard_matriks_kompetensi');
    }
}
