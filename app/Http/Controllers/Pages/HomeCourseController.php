<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Category\LearningCategory;
use App\Models\Course\Course;
use App\Models\UserCourseEnroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Ambil progress dari tabel UserCourseEnroll untuk user yang sedang login
        $userId = Auth::id();
        $userProgress = UserCourseEnroll::where('user_id',
            $userId
        )
        ->pluck('progress_bar', 'course_id') // Mengambil progress untuk menggabungkan nanti
            ->toArray();

        // Ambil courses yang sudah di-enroll oleh user untuk learning category tertentu
        $enrolledCourseIds = UserCourseEnroll::where('user_id', $userId)
        ->pluck('course_id')
        ->toArray();

        // Ambil semua courses dengan relasi yang diperlukan
        $courses = Course::with([
            'learningCategory',
            'divisiCategory.learningCategory',
            'category.divisiCategory.learningCategory',
            'subCategory.category.divisiCategory.learningCategory'
        ])
            ->where('learning_cat_id', $learning_id)
            ->whereIn('id', $enrolledCourseIds)
            ->get();

        $courseIds = $courses->pluck('id')->toArray();

        // Bangun struktur hierarki dinamis
        $groupedCourses = [];
        $addedCourses = []; // Array untuk melacak ID course yang sudah ditambahkan

        foreach ($courses as $course) {
            $progress = $userProgress[$course->id] ?? 0;
            $course->setAttribute('progress', $progress);
            // Level 1: LearningCategory
            // Kursus hanya memiliki LearningCategory
            if ($course->learningCategory) {
                $learningCategoryName = $course->learningCategory->nama;
                if (!isset($groupedCourses[$learningCategoryName])) {
                    $groupedCourses[$learningCategoryName] = [
                        'title' => $learningCategoryName,
                        'courses' => [],
                        'children' => [] // Menampung sub-level
                    ];
                }

                if ($course->learningCategory && !$course->divisiCategory && !$course->category && !$course->subCategory) {
                    // Tambahkan course ke LearningCategory jika belum ada
                    if (!isset($addedCourses[$course->id])) {
                        $groupedCourses[$learningCategoryName]['courses'][] = [
                            'id' => $course->id,
                            'name' => $course->nama_kelas,
                            'thumbnail' => $course->thumbnail,
                            'progress' => $course->progress,
                        ];
                        $addedCourses[$course->id] = true; // Tandai course sudah ditambahkan
                    }
                }
            }

            if ($course->learningCategory && $course->divisiCategory) {
                // Kursus memiliki LearningCategory dan DivisionCategory
                $learningCategoryName = $course->learningCategory->nama;
                $divisionName = $course->divisiCategory->nama;
                if (!isset($groupedCourses[$learningCategoryName]['children'][$divisionName])) {
                    $groupedCourses[$learningCategoryName]['children'][$divisionName] = [
                        'title' => $divisionName,
                        'courses' => [],
                        'children' => [] // Menampung category dan subCategory
                    ];
                }

                // Level 2: DivisionCategory
                if ($course->learningCategory && $course->divisiCategory && !$course->category && !$course->subCategory) {
                    // Tambahkan course ke DivisionCategory jika belum ada
                    if (!isset($addedCourses[$course->id])) {
                        $groupedCourses[$learningCategoryName]['children'][$divisionName]['courses'][] = [
                            'id' => $course->id,
                            'name' => $course->nama_kelas,
                            'thumbnail' => $course->thumbnail,
                            'progress' => $course->progress,
                        ];
                        $addedCourses[$course->id] = true; // Tandai course sudah ditambahkan
                    }
                }
            }

            if ($course->learningCategory && $course->divisiCategory && $course->category) {
                // Kursus memiliki LearningCategory, DivisionCategory, dan Category
                $learningCategoryName = $course->learningCategory->nama;
                $divisionName = $course->divisiCategory->nama;
                $categoryName = $course->category->nama;
                if (!isset($groupedCourses[$learningCategoryName]['children'][$divisionName]['children'][$categoryName])) {
                    $groupedCourses[$learningCategoryName]['children'][$divisionName]['children'][$categoryName] = [
                        'title' => $categoryName,
                        'courses' => [],
                        'children' => [] // Menampung subCategory
                    ];
                }

                // Level 3: Category
                if ($course->learningCategory && $course->divisiCategory && $course->category && !$course->subCategory) {
                    // Tambahkan course ke Category jika belum ada
                    if (!isset($addedCourses[$course->id])) {
                        $groupedCourses[$learningCategoryName]['children'][$divisionName]['children'][$categoryName]['courses'][] = [
                            'id' => $course->id,
                            'name' => $course->nama_kelas,
                            'thumbnail' => $course->thumbnail,
                            'progress' => $course->progress,
                        ];
                        $addedCourses[$course->id] = true; // Tandai course sudah ditambahkan
                    }
                }
            }

            if ($course->learningCategory && $course->divisiCategory && $course->category && $course->subCategory) {
                // Kursus memiliki LearningCategory, DivisionCategory, Category, dan SubCategory
                $learningCategoryName = $course->learningCategory->nama;
                $divisionName = $course->divisiCategory->nama;
                $categoryName = $course->category->nama;
                $subCategoryName = $course->subCategory->nama;
                if (!isset($groupedCourses[$learningCategoryName]['children'][$divisionName]['children'][$categoryName]['children'][$subCategoryName])) {
                    $groupedCourses[$learningCategoryName]['children'][$divisionName]['children'][$categoryName]['children'][$subCategoryName] = [
                        'title' => $subCategoryName,
                        'courses' => []
                    ];
                }

                // Level 4: SubCategory
                if ($course->learningCategory && $course->divisiCategory && $course->category && $course->subCategory) {
                    // Tambahkan course ke SubCategory jika belum ada
                    if (!isset($addedCourses[$course->id])) {
                        $groupedCourses[$learningCategoryName]['children'][$divisionName]['children'][$categoryName]['children'][$subCategoryName]['courses'][] = [
                            'id' => $course->id,
                            'name' => $course->nama_kelas,
                            'thumbnail' => $course->thumbnail,
                            'progress' => $course->progress,
                        ];
                        $addedCourses[$course->id] = true; // Tandai course sudah ditambahkan
                    }
                }
            }
        }

        // Ubah menjadi indexed array
        $groupedCourses = array_values($groupedCourses);
        foreach ($groupedCourses as &$group) {
            foreach ($group['children'] as &$child) {
                foreach ($child['children'] as &$subChild) {
                    $subChild['children'] = array_values($subChild['children']);
                }
                $child['children'] = array_values($child['children']);
            }
        }
        // dd($group);

        return view('pages.course.sub_course', compact('learning','groupedCourses', 'courseIds'));
    }
}
