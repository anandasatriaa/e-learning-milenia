<?php

namespace App\Http\Controllers\Admin\Calendar;

use App\Models\User;
use App\Models\Calendar\Calendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    public function index()
    {
        // Mengambil data pengguna dengan nama dan divisi
        $users = User::select('ID', 'Nama', 'Divisi')->get();
        $events = Calendar::all();

        // Menambahkan properti fotoUrl ke setiap pengguna
        $usersWithFoto = $users->map(function ($user) {
            $formattedFoto = str_pad($user->ID, 5, '0', STR_PAD_LEFT); // Format ID menjadi lima digit
            $user->fotoUrl = "http://192.168.0.8/hrd-milenia/foto/{$formattedFoto}.JPG";
            return $user;
        });

        // Mengirimkan data ke view
        return view('admin.calendar.index', compact('usersWithFoto', 'events'));
    }

    public function store(Request $request)
    {
        // Log input untuk debug
        Log::debug($request->all());

        // Validate the form data
        $validator = Validator::make($request->all(), [
            'eventName' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'personName' => 'nullable|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'backgroundColor' => 'required|string|max:7',
            'userId' => 'nullable|integer|exists:users,ID',
        ]);

        if ($validator->fails()) {
            Log::debug('Validation failed', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil user_id berdasarkan personName jika ada
        $userId = null;
        if ($request->has('personName') && $request->input('personName') !== null) {
            // Cari user_id berdasarkan personName
            $user = User::where('Nama', $request->input('personName'))->first();
            if ($user) {
                $userId = $request->input('userId');  // Dapatkan user_id
            }
        }

        // Lanjutkan menyimpan data
        Log::debug('Validation passed, storing data...');
        $event = new Calendar();
        $event->acara = $request->input('eventName');
        $event->divisi = $request->input('division');
        $event->nama = $request->input('personName');
        $event->start_date = $request->input('startDate');
        $event->end_date = $request->input('endDate');
        $event->bg_color = $request->input('backgroundColor');
        $event->user_id = $userId;
        try {
            $event->save();
            Log::debug('Event saved successfully:', $event->toArray());
        } catch (\Exception $e) {
            Log::error('Error saving event: ' . $e->getMessage());
        }

        // Kembalikan response JSON untuk memperbarui kalender
        return response()->json([
            'id' => $event->id,
            'title' => $event->acara . ' - ' . $event->nama . ' (' . $event->divisi . ')',
            'start' => $event->start_date,
            'end' => $event->end_date,
            'backgroundColor' => $event->bg_color,
        ]);
    }
}
