<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\DivisiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $show = $request->get('show') ?? 15;
        $query = Category::with('divisiCategory:id,nama')->latest();
        if ($search) {
            $query->where('nama', 'LIKE', "%$search%");
        }
        $data = $query->paginate($show)->withQueryString();

        return view('admin.category.category.index', compact('data', 'search', 'show'));
    }

    public function create()
    {
        $divisi = DivisiCategory::get(['id', 'nama']);
        return view('admin.category.category.create', compact('divisi'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $category = new Category();
            $category->divisi_category_id = $request->divisi_category_id;
            $category->nama = $request->nama;

            $image_parts = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $folderPath = storage_path('app/public/category/kategori/');
            $image_name =  date('YmdHi') .  '_' . $request->nama  . '.' . $image_type;
            $file = $folderPath . '' . $image_name;
            $category->image = $image_name;

            $category->deskripsi = $request->deskripsi;
            $category->active = true;
            $category->save();
            DB::commit();
            file_put_contents($file, $image_base64);

            return redirect()->route('admin.category.category.index')->with([
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
        $divisi = DivisiCategory::get(['id', 'nama']);
        $data = Category::findOrFail($id);
        return view('admin.category.category.edit', compact('data', 'divisi'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $category = Category::findOrfail($id);

            if (isset($request->updateStatus) && $request->updateStatus) {
                $category->active = $request->active;
                $category->save();
                DB::commit();

                return response()->json(['isActive' => intval($category->active)], 200);
            }
            $category->divisi_category_id = $request->divisi_category_id;
            $oldValueImageName = $category->image;

            $category->nama = $request->nama;

            if (isset($request->image)) {
                $image_parts = explode(";base64,", $request->image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $folderPath = storage_path('app/public/category/kategori/');
                $image_name =  date('YmdHi') .  '_' . $request->nama  . '.' . $image_type;
                $file = $folderPath . '' . $image_name;
                $category->image = $image_name;
            }

            $category->deskripsi = $request->deskripsi;
            $category->save();
            DB::commit();

            if (isset($request->image)) {
                file_put_contents($file, $image_base64);
                Storage::disk('public')->delete('category/kategori/' . $oldValueImageName);
            }

            return redirect()->route('admin.category.category.index')->with([
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
            $data = Category::findOrFail($id);
            $filename = $data->image;
            $data->delete();
            if ($data) {
                Storage::disk('public')->delete('category/kategori/' . $filename);
            }

            DB::commit();
            return redirect()->route('admin.category.category.index')->with([
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
