<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Models\UserCourseEnroll;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Hitung total courses
        $totalCourseEnrolls = UserCourseEnroll::count();


        // Hitung total participants dengan ID berbeda
        $totalParticipants = UserCourseEnroll::distinct('user_id')->count('user_id');


        // Ambil data peserta berdasarkan bulan
        $monthlyParticipants = UserCourseEnroll::selectRaw('MONTH(enroll_date) as month, COUNT(DISTINCT user_id) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // Isi array dengan nilai default (0) untuk bulan yang tidak ada datanya
        $monthlyData = array_fill(1, 12, 0);
        foreach ($monthlyParticipants as $month => $total) {
            $monthlyData[$month] = $total;
        }


        // Ambil rata-rata time_spend berdasarkan divisi
        $divisions = DB::table('users')
            ->join('user_course_enrolls', 'users.ID', '=', 'user_course_enrolls.user_id')
            ->select('users.Divisi', DB::raw('ROUND(AVG(user_course_enrolls.time_spend) / 60, 1) as average_time_spend'))
            ->groupBy('users.Divisi')
            ->pluck('average_time_spend', 'users.Divisi')
            ->toArray();

        // Ambil nama divisi dan rata-rata time_spend
        $divisionLabels = array_keys($divisions); // Nama divisi
        $divisionData = array_values($divisions); // Data rata-rata


        // Ambil rata-rata progress_bar
        $averageProgress = UserCourseEnroll::avg('progress_bar');


        // Hitung jumlah "Course Completed" dan "Course in Progress"
        $completedData = UserCourseEnroll::where('status', 'completed')->count();
        $inProgressData = UserCourseEnroll::whereNull('status')
            ->orWhere('status', '!=', 'completed')
            ->count();


        // Ambil data dari tabel calendar
        $events = DB::table('calendar')->select(
            'acara as title',
            'nama',
            'divisi',
            'start_date as start',
            'end_date as end',
            'bg_color as color'
        )->get();


        // Ambil dan kategorikan data login berdasarkan jam
        $loginData = DB::table('user_sessions')
            ->selectRaw("
            CASE
                WHEN TIME(login_time) BETWEEN '08:30:00' AND '17:30:00' THEN 'Jam Kerja (08:30 - 17:30)'
                ELSE 'Luar Jam Kerja (17:31 - 08:29)'
            END as category,
            COUNT(*) as total
        ")
            ->whereNotIn('user_id', [1, 2]) // Kecualikan admin
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Isi default jika salah satu kategori kosong
        $loginCategoryData = [
            'Jam Kerja (08:30 - 17:30)' => $loginData['Jam Kerja (08:30 - 17:30)'] ?? 0,
            'Luar Jam Kerja (17:31 - 08:29)' => $loginData['Luar Jam Kerja (17:31 - 08:29)'] ?? 0,
        ];

        $averageLoginTime = DB::table('user_sessions')
            ->whereNotIn('user_id', [1, 2]) // Kecualikan admin
            ->avg(DB::raw('TIME_TO_SEC(TIME(login_time))')); // Rata-rata dalam detik

        // Format waktu rata-rata ke format jam:menit:detik
        $formattedAverageLoginTime = gmdate('H:i', $averageLoginTime) . ' WIB';



        return view('admin.home.index', compact('totalCourseEnrolls', 'totalParticipants', 'events', 'monthlyData', 'divisionLabels', 'divisionData', 'averageProgress', 'completedData', 'inProgressData', 'loginCategoryData', 'formattedAverageLoginTime'));
    }
}
