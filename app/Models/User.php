<?php

namespace App\Models;

use App\Models\Course\Course;
use App\Models\Nilai\Nilai;
use App\Models\Calendar\Calendar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    protected $primaryKey = 'ID';

    protected $fillable = [
        'ID',
        'Nama',
        'JK',
        'TmpLahir',
        'TglLahir',
        'Agama',
        'Pendidikan',
        'Alamat',
        'Alamat_dom',
        'Kota',
        'KodePos',
        'Telpon',
        'KTP',
        'Status',
        'JA',
        'TglMasuk',
        'TglLulus',
        'TglUpdate',
        'Aktif',
        'TglKeluar',
        'Jabatan',
        'Divisi',
        'Cabang',
        'Golongan',
        'jeniskar',
        'statuskar',
        'no_bpjs_tk',
        'no_bpjs_kes',
        'tgl_keper_bpjs',
        'statBpjs',
        'Atasan',
        'JamKerja',
        'total_telat',
        'NoSuratKerja',
        'NoSuratKerja2',
        'MasaBerlaku',
        'MasaBerlaku2',
        'TjMakan',
        'stat_makan',
        'NilaiTjMakan',
        'TjBBM',
        'NilaiTjBBM',
        'stat_BBM',
        'TjAsuransi',
        'TjAssEff',
        'TjAssPolis',
        'TjPengobatan',
        'TjKerajinan',
        'TjLembur',
        'SaldoTjPengobatan',
        'TjUmObMinggu',
        'IDMesin',
        'StatusNoPrick',
        'Pajak',
        'Npwp',
        'hak_cuti',
        'jml_cuti',
        'jml_off',
        'nokk',
        'email_karyawan',
        'email_atasan',
        'uname',
        'pwd',
        'lvl',
        'abs',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pwd',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function course()
    {
        return $this->belongsToMany(Course::class, 'user_course_enrolls', 'user_id', 'course_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function calendar()
    {
        return $this->belongsToMany(Calendar::class,'user_id');
    }
}
