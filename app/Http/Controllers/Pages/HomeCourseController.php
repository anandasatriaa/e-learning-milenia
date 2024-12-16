<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Category\LearningCategory;
use App\Models\Course\Course;
use Illuminate\Http\Request;

class HomeCourseController extends Controller
{
    public function index(Request $request)
    {
        // Ambil kategori berdasarkan query pencarian
        $learnings = LearningCategory::where('nama', 'like', '%' . $request->search . '%')->get();
        return view('pages.course.home_course', compact('learnings'));
    }

    public function subCourse($learning_id)
    {
        // Ambil LearningCategory berdasarkan ID
        $learning = LearningCategory::find($learning_id);

        if (!$learning) {
            abort(404, 'Learning Category not found');
        }

        // Ambil semua courses dengan relasi dan filter berdasarkan learning_id
        $courses = Course::with('subCategory.category.divisiCategory.learningCategory')
        ->whereHas('subCategory.category.divisiCategory.learningCategory', function ($query) use ($learning_id) {
            $query->where('id', $learning_id);
        })
            ->get();


        // Bangun struktur hierarki
        $groupedCourses = [];

        foreach ($courses as $course) {
            $learningTitle = $course->subCategory->category->divisiCategory->learningCategory->nama;
            $divisiTitle = $course->subCategory->category->divisiCategory->nama;
            $categoryTitle = $course->subCategory->category->nama;
            $subCategoryTitle = $course->subCategory->nama;

            // Inisialisasi Level Learning
            if (!isset($groupedCourses[$learningTitle])) {
                $groupedCourses[$learningTitle] = [
                    'title' => $learningTitle,
                    'children' => []
                ];
            }

            // Inisialisasi Level Divisi
            if (!isset($groupedCourses[$learningTitle]['children'][$divisiTitle])) {
                $groupedCourses[$learningTitle]['children'][$divisiTitle] = [
                    'title' => $divisiTitle,
                    'children' => []
                ];
            }

            // Inisialisasi Level Category
            if (!isset($groupedCourses[$learningTitle]['children'][$divisiTitle]['children'][$categoryTitle])) {
                $groupedCourses[$learningTitle]['children'][$divisiTitle]['children'][$categoryTitle] = [
                    'title' => $categoryTitle,
                    'children' => []
                ];
            }

            // Inisialisasi Level SubCategory dan Tambahkan Courses
            $groupedCourses[$learningTitle]['children'][$divisiTitle]['children'][$categoryTitle]['children'][$subCategoryTitle]['title'] = $subCategoryTitle;
            $groupedCourses[$learningTitle]['children'][$divisiTitle]['children'][$categoryTitle]['children'][$subCategoryTitle]['courses'][] = [
                'name' => $course->nama_kelas,
                'progress' => $course->progress,
                'thumbnail' => $course->thumbnail // Tambahkan thumbnail ke dalam array
            ];
        }

        // Ubah ke array indexed untuk view
        $groupedCourses = array_values($this->formatTree($groupedCourses));

        return view('pages.course.sub_course', compact('learning', 'groupedCourses'));
    }


    // Fungsi Rekursif untuk Formatting
    private function formatTree($array)
    {
        $result = [];
        foreach ($array as $item) {
            $children = isset($item['children']) ? $this->formatTree($item['children']) : [];
            $result[] = [
                'title' => $item['title'],
                'children' => $children,
                'courses' => $item['courses'] ?? null
            ];
        }
        return $result;
    }


}
