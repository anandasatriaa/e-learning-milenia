<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\DivisiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DivisiCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $show = $request->get('show') ?? 15;
        $query = DivisiCategory::latest();
        if ($search) {
            $query->where('nama', 'LIKE', "%$search%");
        }
        $data = $query->paginate($show)->withQueryString();

        if ($request->ajax()) {
            // Jika permintaan berasal dari AJAX, kembalikan hanya tabelnya
            return view('admin.category.divisi.index', compact('data'))->render();
        }

        return view('admin.category.divisi.index', compact('data', 'search', 'show'));
    }

    public function create()
    {
        return view('admin.category.divisi.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $divisiCategory = new DivisiCategory();
            $divisiCategory->nama = $request->nama;

            $image_parts = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $folderPath = storage_path('app/public/category/divisi/');
            $image_name =  date('YmdHi') .  '_' . $request->nama  . '.' . $image_type;
            $file = $folderPath . '' . $image_name;
            $divisiCategory->image = $image_name;

            $divisiCategory->deskripsi = $request->deskripsi;
            $divisiCategory->active = true;
            $divisiCategory->save();
            DB::commit();
            file_put_contents($file, $image_base64);

            return redirect()->route('admin.category.divisi-category.index')->with([
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
        $data = DivisiCategory::findOrFail($id);
        return view('admin.category.divisi.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $divisiCategory = DivisiCategory::findOrfail($id);

            if (isset($request->updateStatus) && $request->updateStatus) {
                $divisiCategory->active = $request->active;
                $divisiCategory->save();
                DB::commit();

                return response()->json(['isActive' => intval($divisiCategory->active)], 200);
            }
            $oldValueImageName = $divisiCategory->image;

            $divisiCategory->nama = $request->nama;

            if (isset($request->image)) {
                $image_parts = explode(";base64,", $request->image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $folderPath = storage_path('app/public/category/divisi/');
                $image_name =  date('YmdHi') .  '_' . $request->nama  . '.' . $image_type;
                $file = $folderPath . '' . $image_name;
                $divisiCategory->image = $image_name;
            }

            $divisiCategory->deskripsi = $request->deskripsi;
            $divisiCategory->save();
            DB::commit();

            if (isset($request->image)) {
                file_put_contents($file, $image_base64);
                Storage::disk('public')->delete('category/divisi/' . $oldValueImageName);
            }

            return redirect()->route('admin.category.divisi-category.index')->with([
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
            $data = DivisiCategory::findOrFail($id);
            $filename = $data->image;
            $data->delete();
            if ($data) {
                Storage::disk('public')->delete('category/divisi/' . $filename);
            }

            DB::commit();
            return redirect()->route('admin.category.divisi-category.index')->with([
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
