<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCourseEnroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $courseEnrolled = UserCourseEnroll::with('course')->where('user_id', Auth::user()->ID)->get();
        return view('pages.home.index', compact('courseEnrolled'));
    }
}
