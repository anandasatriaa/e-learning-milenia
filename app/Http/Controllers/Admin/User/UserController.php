<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function datatableGetAllEmployee()
    {
        $data = User::select('ID', 'Nama', 'Jabatan', 'Cabang', 'Divisi', 'Golongan', 'statuskar')->where('lvl', '!=', 1)->get();
        return response()->json(['message' => 'sukses', 'data' => $data], 200);
    }

    public function APIgetAllEmployee()
    {
        $httpcode = 500;
        try {
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
            curl_close($curl);
            $data = json_decode($response);
            DB::beginTransaction();
            foreach ($data->data as $value) {
                $value->TglKeluar = ($value->TglKeluar === '0000-00-00') ? null : $value->TglKeluar;
                $value->TjAssEff = ($value->TjAssEff === '0000-00-00') ? null : $value->TjAssEff;
                $value->TglLulus = ($value->TglLulus === '0000-00-00') ? null : $value->TglLulus;
                $value->MasaBerlaku = ($value->MasaBerlaku === '0000-00-00') ? null : $value->MasaBerlaku;
                $value->MasaBerlaku2 = ($value->MasaBerlaku2 === '0000-00-00') ? null : $value->MasaBerlaku2;
                $value->tgl_keper_bpjs = ($value->tgl_keper_bpjs === '0000-00-00') ? null : $value->tgl_keper_bpjs;
                User::updateOrCreate(
                    ['ID' => $value->ID],
                    array_intersect_key((array) $value, array_flip((new User)->getFillable()))
                );
            }
            DB::commit();

            return response()->json(['message' => 'Berhasil sinkronisasi data'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), $httpcode);
        }
    }
}
