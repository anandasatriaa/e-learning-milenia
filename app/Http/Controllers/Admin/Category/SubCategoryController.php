<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $show = $request->get('show') ?? 15;
        $query = SubCategory::with('category:id,divisi_category_id,nama', 'category.divisiCategory:id,nama')->latest();
        if ($search) {
            $query->where('nama', 'LIKE', "%$search%");
        }
        $data = $query->paginate($show)->withQueryString();

        if ($request->ajax()) {
            // Jika permintaan berasal dari AJAX, kembalikan hanya tabelnya
            return view('admin.category.subcategory.index', compact('data'))->render();
        }

        return view('admin.category.subcategory.index', compact('data', 'search', 'show'));
    }


    public function create()
    {
        $category = Category::with('divisiCategory:id,nama')->get();
        return view('admin.category.subcategory.create', compact('category'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $subCategory = new SubCategory();
            $subCategory->category_id = $request->category_id;
            $subCategory->nama = $request->nama;

            $image_parts = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $folderPath = storage_path('app/public/category/subkategori/');
            $image_name =  date('YmdHi') .  '_' . $request->nama  . '.' . $image_type;
            $file = $folderPath . '' . $image_name;
            $subCategory->image = $image_name;

            $subCategory->deskripsi = $request->deskripsi;
            $subCategory->active = true;
            $subCategory->save();
            DB::commit();
            file_put_contents($file, $image_base64);

            return redirect()->route('admin.category.sub-category.index')->with([
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

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category = Category::with('divisiCategory:id,nama')->get();
        $data = SubCategory::findOrFail($id);
        return view('admin.category.subcategory.edit', compact('data', 'category'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $subCategory = SubCategory::findOrfail($id);

            if (isset($request->updateStatus) && $request->updateStatus) {
                $subCategory->active = $request->active;
                $subCategory->save();
                DB::commit();

                return response()->json(['isActive' => intval($subCategory->active)], 200);
            }
            $subCategory->category_id = $request->category_id;
            $oldValueImageName = $subCategory->image;

            $subCategory->nama = $request->nama;

            if (isset($request->image)) {
                $image_parts = explode(";base64,", $request->image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $folderPath = storage_path('app/public/category/subkategori/');
                $image_name =  date('YmdHi') .  '_' . $request->nama  . '.' . $image_type;
                $file = $folderPath . '' . $image_name;
                $subCategory->image = $image_name;
            }

            $subCategory->deskripsi = $request->deskripsi;
            $subCategory->save();
            DB::commit();

            if (isset($request->image)) {
                file_put_contents($file, $image_base64);
                Storage::disk('public')->delete('category/subkategori/' . $oldValueImageName);
            }

            return redirect()->route('admin.category.sub-category.index')->with([
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
            $data = SubCategory::findOrFail($id);
            $filename = $data->image;
            $data->delete();
            if ($data) {
                Storage::disk('public')->delete('category/subkategori/' . $filename);
            }

            DB::commit();
            return redirect()->route('admin.category.sub-category.index')->with([
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
}
