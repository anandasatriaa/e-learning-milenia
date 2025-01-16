<?php

namespace App\Http\Controllers\Admin\Nilai;

use App\Http\Controllers\Controller;
use App\Models\Nilai\Nilai;
use App\Models\Nilai\NilaiMatriks;
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

class NilaiMatriksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil data courses yang memenuhi kriteria
        $courses = Course::withCount(['modul', 'user']) // Menghitung jumlah modul dan peserta
        ->with('modul') // Memuat data modul untuk akses lebih lanjut
        ->whereHas('category', function ($query) {
            $query->where('nama', 'Matriks Kompetensi');
        })
            ->where('learning_cat_id', 2)
            ->get();

        return view('admin.course.nilai-matriks.index', compact('courses'));
    }

    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            'nilai_quiz' => 'nullable|numeric|between:0,4',
            'nilai_essay' => 'nullable|numeric|between:0,2',
            'nilai_praktek' => 'nullable|numeric|between:0,8',
            'presentase_kompetensi' => 'nullable|numeric|between:0,100',
            'komentar' => 'nullable|string|max:255',
        ]);

        // Simpan data penilaian ke database
        $nilai = new NilaiMatriks();
        $nilai->user_id = $request->user_id;
        $nilai->course_id = $request->course_id;
        $nilai->nilai_quiz = $request->nilai_quiz;
        $nilai->nilai_essay = $request->nilai_essay;
        $nilai->nilai_praktek = $request->nilai_praktek;
        $nilai->presentase_kompetensi = $request->presentase_kompetensi;
        $nilai->komentar = $request->komentar;
        $nilai->save();

        // Response sukses
        return response()->json([
            'message' => 'Nilai berhasil disimpan',
            'status' => 'success'
        ]);
    }

    public function detail($course_id)
    {
        // Lakukan query untuk mendapatkan data kursus berdasarkan ID
        $course = Course::with(['user:id,ID,Nama', 'modul', 'user.nilaiMatriks' => function ($query) use ($course_id) {
            $query->where('course_id', $course_id);  // Menyaring nilai berdasarkan course_id
        }])
            ->withCount(['modul', 'user'])  // Menambahkan count untuk modul dan users
            ->findOrFail($course_id);

        // Kirim data kursus ke view
        return view('admin.course.nilai-matriks.detail', compact('course', 'course_id'));
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

        // Ambil data review (nilai quiz, nilai essay, komentar) dari tabel review
        $review = NilaiMatriks::where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->first();

        // Tambahkan quiz dan essay data pengguna
        foreach ($moduls as $modul) {
            // Ambil jawaban quiz untuk pengguna
            foreach ($modul->modulQuiz as $quiz) {
                // Ambil jawaban pengguna berdasarkan quiz ID dan user ID
                $quiz->userAnswer = ModulQuizUserAnswer::where('modul_quizzes_id', $quiz->id)
                    ->where('user_id', $user_id)
                    ->first(); // Jawaban pengguna pada quiz ini

                // Log kode jawaban yang didapatkan
                Log::info('Jawaban pengguna untuk quiz', [
                    'quiz_id' => $quiz->id,
                    'user_id' => $user_id,
                    'jawaban_pengguna' => $quiz->userAnswer ? $quiz->userAnswer->jawaban : 'Tidak ada jawaban',
                    'kode_jawaban_pengguna' => $quiz->userAnswer ? $quiz->userAnswer->kode_jawaban : 'Tidak ada kode jawaban',
                ]);

                // Ambil opsi pilihan jawaban untuk quiz
                $quiz->options = ModulQuizAnswer::where('modul_quiz_id', $quiz->id)->get();

                // Perbandingan kode jawaban pengguna dengan kunci jawaban
                if ($quiz->userAnswer) {
                    $quiz->is_correct = ($quiz->userAnswer->kode_jawaban === $quiz->kunci_jawaban);
                    $quiz->correct_answer = $quiz->kunci_jawaban;

                    // Log perbandingan kode jawaban
                    Log::info('Perbandingan kode jawaban pengguna dengan kunci jawaban', [
                        'quiz_id' => $quiz->id,
                        'kode_jawaban_pengguna' => $quiz->userAnswer->kode_jawaban,
                        'kunci_jawaban' => $quiz->kunci_jawaban,
                        'is_correct' => $quiz->is_correct,
                    ]);
                } else {
                    $quiz->is_correct = false;
                    $quiz->correct_answer = null;

                    // Log jika tidak ada jawaban pengguna
                    Log::info('Tidak ada jawaban pengguna untuk quiz', [
                        'quiz_id' => $quiz->id,
                        'user_id' => $user_id,
                    ]);
                }
            }

            // Ambil jawaban essay untuk pengguna
            foreach ($modul->modulEssay as $essay) {
                $essay->userAnswer = ModulEssayAnswer::where('course_modul_id', $modul->id)
                    ->where('user_id', $user_id)
                    ->first(); // Jawaban pengguna pada essay ini

                // Log kode jawaban essay
                Log::info('Jawaban pengguna untuk essay', [
                    'essay_id' => $essay->id,
                    'user_id' => $user_id,
                    'jawaban_pengguna' => $essay->userAnswer ? $essay->userAnswer->jawaban : 'Tidak ada jawaban',
                ]);
            }
        }

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
            'review' => $review ? [
                'nilai_quiz' => $review->nilai_quiz,
                'nilai_essay' => $review->nilai_essay,
                'nilai_praktek' => $review->nilai_praktek,
                'presentase_kompetensi' => $review->presentase_kompetensi,
                'komentar' => $review->komentar,
            ] : null,
        ]);
    }

    public function updateReview(Request $request, $course_id, $user_id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nilai_quiz' => 'nullable|numeric',
            'nilai_essay' => 'nullable|numeric',
            'nilai_praktek' => 'nullable|numeric',
            'presentase_kompetensi' => 'nullable|numeric',
            'komentar' => 'nullable|string|max:255',
        ]);

        // Cek apakah review sudah ada di database
        $review = NilaiMatriks::where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->first();

        if ($review) {
            // Update data review jika sudah ada
            $review->nilai_quiz = $validatedData['nilai_quiz'] ?? $review->nilai_quiz;
            $review->nilai_essay = $validatedData['nilai_essay'] ?? $review->nilai_essay;
            $review->nilai_praktek = $validatedData['nilai_praktek'] ?? $review->nilai_praktek;
            $review->presentase_kompetensi = $validatedData['presentase_kompetensi'] ?? $review->presentase_kompetensi;
            $review->komentar = $validatedData['komentar'] ?? $review->komentar;

            // Simpan perubahan
            $review->save();

            // Log perubahan
            Log::info('Review updated', ['course_id' => $course_id, 'user_id' => $user_id]);
        } else {
            // Jika review belum ada, buat baru
            $review = new NilaiMatriks();
            $review->course_id = $course_id;
            $review->user_id = $user_id;
            $review->nilai_quiz = $validatedData['nilai_quiz'];
            $review->nilai_essay = $validatedData['nilai_essay'];
            $review->nilai_praktek = $validatedData['nilai_praktek'];
            $review->presentase_kompetensi = $validatedData['presentase_kompetensi'];
            $review->komentar = $validatedData['komentar'];

            // Simpan data baru
            $review->save();

            // Log pembuatan data baru
            Log::info('New review created', ['course_id' => $course_id, 'user_id' => $user_id]);
        }

        // Return response sukses
        return response()->json(['message' => 'Review saved successfully']);
    }
}
