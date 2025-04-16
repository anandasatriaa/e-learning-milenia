<?php

namespace App\Http\Controllers\Pages\Course;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Models\Course\ModulQuiz;
use App\Models\Course\ModulEssay;
use App\Models\Course\ModulQuizUserAnswer;
use App\Models\Course\ModulEssayAnswer;
use App\Models\Course\CourseModul;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\UserCourseEnroll;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function detailcourse(Request $request, $course_id)
    {
        $course = Course::with(['modul.quizzes', 'modul.essays'])->find($course_id);

        // Siapkan data untuk JavaScript: mapping modul yang terdapat di course
        $courseModules = $course->modul->map(function ($modul) {
            return [
                'namaModul' => $modul->nama_modul,
                'quizIds' => $modul->quizzes->pluck('id')->toArray(),
                'essayIds' => $modul->essays->pluck('course_modul_id')->toArray(),
            ];
        });

        // Ambil data enrollment dari user yang sedang login jika ada
        $courseStatus = null;
        if (auth()->check()) {
            $enrollment = \App\Models\UserCourseEnroll::where('course_id', $course_id)
                ->where('user_id', auth()->user()->ID)
                ->first();
            if ($enrollment) {
                $courseStatus = $enrollment->status; // contoh nilai: 'completed', 'in_progress', dsb.
            }
        }

        // Kirim data course, courseModules, dan courseStatus ke view
        return view('pages.course.course.index', [
            'course' => $course,
            'courseModules' => $courseModules, // Data untuk digunakan di JS
            'courseStatus' => $courseStatus,
        ]);
    }

    public function lesson(Request $request, $course_modul_id)
    {
        $modul = CourseModul::with('course')->where('id', $course_modul_id)->first();
        return view('pages.course.lessons.index', compact('modul'));
    }

    public function embedVideo($course_id)
    {
        $course = Course::findOrFail($course_id);

        // Construct the full path to the video file
        $videoPath = storage_path('app/public/course/thumbnail_video/' . $course->thumbnail_video);

        // Check if the file exists
        if (!file_exists($videoPath)) {
            abort(404); // File not found
        }

        // Get the file size
        $filesize = filesize($videoPath);

        // Set the headers to serve the video
        header('Content-Type: video/mp4');
        header('Content-Length: ' . $filesize);
        header('Accept-Ranges: bytes');

        // Read the file and output it to the response
        readfile($videoPath);
        exit; // Ensure no further output is sent
    }

    public function getFirstModul($course_id)
    {
        $modules = Course::with(['modul.quizzes', 'modul.essays'])
        ->find($course_id)
        ->modul
        ->map(function ($modul) {
            return [
                'namaModul' => $modul->nama_modul,
                'quizIds' => $modul->quizzes->pluck('id')->toArray(),
                'essayIds' => $modul->essays->pluck('course_modul_id')->toArray(),
            ];
        });

        return response()->json([
            'success' => true,
            'modules' => $modules,
        ]);
    }

    public function getQuiz($quiz_id)
    {
        // Ambil pengguna yang sedang login
        $userId = auth()->id(); // Pastikan middleware auth diaktifkan

        // Fetch quiz question and its answers from the database
        $quiz = ModulQuiz::with(['answers', 'userAnswers' => function ($query) use ($userId) {
            $query->where('user_id', $userId); // Filter jawaban berdasarkan user_id
        }])->find($quiz_id);

        if ($quiz) {
            // Pastikan course_modul_id benar-benar berasal dari tabel course_moduls
            $courseModulId = $quiz->course_modul_id;

            if (!$courseModulId) {
                return response()->json(['message' => 'Course module ID not found'], 404);
            }

            // Fetch quizzes only for the same course_modul_id
            $quizzesInModule = ModulQuiz::where('course_modul_id', $courseModulId)->pluck('id')->toArray();
            $quizIndex = array_search($quiz_id, $quizzesInModule) + 1;

            // Fetch user answers if they exist
            $userAnswer = $quiz->userAnswers->first() ? $quiz->userAnswers->first()->jawaban : null;

            return response()->json([
                'question' => $quiz->pertanyaan,
                'questionImage' => $quiz->image ? asset($quiz->image) : null,
                'kunci_jawaban' => $quiz->answers->pluck('pilihan')->toArray(),
                'userAnswer' => $userAnswer, // User answer jika tersedia
                'totalQuizzes' => count($quizzesInModule),
                'quizIds' => $quizzesInModule,
                'quizIndex' => $quizIndex,
                'course_modul_id' => $courseModulId,
            ]);
        }

        return response()->json(['message' => 'Quiz not found'], 404);
    }



    public function quiz($course_modul_id)
    {
        // Fetch quiz question and its answers from the database
        $quiz = ModulQuiz::with('answers')->where('course_modul_id', $course_modul_id)->first();
        Log::info('Quiz blablabla: ' . print_r($quiz, true));
        $testquiz = $quiz->toArray();
        Log::info('Variabel testquiz: ' . print_r($testquiz, true));

        if ($quiz) {
            // Pastikan `course_modul_id` benar-benar berasal dari tabel `course_moduls`
            $courseModulId = $quiz->course_modul_id;

            if (!$courseModulId) {
                return response()->json(['message' => 'Course module ID not found'], 404);
            }

            // Fetch quizzes only for the same `course_modul_id`
            $quizzesInModule = ModulQuiz::where('course_modul_id', $courseModulId)->pluck('id')->toArray();
            $quizIndex = array_search($course_modul_id, $quizzesInModule) + 1;

            return response()->json([
                'question' => $quiz->pertanyaan,
                'questionImage' => $quiz->image ? asset($quiz->image) : null,
                'kunci_jawaban' => $quiz->answers->pluck('pilihan')->toArray(),
                'totalQuizzes' => count($quizzesInModule), // Count quizzes in the module
                'quizIds' => $quizzesInModule, // Send only IDs from the same module
                'quizIndex' => $quizIndex,
                // 'course_modul_id' => $courseModulId, // Include the module ID
            ]);
        }

        return response()->json(['message' => 'Quiz not found'], 404);
    }


    public function essay($course_modul_id)
    {
        $userId = auth()->id(); // Ambil user ID dari session

        // Validasi modul ID
        if (!$course_modul_id) {
            return response()->json(['message' => 'Invalid course module ID'], 400);
        }

        // Ambil semua essay terkait modul
        $essays = ModulEssay::where('course_modul_id', $course_modul_id)->get();

        // Ambil jawaban user terkait modul ini
        $answers = ModulEssayAnswer::where('course_modul_id', $course_modul_id)
            ->where('user_id', $userId)
            ->first();

        // Jika tidak ada essay ditemukan
        if ($essays->isEmpty()) {
            return response()->json(['message' => 'No essays found for this module'], 404);
        }

        // Buat respons dengan essayIds dan detail pertanyaan
        $essayIds = $essays->pluck('course_modul_id'); // Ambil ID semua essay

        return response()->json([
            'essayIds' => $essayIds, // Array ID essay
            'questions' => $essays->map(function ($essay) {
                return [
                    'id' => $essay->id,
                    'question' => $essay->pertanyaan,
                    'image' => $essay->image ? asset('storage/' . $essay->image) : null,
                ];
            }),
            'answer' => $answers ? $answers->jawaban : null, // Jawaban user jika ada
            'totalEssays' => $essays->count(), // Jumlah total essay
        ]);
    }



    public function submitQuiz(Request $request, $modulId, $userId)
    {
        Log::info('Quiz submitted', [
            'modulId' => $modulId,
            'userId' => $userId,
            'data' => $request->all()
        ]);

        try {
            ModulQuizUserAnswer::create([
                'modul_quizzes_id' => $modulId,
                'user_id' => $userId,
                'jawaban' => $request->input('jawaban'),
                'kode_jawaban' => $request->input('kode_jawaban')
            ]);

            return response()->json(['message' => 'Data quiz berhasil disimpan']);
        } catch (\Exception $e) {
            Log::error('Failed to submit quiz', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function submitEssay(Request $request, $modulId, $userId)
    {
        $data = $request->only(['jawaban']);

        try {
            ModulEssayAnswer::create([
                'course_modul_id' => $modulId,
                'user_id' => $userId,
                'jawaban' => $data['jawaban']
            ]);

            return response()->json(['message' => 'Jawaban essay berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateCourseEnrollSummary(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:user_course_enrolls,course_id',
            'finish_date' => 'required|date',
            'status'     => 'required|string',
            'time_spend'  => 'required|integer',
            'progress_bar' => 'required|integer'
        ]);

        $courseId = $request->input('course_id');
        $userId = auth()->user()->ID;

        try {
            $summaryData = [
                'finish_date'   => $request->input('finish_date'),
                'status'       => $request->input('status'),
                'time_spend'    => $request->input('time_spend'),
                'progress_bar'  => $request->input('progress_bar')
            ];

            UserCourseEnroll::where('course_id', $courseId)
            ->where('user_id', $userId) // Menambahkan kondisi user_id
            ->update($summaryData);

            return response()->json(['message' => 'Data summary berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTimeandProgress($course_id, $user_id)
    {
        $enroll = UserCourseEnroll::where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->first();

        if ($enroll) {
            return response()->json([
                'time_spend' => $enroll->time_spend,
                'progress_bar' => $enroll->progress_bar,
            ]);
        } else {
            return response()->json([
                'time_spend' => 0,
                'progress_bar' => 0,
            ]);
        }
    }

    public function postTimeandProgress(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|integer',
            'user_id' => 'required|integer',
            'time_spend' => 'required|integer|min:0',
        ]);

        $enrollment = UserCourseEnroll::where('course_id', $validatedData['course_id'])
        ->where('user_id', $validatedData['user_id'])
        ->first();

        if ($enrollment) {
            $enrollment->time_spend = $validatedData['time_spend'];
            $enrollment->save();

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'Enrollment not found'], 404);
    }
}
