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
        $course = Course::with(['modul', 'modul.quizzes', 'modul.essays'])->where('id', $course_id)->first();
        return view('pages.course.course.index', compact('course'));
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
        // Ambil modul dengan `course_id` yang diurutkan berdasarkan `no_urut` (aktif)
        $modul = CourseModul::where('course_id', $course_id)
            ->where('active', 1)
            ->orderBy('no_urut', 'asc')
            ->first();

        if ($modul) {
            return response()->json([
                'success' => true,
                'url' => $modul->url_media,
                'tipe_media' => $modul->tipe_media,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada modul yang ditemukan.'
        ]);
    }

    public function getQuiz($quiz_id)
    {
        // Fetch quiz question and its answers from the database
        $quiz = ModulQuiz::with('answers')->find($quiz_id);

        if ($quiz) {
            // Pastikan course_modul_id benar-benar berasal dari tabel course_moduls
            $courseModulId = $quiz->course_modul_id;

            if (!$courseModulId) {
                return response()->json(['message' => 'Course module ID not found'], 404);
            }

            // Fetch quizzes only for the same course_modul_id
            $quizzesInModule = ModulQuiz::where('course_modul_id', $courseModulId)->pluck('id')->toArray();
            $quizIndex = array_search($quiz_id, $quizzesInModule) + 1;

            return response()->json([
                'question' => $quiz->pertanyaan,
                'kunci_jawaban' => $quiz->answers->pluck('pilihan')->toArray(),
                'totalQuizzes' => count($quizzesInModule), // Count quizzes in the module
                'quizIds' => $quizzesInModule, // Send only IDs from the same module
                'quizIndex' => $quizIndex,
                'course_modul_id' => $courseModulId, // Include the module ID
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
        $essays = ModulEssay::where('course_modul_id', $course_modul_id)->get();

        if ($essays->isNotEmpty()) {
            return response()->json([
                'questions' => $essays->map(function ($essay) {
                    return [
                        'id' => $essay->id,
                        'question' => $essay->pertanyaan,
                    ];
                }),
            ]);
        }

        return response()->json(['message' => 'No essays found for this module'], 404);
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

        try {
            $summaryData = [
                'finish_date'   => $request->input('finish_date'),
                'status'       => $request->input('status'),
                'time_spend'    => $request->input('time_spend'),
                'progress_bar'  => $request->input('progress_bar')
            ];

            UserCourseEnroll::where('course_id', $courseId)->update($summaryData);

            return response()->json(['message' => 'Data summary berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
