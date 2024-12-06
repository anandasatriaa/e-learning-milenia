<?php

namespace App\Http\Controllers\Admin\Nilai;

use App\Http\Controllers\Controller;
use App\Models\Nilai\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        // Ambil semua peserta yang sudah selesai mengikuti kuis
        $nilai = Nilai::all();
        return view('admin.course.nilai.index', compact('nilai'));
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
