<?php

namespace App\Http\Controllers\Admin\Calendar;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        $users = User::select('Nama', 'Divisi')->get();
        return view('admin.calendar.index', compact('users'));
    }
}
