<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Imports\QuizModulImport;
use App\Models\Course\Course;
use App\Models\Course\CourseModul;
use App\Models\Course\ModulEssay;
use App\Models\User;
use App\Models\UserCourseEnroll;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PharIo\Manifest\InvalidUrlException;
use Illuminate\Support\Facades\Log;


use function PHPUnit\Framework\throwException;

class CourseModulController extends Controller
{
    public function index(Request $request, $course_id)
    {
        $data = Course::with('subCategory:id,nama,category_id', 'subCategory.category:id,nama,divisi_category_id', 'subCategory.category.divisiCategory:id,nama', 'user:ID,Nama,Jabatan')->findOrFail($course_id);
        $courseModul = CourseModul::with('modulQuiz', 'modulQuiz.modulQuizAnswer')->where('course_id', $course_id)->get();
        $totalModul = $courseModul->count();
        $videoModul = $courseModul->where('tipe_media', 'video')->where('active', 1)->count();
        $linkModul = $courseModul->where('tipe_media', 'link')->where('active', 1)->count();
        $pdfModul = $courseModul->where('tipe_media', 'pdf')->where('active', 1)->count();

        $modul = CourseModul::with('modulEssay')->where('course_id', $course_id)->get();

        $checkEnrolledCourse = UserCourseEnroll::where('course_id', $course_id)->pluck('user_id');
        $listUser = User::whereNotIn('ID', $checkEnrolledCourse)->orderBy('ID', 'desc')->get(['ID', 'Nama', 'Jabatan']);

        $listUserWithUrls = $listUser->map(function ($user) {
            $datafoto = $user->ID;
            $formattedFoto = $this->formatToFiveDigits($datafoto);
            $user->fotoUrl = "http://192.168.0.8/hrd-milenia/foto/{$formattedFoto}.JPG";
            return $user;
        });

        return view('admin.course.modul.index', compact('data', 'totalModul', 'videoModul', 'linkModul', 'pdfModul', 'courseModul', 'listUser', 'modul'));
    }

