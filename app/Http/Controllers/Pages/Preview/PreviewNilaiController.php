<?php

namespace App\Http\Controllers\Pages\Preview;

use App\Http\Controllers\Controller;
use App\Models\Nilai\Nilai;
use App\Models\Nilai\NilaiMatriks;
use App\Models\Course\Course;
use App\Models\User;
use App\Models\UserCourseEnroll;
use App\Models\Course\CourseModul;
use App\Models\Course\ModulQuiz;
use App\Models\Course\ModulQuizAnswer;
use App\Models\Course\ModulQuizUserAnswer;
use App\Models\Course\ModulEssay;
use App\Models\Course\ModulEssayAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\NilaiPesertaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class PreviewNilaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil hanya enrolls untuk user ini yang memiliki quiz/essay
        $enrolls = UserCourseEnroll::with([
            'course:id,nama_kelas,thumbnail,category_id',
            'course.category:id,nama',
            'course.moduls' => fn($q) => $q->withCount(['quizzes', 'essays']),
            'course.moduls.quizzes.userAnswers' => fn($q) => $q->where('user_id', $user->ID),
            'course.moduls.essays',
        ])
            ->where('user_id', $user->ID)
            ->whereHas(
                'course.moduls',
                fn($q) =>
                $q->whereHas('quizzes')->orWhereHas('essays')
            )
            ->get();

        // Ambil nilai standar & matriks
        $nilaiStandard = Nilai::where('user_id', $user->ID)->get()->keyBy('course_id');
        $nilaiMatriks  = NilaiMatriks::where('user_id', $user->ID)->get()->keyBy('course_id');

        foreach ($enrolls as $enr) {
            $cat = optional($enr->course->category)->nama;
            if ($cat === 'Matriks Kompetensi') {
                $nilai = $nilaiMatriks->get($enr->course_id) ?: new NilaiMatriks();
            } else {
                $nilai = $nilaiStandard->get($enr->course_id) ?: new Nilai();
            }
            // Pastikan semua field ada
            $nilai->nilai_quiz            = $nilai->nilai_quiz ?? 0;
            $nilai->nilai_essay           = $nilai->nilai_essay ?? 0;
            $nilai->nilai_praktek         = $nilai->nilai_praktek ?? 0;
            $nilai->presentase_kompetensi = $nilai->presentase_kompetensi ?? 0;

            $enr->setRelation('nilai', $nilai);

            // Hitung dan set modules untuk setiap enroll:
            $modules = $enr->course->moduls->map(function ($modul) use ($enr) {
                // Jumlah soal quiz di modul ini
                $total = $modul->quizzes_count;

                // Jumlah jawaban benar user di quiz‐modul ini
                $correct = $modul->quizzes->filter(function ($quiz) use ($enr) {
                    $answer = $quiz->userAnswers->first();
                    return optional($answer)->kode_jawaban === $quiz->kunci_jawaban;
                })->count();

                return (object)[
                    'nama_modul' => $modul->nama_modul,
                    'quiz_score' => $correct,
                    'total_soal' => $total,
                ];
            });

            $enr->setAttribute('modules', $modules);
        }

        return view('pages.preview.nilai', compact('enrolls'));
    }
}
