<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class HomeCourseController extends Controller
{
    public function index(Request $request)
    {
        // Ambil kategori berdasarkan query pencarian
        $categories = Category::where('nama', 'like', '%' . $request->search . '%')->get();
        return view('pages.course.home_course', compact('categories'));
    }
    
}
