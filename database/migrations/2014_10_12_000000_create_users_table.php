<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('ID')->primary();
            $table->string('Nama', 100);
            $table->string('JK', 1)->nullable();
            $table->string('TmpLahir', 100)->nullable();
            $table->date('TglLahir')->nullable();
            $table->string('Agama', 50)->nullable();
            $table->string('Pendidikan', 100)->nullable();
            $table->string('Alamat', 250)->nullable();
            $table->string('Alamat_dom', 250);
            $table->string('Kota', 100)->nullable();
            $table->string('KodePos', 10)->nullable();
            $table->string('Telpon', 15)->nullable();
            $table->string('KTP', 45)->nullable();
            $table->string('Status', 30)->nullable();
            $table->integer('JA')->default(0);
            $table->date('TglMasuk')->nullable();
            $table->date('TglLulus')->nullable();
            $table->timestamp('TglUpdate')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrent()->onUpdate(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('Aktif');
            $table->date('TglKeluar')->nullable();
            $table->string('Jabatan', 20)->nullable();
            $table->string('Divisi', 50)->nullable();
            $table->string('Cabang', 30)->nullable();
            $table->string('Golongan', 20)->nullable();
            $table->string('jeniskar', 50)->nullable();
            $table->string('statuskar', 10)->nullable();
            $table->string('no_bpjs_tk', 50)->nullable();
            $table->string('no_bpjs_kes', 50)->nullable();
            $table->date('tgl_keper_bpjs')->nullable();
            $table->integer('statBpjs')->nullable();
            $table->integer('Atasan')->nullable();
            $table->string('JamKerja', 3)->nullable();
            $table->time('total_telat')->nullable();
            $table->string('NoSuratKerja', 50)->nullable();
            $table->string('NoSuratKerja2', 50)->nullable();
            $table->date('MasaBerlaku')->nullable();
            $table->date('MasaBerlaku2')->nullable();
            $table->string('TjMakan', 2)->nullable();
            $table->integer('stat_makan')->nullable();
            $table->integer('NilaiTjMakan')->default(0);
            $table->string('TjBBM', 2)->nullable();
            $table->integer('NilaiTjBBM')->default(0);
            $table->string('stat_BBM', 30)->nullable();
            $table->string('TjAsuransi', 2)->nullable();
            $table->date('TjAssEff')->nullable();
            $table->string('TjAssPolis', 30)->nullable();
            $table->integer('TjPengobatan')->default(0);
            $table->integer('TjKerajinan')->default(0);
            $table->integer('TjLembur')->default(0);
            $table->integer('SaldoTjPengobatan')->default(0);
            $table->integer('TjUmObMinggu')->default(0);
            $table->integer('IDMesin')->nullable();
            $table->integer('StatusNoPrick')->default(0);
            $table->integer('Pajak')->default(0);
            $table->string('Npwp', 40)->nullable();
            $table->tinyInteger('hak_cuti')->nullable();
            $table->integer('jml_cuti')->nullable();
            $table->integer('jml_off')->nullable();
            $table->string('nokk', 45)->nullable();
            $table->string('email_karyawan', 100)->nullable();
            $table->string('email_atasan', 100)->nullable();
            $table->string('uname', 255);
            $table->string('pwd', 255);
            $table->integer('lvl')->comment('ID hak akses');
            $table->integer('abs')->comment('ID Jam kerja');

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
        Schema::dropIfExists('users');
    }
}
