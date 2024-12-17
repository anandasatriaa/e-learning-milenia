<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Category\SubCategory;
use App\Models\Category\LearningCategory;
use App\Models\Course\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $show = $request->get('show') ?? 15;
        $query = Course::withCount('modul', 'user')
            ->with(['subCategory.category.divisiCategory.learningCategory']) // Memuat relasi yang diperlukan
            ->latest();

        if ($search) {
            $query->where('nama_kelas', 'LIKE', "%$search%");
        }

        $data = $query->paginate($show)->withQueryString();

        if ($request->ajax()) {
            // Jika permintaan berasal dari AJAX, kembalikan hanya tabelnya
            return view('admin.course.course.index', compact('data'))->render();
        }

        return view('admin.course.course.index', compact('data', 'search', 'show'));
    }

    public function create()
    {
        $learningCategories = LearningCategory::with('divisiCategories.categories.subCategories')->get();
        $courses = Course::with('learningCategory', 'divisiCategory', 'category', 'subCategory')->get();
        // dd($courses);
        return view('admin.course.course.create', compact('courses', 'learningCategories'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $course = new Course();

            // Check and process the selected value
            if ($request->filled('form_dropdown')) {
                $selectedValue = $request->input('form_dropdown');

                // Log the selected value to verify
                Log::info('Selected Value: ' . $selectedValue); // Log the selected value

                // Explode value to get prefix and ID
                $parts = explode('_', $selectedValue);

                // Log the exploded parts
                Log::info('Exploded Parts: ', $parts); // Log the exploded parts

                // Process based on the number of parts in the exploded array
                if (count($parts) == 8) {
                    // If there are 7 parts, process as usual
                    $course->sub_category_id = $parts[1];   // subCategory ID
                    $course->category_id = $parts[3];       // category ID
                    $course->divisi_category_id = $parts[5]; // divisiCategory ID
                    $course->learning_cat_id = $parts[7];   // learningCategory ID
                } elseif (count($parts) == 6) {
                    // If there are 5 parts, handle accordingly
                    $course->category_id = $parts[1];       // category ID
                    $course->divisi_category_id = $parts[3]; // divisiCategory ID
                    $course->learning_cat_id = $parts[5];   // learningCategory ID
                } elseif (count($parts) == 4) {
                    // If there are 3 parts, process as needed
                    $course->divisi_category_id = $parts[1]; // divisiCategory ID
                    $course->learning_cat_id = $parts[3];       // category ID
                } elseif (count($parts) == 2) {
                    // If there is only 1 part, process accordingly
                    $course->learning_cat_id = $parts[1];   // learningCategory ID
                } else {
                    Log::warning('Invalid number of parts in dropdown value. Expected 1, 3, 5, or 7 parts, got ' . count($parts));
                }
            }

            $course->nama_kelas = $request->nama_kelas;

            $image_parts = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $folderPath = storage_path('app/public/course/thumbnail/');
            $image_name =  date('YmdHi') .  '_' . $request->nama_kelas  . '.' . $image_type;
            $file = $folderPath . '' . $image_name;
            $course->thumbnail = $image_name;

            if ($request->hasFile('thumbnail_video')) {
                $folderPath = storage_path('app/public/course/thumbnail_video/');
                $nameFile = date('YmdHi') . '_' . $request->nama_kelas . '.' . $request->thumbnail_video->getClientOriginalExtension();
                $fileVideo = $folderPath . '' . $nameFile;

                $course->thumbnail_video = $nameFile;
                file_put_contents($fileVideo, file_get_contents($request->thumbnail_video));
            }

            $course->deskripsi = $request->deskripsi;
            $course->active = true;
            $course->save();
            DB::commit();
            file_put_contents($file, $image_base64);

            return redirect()->route('admin.course.modul.index', $course->id)->with([
                'success' => [
                    'title' => 'Sukses',
                    'message' => 'Berhasil menambah data!'
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

    public function edit($id)
    {
        $learningCategories = LearningCategory::with('divisiCategories.categories.subCategories')->get();
        $data = Course::findOrFail($id);
        
        return view('admin.course.course.edit', compact('data', 'learningCategories'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $course = Course::findOrFail($id);

            // Proses dropdown jika diisi
            if ($request->filled('form_dropdown')) {
                $selectedValue = $request->input('form_dropdown');

                // Log untuk debugging
                Log::info('Selected Value: ' . $selectedValue);

                $parts = explode('_', $selectedValue);

                // Reset nilai sebelumnya terlebih dahulu
                $course->sub_category_id = null;
                $course->category_id = null;
                $course->divisi_category_id = null;
                $course->learning_cat_id = null;

                if (count($parts) == 8) {
                    $course->sub_category_id = $parts[1];
                    $course->category_id = $parts[3];
                    $course->divisi_category_id = $parts[5];
                    $course->learning_cat_id = $parts[7];
                } elseif (count($parts) == 6) {
                    $course->category_id = $parts[1];
                    $course->divisi_category_id = $parts[3];
                    $course->learning_cat_id = $parts[5];
                } elseif (count($parts) == 4) {
                    $course->divisi_category_id = $parts[1];
                    $course->learning_cat_id = $parts[3];
                } elseif (count($parts) == 2) {
                    $course->learning_cat_id = $parts[1];
                } else {
                    Log::warning('Invalid number of parts in dropdown value.');
                }
            }

            $course->nama_kelas = $request->nama_kelas;

            $oldValueImageName = $course->thumbnail;

            if (isset($request->image)) {
                $image_parts = explode(";base64,", $request->image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $folderPath = storage_path('app/public/course/thumbnail/');
                $image_name =  date('YmdHi') .  '_' . $request->nama_kelas  . '.' . $image_type;
                $file = $folderPath . '' . $image_name;
                $course->thumbnail = $image_name;
            }

            if ($request->hasFile('thumbnail_video')) {
                $folderPath = storage_path('app/public/course/thumbnail_video/');
                $nameFile = date('YmdHi') . '_' . $request->nama_kelas . '.' . $request->thumbnail_video->getClientOriginalExtension();
                $fileVideo = $folderPath . '' . $nameFile;

                $course->thumbnail_video = $nameFile;
                file_put_contents($fileVideo, file_get_contents($request->thumbnail_video));
            }

            $course->deskripsi = $request->deskripsi;
            $course->save();

            DB::commit();
            if (isset($request->image)) {
                file_put_contents($file, $image_base64);
                Storage::disk('public')->delete('course/thumbnail/' . $oldValueImageName);
            }

            if ($request->hasFile($request->thumbnail_video)) {
                file_put_contents($fileVideo, file_get_contents($request->thumbnail_video));
                Storage::disk('public')->delete('course/thumbnail_video/' . $course->thumbnail_video);
            }

            return redirect()->route('admin.course.course.index')->with([
                'success' => [
                    'title' => 'Sukses',
                    'message' => 'Berhasil mengubah data!'
                ]
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with([
                'error' => [
                    'title' => 'Error!',
                    'message' => $th->errorInfo[2]
                ]
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $data = Course::findOrFail($id);
            $filename = $data->thumbnail;
            $filenameVideo = $data->thumbnail_video;
            $data->delete();
            if ($data) {
                Storage::disk('public')->delete('course/thumbnail/' . $filename);
                Storage::disk('public')->delete('course/thumbnail_video' . $filenameVideo);
            }

            DB::commit();
            return redirect()->route('admin.course.course.index')->with([
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

    public function isActive(Request $request, $id)
    {
        $course = Course::findOrfail($id);

        if (isset($request->updateStatus) && $request->updateStatus) {
            $course->active = $request->active;
            $course->save();
            DB::commit();

            return response()->json(['isActive' => intval($course->active)], 200);
        }
    }
}
