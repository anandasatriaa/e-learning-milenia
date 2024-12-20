<?php

namespace App\Http\Controllers\Admin\Nilai;

use App\Http\Controllers\Controller;
use App\Models\Nilai\Nilai;
use App\Models\Course\Course;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        // Ambil data courses dengan jumlah modul dan peserta
        $courses = Course::withCount(['modul', 'user']) // Menghitung jumlah modul dan peserta
            ->with('modul') // Memuat data modul untuk akses lebih lanjut
            ->get();

        return view('admin.course.nilai.index', compact('courses'));
    }

    public function store(Request $request)
    {
        // Validasi dan simpan penilaian
        $request->validate([
            'skor' => 'required|integer|min:0|max:100',
            'komentar' => 'nullable|string',
        ]);

        $peserta = Nilai::find($request->id);
        $peserta->update([
            'skor' => $request->skor,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan!');
    }

    public function detail()
    {
        return view('admin.course.nilai.detail');
    }
}
