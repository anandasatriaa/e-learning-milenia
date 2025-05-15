<?php

namespace App\Http\Controllers\Admin\Nilai;

use App\Http\Controllers\Controller;
use App\Models\Nilai\Nilai;
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

class NilaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil data courses dengan jumlah modul dan peserta, kecuali Matriks Kompetensi
        $courses = Course::withCount(['modul', 'user']) // Menghitung jumlah modul dan peserta
        ->with('modul') // Memuat data modul untuk akses lebih lanjut
        ->whereDoesntHave('category', function ($query) {
            $query->where('nama', 'Matriks Kompetensi');
        })
            ->get();

        return view('admin.course.nilai.index', compact('courses'));
    }

    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            'nilai_quiz' => 'nullable|numeric|between:0,10',
            'nilai_essay' => 'nullable|numeric',
            'komentar' => 'nullable|string|max:255',
        ]);

        // Simpan data penilaian ke database
        $nilai = new Nilai();
        $nilai->user_id = $request->user_id;
        $nilai->course_id = $request->course_id;
        $nilai->nilai_quiz = $request->nilai_quiz;
        $nilai->nilai_essay = $request->nilai_essay;
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

        // Ambil data review (nilai quiz, nilai essay, komentar) dari tabel review
        $review = Nilai::where('course_id', $course_id)
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
            'komentar' => 'nullable|string|max:255',
        ]);

        // Cek apakah review sudah ada di database
        $review = Nilai::where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->first();

        if ($review) {
            // Update data review jika sudah ada
            $review->nilai_quiz = $validatedData['nilai_quiz'] ?? $review->nilai_quiz;
            $review->nilai_essay = $validatedData['nilai_essay'] ?? $review->nilai_essay;
            $review->komentar = $validatedData['komentar'] ?? $review->komentar;

            // Simpan perubahan
            $review->save();

            // Log perubahan
            Log::info('Review updated', ['course_id' => $course_id, 'user_id' => $user_id]);
        } else {
            // Jika review belum ada, buat baru
            $review = new Nilai();
            $review->course_id = $course_id;
            $review->user_id = $user_id;
            $review->nilai_quiz = $validatedData['nilai_quiz'];
            $review->nilai_essay = $validatedData['nilai_essay'];
            $review->komentar = $validatedData['komentar'];

            // Simpan data baru
            $review->save();

            // Log pembuatan data baru
            Log::info('New review created', ['course_id' => $course_id, 'user_id' => $user_id]);
        }

        // Return response sukses
        return response()->json(['message' => 'Review saved successfully']);
    }

    public function previewNilai()
    {
        // 1) Ambil semua user yang punya quiz/essay
        $users = User::select('ID', 'Nama', 'Divisi', 'email_karyawan')
            ->whereHas(
                'enrolls.course.moduls',
                fn($q) =>
                $q->whereHas('quizzes')->orWhereHas('essays')
            )
            ->get();

        foreach ($users as $user) {
            // 2) Fetch enrolls + data terkait
            $enrolls = UserCourseEnroll::with([
                'course:id,nama_kelas,thumbnail',
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

            // 3) Ambil semua nilai user, keyed by course_id
            $nilaiByCourse = Nilai::where('user_id', $user->ID)
                ->get()
                ->keyBy('course_id');

            // Assign nilai yang tepat ke setiap enroll (default 0 jika tidak ada)
            foreach ($enrolls as $enr) {
                $nilai = $nilaiByCourse->get($enr->course_id);
                if (!$nilai) {
                    $nilai = new Nilai();
                    $nilai->nilai_quiz = 0;
                    $nilai->nilai_essay = 0;
                }
                $enr->setRelation('nilai', $nilai);
            }

            $user->courses      = $enrolls;
            $user->total_course = $enrolls->count();

            // 4) Hitung quiz_score per modul
            foreach ($user->courses as $enr) {
                $enr->setAttribute(
                    'modules',
                    $enr->course->moduls->map(fn($modul) => tap((object) [
                        'nama_modul'   => $modul->nama_modul,
                        'quiz_details' => $modul->quizzes->map(fn($quiz) => [
                            'is_correct' => optional(
                                $quiz->userAnswers->first()
                            )->kode_jawaban === $quiz->kunci_jawaban,
                        ]),
                    ], function ($o) {
                        $o->quiz_score = collect($o->quiz_details)
                            ->where('is_correct', true)
                            ->count();
                        $o->total_soal = count($o->quiz_details);
                    }))
                );
            }
        }

        return view('admin.preview.nilai', compact('users'));
    }
}
