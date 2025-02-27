<?php

namespace App\Http\Controllers\Admin\Calendar;

use App\Models\User;
use App\Models\Calendar\Calendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Mail\ReminderEmail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventsExport;
use Yajra\DataTables\Facades\DataTables;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil daftar divisi unik dari tabel users
        $divisi = User::where('Aktif', 1)->distinct()->pluck('Divisi');

        // Mengambil daftar acara unik dari tabel calendar
        $acaraDistinct = Calendar::distinct()->pluck('acara');

        // Membuat query untuk events dengan filter
        $query = Calendar::query();
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }
        if ($request->filled('acara')) {
            $query->where('acara', $request->acara);
        }
        $events = $query->get();

        // Mendapatkan data pengguna untuk fullCalendar (jika diperlukan)
        $users = User::select('ID', 'Nama', 'Divisi')->where('Aktif', 1)->get();
        $usersWithFoto = $users->map(function ($user) {
            $formattedFoto = str_pad($user->ID, 5, '0', STR_PAD_LEFT);
            $user->fotoUrl = "http://192.168.0.8/hrd-milenia/foto/{$formattedFoto}.JPG";
            return $user;
        });

        $eventsForFullCalendar = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->acara . ' - ' . $event->nama . ' (' . $event->divisi . ')',
                'start' => $event->start_date . 'T00:00:00',
                'end' => $event->end_date . 'T23:59:59',
                'backgroundColor' => $event->bg_color,
            ];
        });

        return view('admin.calendar.index', compact('usersWithFoto', 'eventsForFullCalendar', 'events', 'divisi', 'acaraDistinct'));
    }

    public function export(Request $request)
    {
        // Query events sesuai filter yang diterima
        $query = Calendar::query();
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }
        if ($request->filled('acara')) {
            $query->where('acara', $request->acara);
        }
        $events = $query->get();

        // Menggunakan Laravel Excel untuk mendownload file Excel
        return Excel::download(new EventsExport($events), 'jadwal_elearning.xlsx');
    }

    public function data(Request $request)
    {
        $query = Calendar::query();
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }
        if ($request->filled('acara')) {
            $query->where('acara', $request->acara);
        }

        return DataTables::of($query)
        ->addColumn('nama_divisi', function ($event) {
            return $event->nama ? $event->nama . ' - ' . $event->divisi : $event->divisi;
        })
            ->editColumn('bg_color', function ($event) {
                return '<span class="badge" style="background-color: ' . $event->bg_color . ';">' . $event->bg_color . '</span>';
            })
            ->addColumn('aksi', function ($event) {
                return '<button type="button" id="btnHapus_' . $event->id . '" class="btn btn-icon btn-round btn-danger" data-id="' . $event->id . '">
                        <i class="fas fa-trash-alt"></i>
                    </button>';
            })
            ->rawColumns(['bg_color', 'aksi'])
            ->make(true);
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

            // Ambil email_karyawan berdasarkan user_id
            $user = User::find($event->user_id);
            if ($user && $user->email_karyawan) {
                $details = [
                    'eventName' => $event->acara,
                    'division' => $event->divisi,
                    'personName' => $event->nama,
                    'startDate' => $event->start_date,
                    'endDate' => $event->end_date,
                ];

                // Kirim email
                Mail::to($user->email_karyawan)->send(new ReminderEmail($details));
            }
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

    public function destroy($id)
    {
        $event = Calendar::findOrFail($id); // Mencari data berdasarkan ID
        $event->delete(); // Menghapus data
        return response()->json(['success' => true]);
    }

}
