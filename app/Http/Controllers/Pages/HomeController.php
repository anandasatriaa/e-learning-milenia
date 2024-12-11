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
        // Ambil ID pengguna yang sedang login
        $userId = Auth::id(); // atau Auth::user()->id

        // Ambil daftar kursus yang diikuti user (dengan relasi)
        $courseEnrolled = UserCourseEnroll::with('course')->where('user_id', $userId)->get();

        // 1. Kursus Tersedia
        $totalCourses = DB::table('courses')->count();

        // 2. Kursus Diikuti
        $coursesFollowed = DB::table('user_course_enrolls')
        ->where('user_id', $userId)
            ->count();

        // 3. Kursus Sedang Dipelajari
        $coursesInProgress = DB::table('user_course_enrolls')
        ->where('user_id', $userId)
        ->whereNull('status')
            ->count();

        // 4. Kursus Telah Diselesaikan
        $coursesCompleted = DB::table('user_course_enrolls')
        ->where('user_id', $userId)
            ->where('status', 'complete')
            ->count();

        // Untuk setiap course enrolled, ambil kategori dan jumlah modul
        foreach ($courseEnrolled as $courseEnrolleds) {
            // Ambil kategori berdasarkan sub_category_id
            $category = Category::find($courseEnrolleds->course->sub_category_id);

            // Hitung jumlah modul berdasarkan course_id
            $modulCount = CourseModul::where('course_id', $courseEnrolleds->course_id)->count();

            // Menambahkan data kategori dan jumlah modul ke dalam collection
            $courseEnrolleds->category_name = $category ? $category->nama : 'No Category';
            $courseEnrolleds->modul_count = $modulCount;
        }

        // Kirimkan data ke view
        return view('pages.home.index', compact(
            'courseEnrolled',
            'totalCourses',
            'coursesFollowed',
            'coursesInProgress',
            'coursesCompleted'
        ));

        return view('pages.home.index', compact('courseEnrolled'));
    }

}
