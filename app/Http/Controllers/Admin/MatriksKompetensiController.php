<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matriks\DashboardMatriks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MatriksKompetensiController extends Controller
{
    public function index()
    {
        $data = DB::table('divisi_categories as dc')
        ->join('categories as c', 'c.divisi_category_id', '=', 'dc.id')
        ->join('courses as cr', 'cr.divisi_category_id', '=', 'dc.id')
        ->join('user_course_enrolls as uce', 'uce.course_id', '=', 'cr.id')
        ->leftJoin('learning_cat as lc', 'lc.id', '=', 'dc.learning_cat_id')
        ->select(
            'dc.id',
            'dc.nama as divisi_name',
            'dc.image',
            'dc.deskripsi',
            'c.nama as category_name',
            'dc.nama as divisi_category_name',
            'lc.nama as learning_category_name',
            'cr.sub_category_id',
            DB::raw('COUNT(DISTINCT uce.user_id) as peserta_count')
        )
            ->where('dc.learning_cat_id', 2)
            ->where('c.nama', 'Matriks Kompetensi')
            ->groupBy(
                'dc.id',
                'dc.nama',
                'dc.image',
                'dc.deskripsi',
                'c.nama',
                'lc.nama',
                'cr.sub_category_id'
            )
            ->get();

        return view('admin.matriks.index', compact('data'));
    }

    public function detail($divisi_id)
    {
        $dashboard = DB::table('dashboard_matriks_kompetensi')
        ->where('divisi_category_id', $divisi_id)
        ->first(); // Ambil data pertama untuk divisi ini

        $divisiID = DB::table('divisi_categories')
        ->where('id', $divisi_id)
        ->first();

        $divisi = DB::table('divisi_categories as dc')
        ->join('categories as c', 'c.divisi_category_id', '=', 'dc.id')
        ->leftJoin('learning_cat as lc', 'lc.id', '=', 'dc.learning_cat_id')
        ->select(
            'dc.id',
            'dc.nama as divisi_name',
            'dc.image',
            'dc.deskripsi',
            'c.nama as category_name',
            'lc.nama as learning_category_name'
        )
            ->where('dc.id', $divisi_id)
            ->where('dc.learning_cat_id', 2)
            ->where('c.nama', 'Matriks Kompetensi')
            ->first();

        if (!$divisi) {
            return redirect()->route('matriks-kompetensi')->with('error', 'Divisi tidak ditemukan');
        }

        $users = DB::table('user_course_enrolls as uce')
        ->join('courses as cr', 'uce.course_id', '=', 'cr.id')
        ->join('categories as cat', 'cr.category_id', '=', 'cat.id')
        ->join('users as u', 'uce.user_id', '=', 'u.id')
        ->select(
            'uce.user_id',
            'u.nama as user_name',
            DB::raw('MIN(cr.nama_kelas) as nama_kelas'),
            'cat.nama as category_name'
        )
            ->where('cr.learning_cat_id', 2)
            ->where('cat.nama', 'Matriks Kompetensi')
            ->where('cr.divisi_category_id', $divisi_id)
            ->groupBy('uce.user_id', 'u.nama', 'cat.nama')
            ->get();

        $courses = DB::table('courses as cr')
        ->join('categories as cat', 'cr.category_id', '=', 'cat.id')
        ->select(
            'cr.id as course_id',
            'cr.nama_kelas as course_name',
            'cat.nama as category_name'
        )
            ->where('cr.learning_cat_id', 2)
            ->where('cat.nama', 'Matriks Kompetensi')
            ->where('cr.divisi_category_id', $divisi_id)
            ->orderBy('cr.nama_kelas', 'asc')
            ->get();

        foreach ($users as $user) {
            $user->courses = collect($courses)->map(function ($course) use ($user) {
                $courseCopy = clone $course;
                $presentaseKompetensi = DB::table('nilai_matriks_kompetensi')
                ->where('user_id', $user->user_id)
                    ->where('course_id', $course->course_id)
                    ->value('presentase_kompetensi');

                $courseCopy->presentase_kompetensi = $presentaseKompetensi !== null ? $presentaseKompetensi : '-';
                return $courseCopy;
            });
        }

        return view('admin.matriks.detail', compact('divisi', 'users', 'courses', 'dashboard', 'divisiID'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->_method == 'PUT' && $request->dashboard_id) {
                return $this->update($request, $request->dashboard_id);  // Panggil update jika ada dashboard_id dan _method PUT
            }

            $validatedData = $request->validate([
                'divisi_id' => 'required|integer|exists:divisi_categories,id',
                'namaDashboard' => 'required|string|max:100',
                'kodeDashboard' => 'nullable|string|max:50',
                'tanggalUpdate' => 'required|date',
                'ttd1' => 'nullable|image|max:2048',
                'ttd2' => 'nullable|image|max:2048',
                'ttd3' => 'nullable|image|max:2048',
                'nama1' => 'nullable|string|max:100',
                'nama2' => 'nullable|string|max:100',
                'nama3' => 'nullable|string|max:100',
            ]);

            // Handle file uploads
            $imagePaths = [];
            foreach (['ttd1', 'ttd2', 'ttd3'] as $key) {
                $imagePaths[$key] = $request->hasFile($key) ? $request->file($key)->store('dashboard/signatures', 'public') : null;
            }

            // Create new dashboard record
            $dashboard = DashboardMatriks::create([
                'divisi_category_id' => $validatedData['divisi_id'],
                'nama' => $validatedData['namaDashboard'],
                'kode_dashboard' => $validatedData['kodeDashboard'],
                'tgl_update' => $validatedData['tanggalUpdate'],
                'image_ttd_1' => $imagePaths['ttd1'],
                'image_ttd_2' => $imagePaths['ttd2'],
                'image_ttd_3' => $imagePaths['ttd3'],
                'nama_ttd_1' => $validatedData['nama1'],
                'nama_ttd_2' => $validatedData['nama2'],
                'nama_ttd_3' => $validatedData['nama3'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan!',
                'data' => $dashboard,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in storing data: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'divisi_id' => 'required|integer|exists:divisi_categories,id',
                'namaDashboard' => 'required|string|max:100',
                'kodeDashboard' => 'nullable|string|max:50',
                'tanggalUpdate' => 'required|date',
                'ttd1' => 'nullable|image|max:2048',
                'ttd2' => 'nullable|image|max:2048',
                'ttd3' => 'nullable|image|max:2048',
                'nama1' => 'nullable|string|max:100',
                'nama2' => 'nullable|string|max:100',
                'nama3' => 'nullable|string|max:100',
            ]);

            // Find existing dashboard record
            $dashboard = DashboardMatriks::findOrFail($id);

            // Handle file uploads (use existing image if not uploaded)
            $imagePaths = [];
            foreach (['ttd1', 'ttd2', 'ttd3'] as $key) {
                // Jika file baru ada, simpan gambar baru, jika tidak ada gambar baru, gunakan gambar lama
                $imagePaths[$key] = $request->hasFile($key) ?
                    $request->file($key)->store('dashboard/signatures', 'public') :
                    $dashboard->{'image_ttd_' . substr($key, -1)};  // Mengakses nama kolom gambar yang benar
            }

            // Update existing dashboard record
            $dashboard->update([
                'divisi_category_id' => $validatedData['divisi_id'],
                'nama' => $validatedData['namaDashboard'],
                'kode_dashboard' => $validatedData['kodeDashboard'],
                'tgl_update' => $validatedData['tanggalUpdate'],
                'image_ttd_1' => $imagePaths['ttd1'],
                'image_ttd_2' => $imagePaths['ttd2'],
                'image_ttd_3' => $imagePaths['ttd3'],
                'nama_ttd_1' => $validatedData['nama1'],
                'nama_ttd_2' => $validatedData['nama2'],
                'nama_ttd_3' => $validatedData['nama3'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diperbarui!',
                'data' => $dashboard,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'status' => 'error',
                'message' => 'Mohon isi data dengan benar.',
                'errors' => $e->errors(), // Display validation errors
            ], 422);
        }
    }
}