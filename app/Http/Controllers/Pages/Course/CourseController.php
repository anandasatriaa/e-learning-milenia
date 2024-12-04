<?php

namespace App\Http\Controllers\Pages\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Course\CourseModul;
use App\Models\Course\ModulQuiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function detailcourse(Request $request, $course_id)
    {
        $course = Course::with('modul')->where('id', $course_id)->first();
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

    public function quiz($modul_quiz_id)
    {
        // Ambil quiz berdasarkan modul_quiz_id
        $quiz = ModulQuiz::with('answers') // Memuat soal dan jawabannya
        ->where('id', $modul_quiz_id)
            ->firstOrFail();

        // Kirim data quiz ke view
        return view('pages.course.course.index', compact('quiz'));
    }

    public function submitQuiz(Request $request, $modul_quiz_id)
    {
        $quiz = ModulQuiz::findOrFail($modul_quiz_id);
        $correctAnswer = $quiz->kunci_jawaban;

        if ($request->input('answer') == $correctAnswer) {
            // Logika untuk jawaban benar
            return back()->with('success', 'Jawaban benar!');
        } else {
            // Logika untuk jawaban salah
            return back()->with('error', 'Jawaban salah. Coba lagi!');
        }
    }

}
