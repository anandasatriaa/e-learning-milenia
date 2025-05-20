<?php

namespace App\Http\Controllers\Admin\Kuesioner;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Questionnaire\Questionnaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminKuesionerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Eager load relasi yang membentuk hierarki
        $courses = Course::with([
            'category',          // relasi ke kategori utama
            'divisiCategory',    // relasi ke divisi
            'learningCategory',       // relasi ke learning category
            'subCategory'        // relasi ke sub kategori
        ])
            ->orderBy('nama_kelas')
            ->get()
            ->map(function ($course) {
                // Ambil nama tiap level kalau ada
                $parts = [];
                if ($course->learningCategory)     $parts[] = $course->learningCategory->nama;
                if ($course->divisiCategory)  $parts[] = $course->divisiCategory->nama;
                if ($course->category)        $parts[] = $course->category->nama;
                if ($course->subCategory)     $parts[] = $course->subCategory->nama;

                // Gabungkan dengan ' > '
                $course->path = implode(' > ', $parts);

                return $course;
            });

        // Ambil semua kuesioner dengan relasi courses jika perlu
        $questionnaires = Questionnaire::with('courses')->orderBy('created_at', 'desc')->get();

        return view('admin.kuesioner.index', compact('courses', 'questionnaires'));
    }

    public function store(Request $request)
    {
        // 1) Validasi
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'course_ids'  => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'questions'   => 'required|array|min:1',
            'questions.*.text'      => 'required|string',
            'questions.*.scale_min' => 'required|integer|min:0',
            'questions.*.scale_max' => 'required|integer|gt:questions.*.scale_min',
            'questions.*.label_min' => 'required|string',
            'questions.*.label_max' => 'required|string',
        ]);

        // 2) Buat Questionnaire
        $q = Questionnaire::create([
            'title' => $data['title'],
            'image' => null,   // sesuaikan jika ada upload image
        ]);

        // 3) Simpan Pertanyaan
        foreach ($data['questions'] as $idx => $qitem) {
            $q->questions()->create([
                'type'      => 'linear_scale',
                'text'      => $qitem['text'],
                'scale_min' => $qitem['scale_min'],
                'scale_max' => $qitem['scale_max'],
                'label_min' => $qitem['label_min'],
                'label_max' => $qitem['label_max'],
                'position'  => $idx + 1,
            ]);
        }

        // 4) Attach ke Course (pivot)
        $q->courses()->sync($data['course_ids']);

        // 5) Redirect balik dengan pesan sukses
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('admin.kuesioner.feedback-kuesioner')
            ->with('success', 'Kuesioner berhasil disimpan.');
    }

    public function edit(Questionnaire $questionnaire)
    {
        $questionnaire->load('courses', 'questions');
        return response()->json($questionnaire);
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        // Validasi sama seperti store…
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'course_ids'  => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'questions'   => 'required|array|min:1',
            'questions.*.text'      => 'required|string',
            'questions.*.scale_min' => 'required|integer|min:0',
            'questions.*.scale_max' => 'required|integer|gt:questions.*.scale_min',
            'questions.*.label_min' => 'required|string',
            'questions.*.label_max' => 'required|string',
        ]);

        // Update title & image jika ada
        $questionnaire->update(['title' => $request->title, 'image' => null]);

        // Sync pertanyaan: misalnya delete old & recreate, atau update individually
        $questionnaire->questions()->delete();
        foreach ($request->questions as $idx => $qitem) {
            $questionnaire->questions()->create([
                'type' => 'linear_scale',
                'text' => $qitem['text'],
                'scale_min' => $qitem['scale_min'],
                'scale_max' => $qitem['scale_max'],
                'label_min' => $qitem['label_min'],
                'label_max' => $qitem['label_max'],
                'position' => $idx + 1,
            ]);
        }

        // Sync courses pivot
        $questionnaire->courses()->sync($request->course_ids);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, Questionnaire $questionnaire)
    {
        $questionnaire->questions()->delete();
        $questionnaire->courses()->detach();
        $questionnaire->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('admin.kuesioner.feedback-kuesioner')
            ->with('success', 'Kuesioner berhasil dihapus.');
    }
}
