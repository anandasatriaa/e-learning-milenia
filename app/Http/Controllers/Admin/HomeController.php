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
        // Data filter Divisi
        $divisionFilter = DB::table('users')->distinct()->pluck('Divisi')->toArray();


        // Data filter Tahun
        $currentYear = date('Y');
        $years = range($currentYear, $currentYear + 10);


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
                WHEN TIME(login_time) BETWEEN '07:00:00' AND '17:30:00' THEN 'Jam Kerja (07:00 - 17:30)'
                ELSE 'Luar Jam Kerja (17:31 - 06:59)'
            END as category,
            COUNT(*) as total
        ")
            ->whereNotIn('user_id', [1, 2]) // Kecualikan admin
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Isi default jika salah satu kategori kosong
        $loginCategoryData = [
            'Jam Kerja (07:00 - 17:30)' => $loginData['Jam Kerja (07:00 - 17:30)'] ?? 0,
            'Luar Jam Kerja (17:31 - 06:59)' => $loginData['Luar Jam Kerja (17:31 - 06:59)'] ?? 0,
        ];

        $averageLoginTime = DB::table('user_sessions')
            ->whereNotIn('user_id', [1, 2]) // Kecualikan admin
            ->avg(DB::raw('TIME_TO_SEC(TIME(login_time))')); // Rata-rata dalam detik

        // Format waktu rata-rata ke format jam:menit:detik
        $formattedAverageLoginTime = gmdate('H:i', $averageLoginTime) . ' WIB';



        return view('admin.home.index', compact('divisionFilter', 'years', 'totalCourseEnrolls', 'totalParticipants', 'events', 'monthlyData', 'divisionLabels', 'divisionData', 'averageProgress', 'completedData', 'inProgressData', 'loginCategoryData', 'formattedAverageLoginTime'));
    }

    public function getChartData(Request $request)
    {
        $divisi = $request->input('divisi');

        // Data untuk monthlyData
        $monthlyParticipantsQuery = UserCourseEnroll::selectRaw('MONTH(enroll_date) as month, COUNT(DISTINCT user_id) as total')
            ->groupBy('month');
        if ($divisi !== 'Semua Divisi') {
            $monthlyParticipantsQuery->whereHas('user', function ($query) use ($divisi) {
                $query->where('Divisi', $divisi);
            });
        }
        $monthlyParticipants = $monthlyParticipantsQuery->pluck('total', 'month')->toArray();
        $monthlyData = array_fill(1, 12, 0);
        foreach ($monthlyParticipants as $month => $total) {
            $monthlyData[$month] = $total;
        }

        // Data untuk division time spend
        $divisionsQuery = DB::table('users')
            ->join('user_course_enrolls', 'users.ID', '=', 'user_course_enrolls.user_id')
            ->select('users.Divisi', DB::raw('ROUND(AVG(user_course_enrolls.time_spend) / 60, 1) as average_time_spend'))
            ->groupBy('users.Divisi');
        if ($divisi !== 'Semua Divisi') {
            $divisionsQuery->where('users.Divisi', $divisi);
        }
        $divisions = $divisionsQuery->pluck('average_time_spend', 'users.Divisi')->toArray();
        $divisionLabels = array_keys($divisions);
        $divisionData = array_values($divisions);

        // Data untuk loginAverage
        $loginQuery = DB::table('user_sessions')
            ->join('users', 'user_sessions.user_id', '=', 'users.ID')
            ->selectRaw("
                CASE
                    WHEN TIME(user_sessions.login_time) BETWEEN '07:00:00' AND '17:30:00' THEN 'Jam Kerja (07:00 - 17:30)'
                    ELSE 'Luar Jam Kerja (17:31 - 06:59)'
                END as category,
                COUNT(*) as total
            ")
            ->whereNotIn('user_sessions.user_id', [1, 2]) // Kecualikan admin
            ->groupBy('category');

        if ($divisi !== 'Semua Divisi') {
            $loginQuery->where('users.Divisi', $divisi); // Filter berdasarkan divisi
        }

        $loginData = $loginQuery->pluck('total', 'category')->toArray();


        $loginCategoryData = [
            'Jam Kerja (07:00 - 17:30)' => $loginData['Jam Kerja (07:00 - 17:30)'] ?? 0,
            'Luar Jam Kerja (17:31 - 06:59)' => $loginData['Luar Jam Kerja (17:31 - 06:59)'] ?? 0,
        ];

        // Data untuk averageProgress berdasarkan divisi
        $averageProgressQuery = DB::table('user_course_enrolls')
        ->join('users', 'user_course_enrolls.user_id', '=', 'users.ID')
        ->select('users.Divisi', DB::raw('AVG(user_course_enrolls.progress_bar) as average_progress'))
        ->groupBy('users.Divisi');

        if ($divisi !== 'Semua Divisi') {
            $averageProgressQuery->where('users.Divisi', $divisi); // Filter berdasarkan divisi
        }

        // Ambil nilai rata-rata progress berdasarkan divisi
        $averageProgressResult = $averageProgressQuery->pluck('average_progress', 'users.Divisi')->toArray();
        // Periksa jika divisi spesifik dipilih
        if ($divisi !== 'Semua Divisi') {
            $averageProgress = $averageProgressResult[$divisi] ?? 0; // Jika divisi spesifik, ambil rata-rata untuk divisi tersebut
        } else {
            // Jika Semua Divisi, hitung rata-rata dari semua divisi
            $averageProgress = count($averageProgressResult) > 0 ? array_sum($averageProgressResult) / count($averageProgressResult) : 0;
        }

        // Ambil jumlah "Course Completed" berdasarkan divisi
        $completedDataQuery = UserCourseEnroll::where('status', 'completed');
        // Ambil jumlah "Course in Progress" berdasarkan divisi
        $inProgressDataQuery = UserCourseEnroll::whereNull('status');

        // Jika divisi dipilih, filter berdasarkan divisi tersebut
        if ($divisi !== 'Semua Divisi') {
            $completedDataQuery->whereHas('user', function ($query) use ($divisi) {
                $query->where('Divisi', $divisi);
            });

            $inProgressDataQuery->whereHas('user', function ($query) use ($divisi) {
                $query->where('Divisi', $divisi);
            });
        }

        // Hitung jumlah untuk masing-masing status
        $completedData = $completedDataQuery->count();
        $inProgressData = $inProgressDataQuery->count();

        $averageLoginTime = DB::table('user_sessions')
        ->join('users', 'user_sessions.user_id', '=', 'users.ID')
        ->whereNotIn('user_sessions.user_id', [1, 2]) // Kecualikan admin
        ->when($divisi !== 'Semua Divisi', function ($query) use ($divisi) {
            // Filter berdasarkan divisi jika tidak 'Semua Divisi'
            $query->where('users.Divisi', $divisi);
        })
            ->avg(DB::raw('TIME_TO_SEC(TIME(user_sessions.login_time))')); // Rata-rata dalam detik

        // Format waktu rata-rata ke format jam:menit
        $formattedAverageLoginTime = gmdate('H:i', $averageLoginTime) . ' WIB';

        return response()->json([
            'monthlyData' => array_values($monthlyData),
            'divisionLabels' => $divisionLabels,
            'divisionData' => $divisionData,
            'loginAverage' => array_values($loginCategoryData),
            'averageProgress' => $averageProgress,
            'completedData' => $completedData,
            'inProgressData' => $inProgressData,
            'formattedAverageLoginTime' => $formattedAverageLoginTime
        ]);
    }
}