    private function formatToFiveDigits($number)
    {
        return str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function create($course_id)
    {
        $data = Course::findOrFail($course_id);

        return view('admin.course.modul.create', compact('data'));
    }

    public function store(Request $request, $course_id)
    {
        try {
            DB::beginTransaction();
            $modul = new CourseModul();
            $modul->course_id = $request->course_id;
            $modul->nama_modul = $request->nama_modul;
            $modul->deskripsi = $request->deskripsi;
            $modul->tipe_media = $request->tipe_media;
            $modul->active = true;

            $checkEntryIsExist = CourseModul::where('course_id', $course_id)->latest()->value('no_urut');
            if ($checkEntryIsExist) {
                $modul->no_urut = $checkEntryIsExist + 1;
            } else {
                $modul->no_urut = 1;
            }

            switch ($request->tipe_media) {
                case 'video':
                    if ($request->hasFile('url_media')) {
                        $folderPath = storage_path('app/public/course/modul/video/');
                        $nameFile = date('YmdHi') . '_' . $request->nama_modul . '.' . $request->url_media->getClientOriginalExtension();
                        $file = $folderPath . '' . $nameFile;

                        $modul->url_media = $nameFile;
                    }
                    break;

                case 'pdf':
                    if ($request->hasFile('url_media')) {
                        $folderPath = storage_path('app/public/course/modul/pdf/');
                        $nameFile = date('YmdHi') . '_' . $request->nama_modul . '.' . $request->url_media->getClientOriginalExtension();
                        $file = $folderPath . '' . $nameFile;

                        $modul->url_media = $nameFile;
                    }
                    break;

                case 'link':
                    $modul->url_media = $request->url_media;
                    break;

                default:
                    $modul->url_media = null;
                    break;
            }
            $modul->save();
            if ($request->tipe_media != 'link') {
                file_put_contents($file, file_get_contents($request->url_media));
            }
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy($course_id, $modul_id)
    {
        try {
            DB::beginTransaction();
            $data = CourseModul::findOrFail($modul_id);
            $file_tipe = $data->tipe_media;
            $filename = $data->url_media;
            $course_id = $data->course_id;
            $data->delete();
            if ($data) {
                if ($file_tipe != 'link') {
                    Storage::disk('public')->delete('course/modul/' . $file_tipe . '/' . $filename);
                }
            }

            DB::commit();
            return redirect()->route('admin.course.modul.index', $course_id)->with([
                'success' => [
                    'title' => 'Sukses',
                    'message' => 'Berhasil menghapus data!'
                ]
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with([
                'error' => [
                    'title' => 'Error!',
                    'message' => $th->getMessage()
                ]
            ]);
        }
    }

    public function importQuizProcess(Request $request, $course_id, $modul_id)
    {
        $request->validate([
            'excel' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new QuizModulImport($modul_id);
            Excel::import($import, $request->file('excel'));

            $errorImport = $import->getErrorImport();

            if (!empty($errorImport)) {
                return redirect()->route('admin.course.modul.index', $course_id)->with([
                    'error' => [
                        'title' => 'Error!',
                        'message' => $errorImport
                    ]
                ]);
            }

            return redirect()->route('admin.course.modul.index', $course_id)->with([
                'success' => [
                    'title' => 'Sukses',
                    'message' => 'Berhasil mengimport data!'
                ]
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('admin.course.modul.index', $course_id)->with([
                'error' => [
                    'title' => 'Error!',
                    'message' => $th->getMessage()
                ]
            ]);
        }
    }

    public function importEssayProcess(Request $request, $course_id, $modul_id)
    {
        // Ambil array essay dari form
        $essays = $request->input('essay', []); 

        if (is_array($essays)) {
            foreach ($essays as $id => $content) {
                if (!empty($content)) {
                    // Jika ID adalah angka valid, cari essay berdasarkan ID untuk update
                    if (is_numeric($id)) {
                        $essay = ModulEssay::where('course_modul_id', $modul_id)->find($id);
                        if ($essay) {
                            // Update essay jika ditemukan
                            $essay->pertanyaan = $content;
                            $essay->updated_at = now();
                            $essay->save();
                        }
                    } else {
                        // Jika ID tidak valid (biasanya key default array), maka tambahkan data baru
                        $exists = DB::table('modul_essay_questions')
                            ->where('course_modul_id', $modul_id)
                            ->where('pertanyaan', $content)
                            ->exists();

                        if (!$exists) {
                            DB::table('modul_essay_questions')->insert([
                                'course_modul_id' => $modul_id,
                                'pertanyaan' => $content,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Semua pertanyaan berhasil diproses!']);
    }

    public function deleteEssay($course_id, $modul_id, $id)
    {
        // Temukan essay berdasarkan ID
        $essay = ModulEssay::where('course_modul_id', $modul_id)->find($id);

        if (!$essay) {
            return response()->json(['success' => false, 'message' => 'Essay tidak ditemukan'], 404);
        }

        // Hapus data essay
        $essay->delete();

        return response()->json(['success' => true, 'message' => 'Essay berhasil dihapus']);
    }

    public function isActive(Request $request, $course_id, $modul_id)
    {
        try {
            DB::beginTransaction();
            $courseModul = CourseModul::find($modul_id);
            if (isset($request->updateStatus) && $request->updateStatus) {
                $courseModul->active = $request->active;
                $courseModul->save();
                DB::commit();

                return response()->json(['isActive' => intval($courseModul->active)], 200);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    public function updateOrder(Request $request, $course_id)
    {
        $order = $request->input('order');

        foreach ($order as $index => $item) {
            $modul = CourseModul::find($item['id']);
            if ($modul) {
                $modul->no_urut = $item['no_urut'];
                $modul->save();
            }
        }

        return response()->json(['success' => true]);
    }
}
