<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function datatableGetAllEmployee()
    {
        $data = User::select('ID', 'Nama', 'Jabatan', 'Cabang', 'Divisi', 'Golongan', 'statuskar')->where('lvl', '!=', 1)->where('Aktif', '!=', 0)->get();
        return response()->json(['message' => 'sukses', 'data' => $data], 200);
    }

    public function APIgetAllEmployee()
    {
        $httpcode = 500;
        try {
            Log::info('Mulai melakukan sinkronisasi data karyawan.');

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://192.168.0.8/hrd-milenia/API/karyawan/getAllEmployee.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('API_KEY' => 'LxPNcX1EMScOV%zAVgTbY^ICbxUF8Pk@aZYTsmZcus57!uxgDGmxs!hjljN8'),
            ));

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode !== 200) {
                Log::error("Request API gagal, kode HTTP: {$httpcode}, Response: {$response}");
            }

            curl_close($curl);
            $data = json_decode($response);

            Log::info('Data karyawan berhasil diterima dari API.');

            DB::beginTransaction();
            foreach ($data->data as $value) {
                Log::info("Memproses data karyawan ID: {$value->ID}.");

                // Memastikan tanggal yang tidak valid menjadi null
                $value->TglKeluar = ($value->TglKeluar === '0000-00-00') ? null : $value->TglKeluar;
                $value->TjAssEff = ($value->TjAssEff === '0000-00-00') ? null : $value->TjAssEff;
                $value->TglLulus = ($value->TglLulus === '0000-00-00') ? null : $value->TglLulus;
                $value->TglLahir = ($value->TglLahir === '0000-00-00') ? null : $value->TglLahir;
                $value->MasaBerlaku = ($value->MasaBerlaku === '0000-00-00') ? null : $value->MasaBerlaku;
                $value->MasaBerlaku2 = ($value->MasaBerlaku2 === '0000-00-00') ? null : $value->MasaBerlaku2;
                $value->tgl_keper_bpjs = ($value->tgl_keper_bpjs === '0000-00-00') ? null : $value->tgl_keper_bpjs;
                $value->TglMasuk = ($value->TglMasuk === '0000-00-00') ? null : $value->TglMasuk;
                $value->TglUpdate = ($value->TglUpdate === '0000-00-00') ? null : $value->TglUpdate;
                $value->SaldoTjPengobatan = ($value->SaldoTjPengobatan === null) ? 0 : $value->SaldoTjPengobatan;

                // Cek status Aktif di table users dan trkaryawan
                $user = User::find($value->ID);
                if ($user) {
                    if ($user->Aktif != $value->Aktif) {
                        // Update status Aktif jika berbeda
                        $user->Aktif = $value->Aktif;
                        $user->save();
                        Log::info("Status aktif user ID: {$value->ID} telah diperbarui.");
                    }
                } else {
                    // Jika user tidak ditemukan, buat user baru
                    User::create([
                        'ID' => $value->ID,
                        'Nama' => $value->Nama,
                        'JK' => $value->JK,
                        'TmpLahir' => $value->TmpLahir,
                        'TglLahir' => $value->TglLahir,
                        'Agama' => $value->Agama,
                        'Pendidikan' => $value->Pendidikan,
                        'Alamat' => $value->Alamat,
                        'Alamat_dom' => $value->Alamat_dom,
                        'Kota' => $value->Kota,
                        'KodePos' => $value->KodePos,
                        'Telpon' => $value->Telpon,
                        'KTP' => $value->KTP,
                        'Status' => $value->Status,
                        'JA' => $value->JA,
                        'TglMasuk' => $value->TglMasuk,
                        'TglLulus' => $value->TglLulus,
                        'TglUpdate' => $value->TglUpdate,
                        'Aktif' => $value->Aktif,
                        'TglKeluar' => $value->TglKeluar,
                        'Jabatan' => $value->Jabatan,
                        'Divisi' => $value->Divisi,
                        'Cabang' => $value->Cabang,
                        'Golongan' => $value->Golongan,
                        'jeniskar' => $value->jeniskar,
                        'statuskar' => $value->statuskar,
                        'no_bpjs_tk' => $value->no_bpjs_tk,  // Pastikan bahwa variabel $value memiliki properti ini
                        'no_bpjs_kes' => $value->no_bpjs_kes, // Pastikan bahwa variabel $value memiliki properti ini
                        'tgl_keper_bpjs' => $value->tgl_keper_bpjs,
                        'statBpjs' => $value->statBpjs,
                        'Atasan' => $value->Atasan,
                        'JamKerja' => $value->JamKerja,
                        'total_telat' => $value->total_telat,
                        'NoSuratKerja' => $value->NoSuratKerja,
                        'NoSuratKerja2' => $value->NoSuratKerja2,
                        'MasaBerlaku' => $value->MasaBerlaku,
                        'MasaBerlaku2' => $value->MasaBerlaku2,
                        'TjMakan' => $value->TjMakan,
                        'stat_makan' => $value->stat_makan,
                        'NilaiTjMakan' => $value->NilaiTjMakan,
                        'TjBBM' => $value->TjBBM,
                        'NilaiTjBBM' => $value->NilaiTjBBM,
                        'stat_BBM' => $value->stat_BBM,
                        'TjAsuransi' => $value->TjAsuransi,
                        'TjAssEff' => $value->TjAssEff,
                        'TjAssPolis' => $value->TjAssPolis,
                        'TjPengobatan' => $value->TjPengobatan,
                        'TjKerajinan' => $value->TjKerajinan,
                        'TjLembur' => $value->TjLembur,
                        'SaldoTjPengobatan' => $value->SaldoTjPengobatan,
                        'TjUmObMinggu' => $value->TjUmObMinggu,
                        'IDMesin' => $value->IDMesin,
                        'StatusNoPrick' => $value->StatusNoPrick,
                        'Pajak' => $value->Pajak,
                        'Npwp' => $value->Npwp,
                        'hak_cuti' => $value->hak_cuti,
                        'jml_cuti' => $value->jml_cuti,
                        'jml_off' => $value->jml_off,
                        'nokk' => $value->nokk,
                        'email_karyawan' => $value->email_karyawan,
                        'email_atasan' => $value->email_atasan,
                        'uname' => $value->uname,
                        'pwd' => $value->pwd,
                        'lvl' => $value->lvl,
                        'abs' => $value->abs,
                    ]);
                    Log::info("User baru ID: {$value->ID} telah dibuat.");
                }

                // Sinkronkan data lainnya
                User::updateOrCreate(
                    ['ID' => $value->ID],
                    array_intersect_key((array) $value, array_flip((new User)->getFillable()))
                );
            }
            DB::commit();

            Log::info('Sinkronisasi data karyawan selesai.');

            return response()->json(['message' => 'Berhasil sinkronisasi data'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Terjadi kesalahan dalam sinkronisasi data: ' . $th->getMessage());
            return response()->json($th->getMessage(), $httpcode);
        }
    }



}
