<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Category\SubCategory;
use App\Models\Course\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $show = $request->get('show') ?? 15;
        $query = Course::withCount('modul', 'user')->latest();
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
        $subCategory = SubCategory::with('category:id,nama,divisi_category_id', 'category.divisiCategory:id,nama')->get();
        return view('admin.course.course.create', compact('subCategory'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $course = new Course();
            $course->sub_category_id = $request->sub_category_id;
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
        $subCategory = SubCategory::with('category:id,nama,divisi_category_id', 'category.divisiCategory:id,nama')->get();
        $data = Course::findOrFail($id);
        return view('admin.course.course.edit', compact('data', 'subCategory'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $course = Course::findOrFail($id);
            $course->sub_category_id = $request->sub_category_id;
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
