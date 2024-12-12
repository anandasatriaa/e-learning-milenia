<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\LearningCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LearningCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $show = $request->query('show', 15);

        $data = LearningCategory::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            })
            ->paginate($show);

        return view('admin.category.learning.index', compact('data', 'search', 'show'));
    }

    public function create()
    {
        return view('admin.category.learning.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $learningCategory = new LearningCategory();
            $learningCategory->nama = $request->nama;

            $image_parts = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $folderPath = storage_path('app/public/category/learning/');
            $image_name =  date('YmdHi') .  '_' . $request->nama  . '.' . $image_type;
            $file = $folderPath . '' . $image_name;
            $learningCategory->image = $image_name;

            $learningCategory->deskripsi = $request->deskripsi;
            $learningCategory->active = true;
            $learningCategory->save();
            DB::commit();
            file_put_contents($file, $image_base64);

            return redirect()->route('admin.category.learning.index')->with([
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
        $data = LearningCategory::findOrFail($id);
        return view('admin.category.learning.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $learningCategory = LearningCategory::findOrfail($id);

            if (isset($request->updateStatus) && $request->updateStatus) {
                $learningCategory->active = $request->active;
                $learningCategory->save();
                DB::commit();

                return response()->json(['isActive' => intval($learningCategory->active)], 200);
            }
            $oldValueImageName = $learningCategory->image;

            $learningCategory->nama = $request->nama;

            if (isset($request->image)) {
                $image_parts = explode(";base64,", $request->image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $folderPath = storage_path('app/public/category/learning/');
                $image_name =  date('YmdHi') .  '_' . $request->nama  . '.' . $image_type;
                $file = $folderPath . '' . $image_name;
                $learningCategory->image = $image_name;
            }

            $learningCategory->deskripsi = $request->deskripsi;
            $learningCategory->save();
            DB::commit();

            if (isset($request->image)) {
                file_put_contents($file, $image_base64);
                Storage::disk('public')->delete('category/learning/' . $oldValueImageName);
            }

            return redirect()->route('admin.category.learning.index')->with([
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
                    'message' => $th->getMessage()
                ]
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $data = LearningCategory::findOrFail($id);
            $filename = $data->image;
            $data->delete();
            if ($data) {
                Storage::disk('public')->delete('category/learning/' . $filename);
            }

            DB::commit();
            return redirect()->route('admin.category.learning.index')->with([
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'active' => 'required|boolean',
        ]);

        $category = LearningCategory::findOrFail($id);
        $category->update(['active' => $request->active]);

        return response()->json([
            'isActive' => $category->active,
            'message' => 'Status updated successfully.',
        ]);
    }
}
