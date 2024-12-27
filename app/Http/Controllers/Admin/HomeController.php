<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Models\UserCourseEnroll;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Hitung total courses
        $totalCourses = Course::count();

        // Hitung total participants dengan ID berbeda
        $totalParticipants = UserCourseEnroll::distinct('user_id')->count('user_id');

        // Ambil data dari tabel calendar
        $events = DB::table('calendar')->select(
            'acara as title',
            'nama',
            'divisi',
            'start_date as start',
            'end_date as end',
            'bg_color as color'
        )->get();

        return view('admin.home.index', compact('totalCourses','totalParticipants', 'events'));
    }
}
