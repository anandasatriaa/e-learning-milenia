<?php

use App\Http\Controllers\Admin\Category\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\Category\DivisiCategoryController as AdminDivisiCategoryController;
use App\Http\Controllers\Admin\Category\SubCategoryController as AdminSubCategoryController;
use App\Http\Controllers\Admin\Category\LearningCategoryController as AdminLearningCategoryController;
use App\Http\Controllers\Admin\Course\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\Course\CourseModulController as AdminCourseModulController;
use App\Http\Controllers\Admin\Course\UserCourseEnrollController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\Nilai\NilaiController;
use App\Http\Controllers\Pages\Course\CourseController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\HomeCourseController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login-process', [AuthController::class, 'authenticate'])->name('login.process');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'checkAdmin'], function () {
        Route::get('/dashboard', [AdminHomeController::class, 'index']);
        Route::get('/home', [AdminHomeController::class, 'index']);
        Route::get('/', [AdminHomeController::class, 'index'])->name('dashboard');

        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::resource('learning', AdminLearningCategoryController::class);
            Route::resource('divisi-category', AdminDivisiCategoryController::class);
            Route::resource('category', AdminCategoryController::class);
            Route::resource('sub-category', AdminSubCategoryController::class);
        });

        Route::group(['prefix' => 'course', 'as' => 'course.'], function () {
            Route::get('/course', [AdminCourseController::class, 'index'])->name('course.index');
            Route::get('/course/create', [AdminCourseController::class, 'create'])->name('course.create');
            Route::post('/course/store', [AdminCourseController::class, 'store'])->name('course.store');
            Route::get('/course/{id}/edit', [AdminCourseController::class, 'edit'])->name('course.edit');
            Route::put('/course/{id}/update', [AdminCourseController::class, 'update'])->name('course.update');
            Route::delete('/course/destroy/{id}', [AdminCourseController::class, 'destroy'])->name('course.destroy');

            Route::patch('/course/update-is-active/{id}', [AdminCourseController::class, 'isActive'])->name('course.is-active');

            Route::get('/course/{course_id}/modul', [AdminCourseModulController::class, 'index'])->name('modul.index');
            Route::get('/course/{course_id}/modul/create', [AdminCourseModulController::class, 'create'])->name('modul.create');
            Route::post('/course/{course_id}/modul/store', [AdminCourseModulController::class, 'store'])->name('modul.store');
            Route::delete('/course/{course_id}/modul/destroy/{modul_id}', [AdminCourseModulController::class, 'destroy'])->name('modul.destroy');
            Route::patch('/course/{course_id}/modul/update-is-active/{modul_id}', [AdminCourseModulController::class, 'isActive'])->name('modul.is-active');
            Route::post('/course/{course_id}/modul/{modul_id}/question-import', [AdminCourseModulController::class, 'importQuizProcess'])->name('modul.question-import');
            Route::post('/course/{course_id}/modul/updateOrder', [AdminCourseModulController::class, 'updateOrder'])->name('modul.update-order');


            Route::post('/course/{course_id}/modul/{modul_id}/question-import-essay', [AdminCourseModulController::class, 'importEssayProcess'])->name('modul.question-import-essay');
            Route::post('/course/{course_id}/modul/{modul_id}/update-essay/{id}', [AdminCourseModulController::class, 'updateEssay'])->name('modul.update-essay');
            Route::delete('/course/{course_id}/modul/{modul_id}/delete-essay/{id}', [AdminCourseModulController::class, 'deleteEssay'])->name('modul.delete-essay');


            Route::post('/course/{course_id}/enroll-user', [UserCourseEnrollController::class, 'userCourseEnroll'])->name('course.enroll');
            Route::delete('/course/{course_id}/enroll-user/destroy/{user_id}', [UserCourseEnrollController::class, 'destroyUser'])->name('course.destroy-user');

            Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
            Route::get('/nilai/course_id', [NilaiController::class, 'detail'])->name('nilai.detail');
        });

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('/employee/index', [UserController::class, 'index'])->name('employee.index');
            Route::get('/employee/getAllEmployee', [UserController::class, 'datatableGetAllEmployee'])->name('datatable-getAllEmployee');
            Route::get('/employee/sync', [UserController::class, 'APIgetAllEmployee'])->name('api-sync');
        });
    });

    Route::group(['prefix' => '/', 'as' => 'pages.'], function () {
        Route::get('/dashboard', [HomeController::class, 'index']);
        Route::get('/home', [HomeController::class, 'index']);
        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
        Route::get('/course', [HomeCourseController::class, 'index'])->name('homeCourse');

        Route::group(['prefix' => 'course', 'as' => 'course.'], function () {
            Route::get('/{course_id}/', [CourseController::class, 'detailcourse'])->name('course.detail');
            Route::get('/embed-video/{course_modul_id}/', [CourseController::class, 'embedVideo'])->name('course.video');
            Route::get('/{course_id}/first-modul', [CourseController::class, 'getFirstModul']);
        
            Route::get('/quiz/{course_modul_id}', [CourseController::class, 'quiz'])->name('course.quiz');
            Route::get('/essay/{course_modul_id}', [CourseController::class, 'essay'])->name('course.essay');
            // Route::post('/{course_id}/kirim-jawaban-essay-quiz', [CourseController::class, 'kirimJawaban'])->name('course.kirimJawaban');
            // Route::post('/quiz/{modul_quiz_id}/submit', [CourseController::class, 'submitQuiz'])->name('course.submitQuiz');

            Route::post('/quiz/{course_modul_id}/submit/{user_id}',[CourseController::class, 'quiz'])->name('course.quiz');
            Route::post('/essay/{course_modul_id}/submit/{user_id}',[CourseController::class, 'essay'])->name('course.essay');
            Route::get('/getQuiz/{quiz_id}', [CourseController::class, 'getQuiz'])->name('course.getQuiz');

        });
    });
});
