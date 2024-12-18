<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCourseEnroll;
use App\Models\Category\Category;
use App\Models\Course\CourseModul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id(); // ID pengguna yang login

        // Ambil daftar kursus yang diikuti user dengan semua relasi terkait
        $courseEnrolled = UserCourseEnroll::with([
            'course.subCategory',
            'course.category',
            'course.divisiCategory',
            'course.learningCategory',
        ])
            ->where('user_id', $userId)
            ->get();

        // Hitung statistik kursus
        $totalCourses = DB::table('courses')->count();
        $coursesFollowed = UserCourseEnroll::where('user_id', $userId)->count();
        $coursesInProgress = UserCourseEnroll::where('user_id', $userId)->whereNull('status')->count();
        $coursesCompleted = UserCourseEnroll::where('user_id', $userId)->where('status', 'complete')->count();
        $averageProgress = UserCourseEnroll::where('user_id', $userId)->avg('progress_bar');

        $totalTimeSpendInSeconds = UserCourseEnroll::where('user_id', $userId)->sum('time_spend');
        $totalTimeSpendInHours = $totalTimeSpendInSeconds / 3600;

        // Tambahkan data jumlah modul untuk setiap kursus
        foreach ($courseEnrolled as $courseEnrolleds) {
            $modulCount = $courseEnrolleds->course->modul->count(); // Menggunakan relasi modul
            $courseEnrolleds->modul_count = $modulCount; // Tambahkan atribut jumlah modul
            $courseEnrolleds->progress = $courseEnrolleds->progress_bar ?? 0;
        }

        // Kirimkan data ke view
        return view('pages.home.index', compact(
            'courseEnrolled',
            'totalCourses',
            'coursesFollowed',
            'coursesInProgress',
            'coursesCompleted',
            'averageProgress',
            'totalTimeSpendInHours'
        ));
    }

}
