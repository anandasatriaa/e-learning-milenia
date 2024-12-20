<?php

namespace App\Http\Controllers\Admin\Nilai;

use App\Http\Controllers\Controller;
use App\Models\Nilai\Nilai;
use App\Models\Course\Course;
use App\Models\User;
use App\Models\Course\CourseModul;
use App\Models\Course\ModulQuiz;
use App\Models\Course\ModulQuizAnswer;
use App\Models\Course\ModulQuizUserAnswer;
use App\Models\Course\ModulEssay;
use App\Models\Course\ModulEssayAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function detail($course_id)
    {
        // Lakukan query untuk mendapatkan data kursus berdasarkan ID
        $course = Course::with(['user:id,ID,Nama', 'modul', 'user.nilai' => function ($query) use ($course_id) {
            $query->where('course_id', $course_id);  // Menyaring nilai berdasarkan course_id
        }])
            ->withCount(['modul', 'user'])  // Menambahkan count untuk modul dan users
            ->findOrFail($course_id);

        // Kirim data kursus ke view
        return view('admin.course.nilai.detail', compact('course'));
    }

    public function showReviewModal($course_id, $user_id)
    {
        // Ambil data kursus dan pengguna
        $course = Course::find($course_id);
        $user = User::find($user_id);

        if (!$course) {
            Log::error('Course not found.', ['course_id' => $course_id]);
            return response()->json(['error' => 'Course not found.'], 404);
        }

        if (!$user) {
            Log::error('User not found.', ['user_id' => $user_id]);
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Ambil data modul
        $moduls = CourseModul::with(['modulQuiz', 'modulEssay'])->where('course_id', $course_id)->get();

        // Tambahkan quiz dan essay data pengguna
        foreach ($moduls as $modul) {
            // Ambil jawaban quiz untuk pengguna
            foreach ($modul->modulQuiz as $quiz) {
                $quiz->userAnswer = ModulQuizUserAnswer::where('modul_quizzes_id', $quiz->id)
                ->where('user_id', $user_id)
                    ->first(); // Jawaban pengguna pada quiz ini

                // Ambil opsi pilihan jawaban untuk quiz
                $quiz->options = ModulQuizAnswer::where('modul_quiz_id', $quiz->id)->get();
            }

            // Ambil jawaban essay untuk pengguna
            foreach ($modul->modulEssay as $essay) {
                $essay->userAnswer = ModulEssayAnswer::where('course_modul_id', $modul->id)
                ->where('user_id', $user_id)
                    ->first(); // Jawaban pengguna pada essay ini
            }
        }

        Log::info('Moduls data:', ['moduls' => $moduls]);
        Log::info('Modul Quiz Data:', ['quiz_data' => $moduls->pluck('modulQuiz')]);
        Log::info('Modul Essay Data:', ['essay_data' => $moduls->pluck('modulEssay')]);



        // Return data dalam format JSON untuk frontend
        return response()->json([
            'course' => [
                'name' => $course->nama_kelas,
                'moduls' => $moduls,
            ],
            'user' => [
                'id' => $user->ID,
                'name' => $user->Nama,
            ],
        ]);

        

    }
}
