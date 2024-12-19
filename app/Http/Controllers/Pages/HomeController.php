<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCourseEnroll;
use App\Models\Calendar\Calendar;
use App\Models\Category\Category;
use App\Models\Course\CourseModul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id(); // ID pengguna yang login
        $userDivisi = Auth::user()->Divisi; // Ambil divisi pengguna atau set default
        $userName = Auth::user()->Nama; // Ambil nama pengguna atau set default

        // Log untuk mengecek nilai userId, userDivisi, dan userName
        Log::info('User ID: ' . $userId);
        Log::info('User Divisi: ' . $userDivisi);
        Log::info('User Name: ' . $userName);

        $query = Calendar::where('divisi', $userDivisi)
            ->where(function ($subQuery) use ($userName) {
                $subQuery->where('nama', $userName)
                    ->orWhereNull('nama');
            });

        // Log query SQL dengan parameter
        Log::info('Query SQL: ' . $query->toSql());
        Log::info('Query Bindings: ' . json_encode($query->getBindings()));

        // Ambil hasil query
        $events = $query->get();

        // Format data untuk FullCalendar
        $eventsData = $events->map(function ($event) {
            $adjustedEndDate = \Carbon\Carbon::parse($event->end_date)->addDay()->format('Y-m-d');
            return [
                'title' => $event->acara,
                'divisi' => $event->divisi,
                'nama' => $event->nama,
                'start' => $event->start_date,
                'end' => $adjustedEndDate, // Tambahkan satu hari ke tanggal akhir
                'backgroundColor' => $event->bg_color,
            ];
        });


        // Log hasil eventsData
        Log::info('Events data: ' . json_encode($eventsData));

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
        return view('pages.home.index', [
            'events' => $eventsData,
            'courseEnrolled' => $courseEnrolled,
            'totalCourses' => $totalCourses,
            'coursesFollowed' => $coursesFollowed,
            'coursesInProgress' => $coursesInProgress,
            'coursesCompleted' => $coursesCompleted,
            'averageProgress' => $averageProgress,
            'totalTimeSpendInHours' => $totalTimeSpendInHours,
        ]);
    }
}
