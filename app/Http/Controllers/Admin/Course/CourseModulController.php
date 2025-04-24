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
        $data = Course::with([
            'subCategory',
            'subCategory.category',
            'subCategory.category.divisiCategory',
            'subCategory.category.divisiCategory.learningCategory'
        ])->findOrFail($course_id);
        $courseModul = CourseModul::with('modulQuiz', 'modulQuiz.modulQuizAnswer')->where('course_id', $course_id)->get();
        $totalModul = $courseModul->count();
        $videoModul = $courseModul->where('tipe_media', 'video')->where('active', 1)->count();
        $linkModul = $courseModul->where('tipe_media', 'link')->where('active', 1)->count();
        $pdfModul = $courseModul->where('tipe_media', 'pdf')->where('active', 1)->count();

        $modul = CourseModul::with('modulEssay')->where('course_id', $course_id)->get();

        $checkEnrolledCourse = UserCourseEnroll::where('course_id', $course_id)->pluck('user_id');
        $listUser = User::whereNotIn('ID', $checkEnrolledCourse)->where('Aktif', 1)->orderBy('ID', 'desc')->get(['ID', 'Nama', 'Jabatan']);

        $listUserWithUrls = $listUser->map(function ($user) {
            $datafoto = $user->ID;
            $formattedFoto = $this->formatToFiveDigits($datafoto);
            $cacheBuster = time();
            $user->fotoUrl = "http://192.168.0.8/hrd-milenia/foto/{$formattedFoto}.JPG?v={$cacheBuster}";
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
        try {
            $essay_ids = $request->input('essay_id', []);
            $essays = $request->input('essay', []);
            $files = $request->file('essay_image', []);

            Log::info('Essay Image Mapping:', $request->file('essay_image', []));
            Log::info('Essay Data Mapping:', $essays);

            // Log incoming data
            Log::info('Received Essay Data:', [
                'essay_ids' => $essay_ids,
                'essays' => $essays,
                'files' => $files,
            ]);

            foreach ($essays as $index => $essay) {
                $essayId = $essay_ids[$index] ?? null;
                $file = $files[$index] ?? null;
                $oldImage = $request->input("old_image_{$essayId}", null);

                if ($essayId) {
                    // Update existing essay
                    $existingEssay = ModulEssay::find($essayId);
                    if ($existingEssay) {
                        $existingEssay->pertanyaan = $essay;

                        // Handle file upload if a new file is provided
                        if ($file) {
                            // Cek jika ada gambar lama dan hapus file yang lama
                            if ($existingEssay->image) {
                                Storage::delete('public/' . $existingEssay->image);
                            }

                            $path = $file->store('essay/questions', 'public');
                            $existingEssay->image = $path;
                        } else if ($oldImage) {
                            // Jika tidak ada gambar baru, tetap kirim gambar lama
                            $existingEssay->image = $oldImage;
                        }

                        $existingEssay->save();

                        // Log the updated essay
                        Log::info('Updated Essay:', [
                            'essay_id' => $essayId,
                            'pertanyaan' => $essay,
                            'image' => $existingEssay->image ?? 'No Image Uploaded',
                        ]);
                    }
                } else {
                    // Only create new essay if there's no matching essay_id
                    // Check if essay already exists to prevent duplicate data
                    $existingEssayCheck = ModulEssay::where('pertanyaan', $essay)
                        ->where('course_modul_id', $modul_id)
                        ->first();

                    if (!$existingEssayCheck) {
                        $path = null;
                        if ($file) {
                            $path = $file->store('essay/questions', 'public');
                        }

                        // Add new essay only if not already present
                        $newEssay = ModulEssay::create([
                            'course_modul_id' => $modul_id,
                            'pertanyaan' => $essay,
                            'image' => $path,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Log the newly created essay
                        Log::info('Created New Essay:', [
                            'new_essay_id' => $newEssay->id,
                            'pertanyaan' => $essay,
                            'image' => $newEssay->image,
                        ]);
                    } else {
                        // Log if the essay already exists (skip creation)
                        Log::info('Essay already exists, skipping creation:', [
                            'pertanyaan' => $essay
                        ]);
                    }
                }
            }

            // Return a JSON response on success
            return response()->json(['success' => true, 'message' => 'Essay updated successfully']);
        } catch (\Exception $e) {
            // Handle errors and return JSON error message
            Log::error('Error occurred during essay import:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }



    public function deleteEssay($course_id, $modul_id, $id)
    {
        $essay = ModulEssay::find($id);
        if ($essay) {
            $essay->delete();
            return response()->json(['success' => true, 'message' => 'Essay berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Essay tidak ditemukan.']);
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
