<?php

namespace App\Http\Controllers\Admin\Kuesioner;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Questionnaire\Questionnaire;
use App\Models\Questionnaire\QuestionnaireQuestion;
use App\Models\Questionnaire\QuestionnaireResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

    public function data($id)
    {
        // 1) Ambil semua pertanyaan kuesioner dengan jumlah tiap nilai skala
        $questions = QuestionnaireQuestion::where('questionnaire_id', $id)
            ->orderBy('position')
            ->get()
            ->map(function ($q) {
                // hitung jumlah per scale_value
                $counts = DB::table('questionnaires_answers')
                    ->where('question_id', $q->id)
                    ->select('scale_value', DB::raw('count(*) as count'))
                    ->groupBy('scale_value')
                    ->pluck('count', 'scale_value')
                    ->toArray();

                // pastikan semua nilai skala tampil (jika ada yang nol)
                $answers = [];
                for ($v = $q->scale_min; $v <= $q->scale_max; $v++) {
                    $answers[$v] = $counts[$v] ?? 0;
                }

                return [
                    'question'   => $q->text,
                    'scale_min'  => $q->scale_min,
                    'scale_max'  => $q->scale_max,
                    'label_min'  => $q->label_min,
                    'label_max'  => $q->label_max,
                    'answers'    => $answers,
                ];
            });

        // 2) Ambil responden beserta jawaban mereka
        $respondents = QuestionnaireResponse::with(['user', 'answers.question'])
            ->where('questionnaire_id', $id)
            ->get()
            ->map(function ($resp) {
                $user = $resp->user;

                // --- Mulai logic foto ---
                $formattedFoto = str_pad($user->ID, 5, '0', STR_PAD_LEFT);
                $cacheBuster   = time();

                $clientIp = request()->ip();
                if (
                    $clientIp === '127.0.0.1' ||
                    \Illuminate\Support\Str::startsWith($clientIp, '192.168.0.')
                ) {
                    $baseUrl = 'http://192.168.0.8/hrd-milenia/foto/';
                } else {
                    $baseUrl = 'http://pc.dyndns-office.com:8001/hrd-milenia/foto/';
                }

                $photoUrl = $baseUrl . "{$formattedFoto}.JPG?v={$cacheBuster}";
                // --- Selesai logic foto ---

                return [
                    'name'      => $user->Nama,
                    'division'  => $user->Divisi ?: '-',
                    'photoUrl'  => $photoUrl,
                    'answers'   => $resp->answers->map(function ($a) {
                        return [
                            'id'         => $a->question->id,
                            'question'   => $a->question->text,
                            'answer'     => $a->scale_value,
                            'scale_min'  => $a->question->scale_min,
                            'scale_max'  => $a->question->scale_max,
                            'label_min'  => $a->question->label_min,
                            'label_max'  => $a->question->label_max,
                        ];
                    }),
                ];
            });

        // ambil $questions & $respondents seperti contoh sebelumnya
        return response()->json([
            'questions'   => $questions,
            'respondents' => $respondents,
        ]);
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
