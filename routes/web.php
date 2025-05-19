<?php

use App\Http\Controllers\Admin\Category\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\Category\DivisiCategoryController as AdminDivisiCategoryController;
use App\Http\Controllers\Admin\Category\SubCategoryController as AdminSubCategoryController;
use App\Http\Controllers\Admin\Category\LearningCategoryController as AdminLearningCategoryController;
use App\Http\Controllers\Admin\Course\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\Course\CourseModulController as AdminCourseModulController;
use App\Http\Controllers\Admin\Course\UserCourseEnrollController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\MatriksKompetensiController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\Nilai\NilaiController;
use App\Http\Controllers\Admin\Nilai\NilaiMatriksController;
use App\Http\Controllers\Admin\Calendar\CalendarController;
use App\Http\Controllers\Pages\Course\CourseController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\HomeCourseController;
use App\Http\Controllers\Pages\Preview\PreviewNilaiController;
use Illuminate\Support\Facades\Route;

Route::get('/landing-page', [AuthController::class, 'landingPage'])->name('landing.page');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login-process', [AuthController::class, 'authenticate'])->name('login.process');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'checkAdmin'], function () {
        Route::get('/dashboard', [AdminHomeController::class, 'index']);
        Route::get('/home', [AdminHomeController::class, 'index']);
        Route::get('/', [AdminHomeController::class, 'index'])->name('dashboard');
        Route::get('/get-chart-data', [AdminHomeController::class, 'getChartData']);
        Route::get('/dashboard-matriks-kompetensi', [MatriksKompetensiController::class, 'index'])->name('matriks-kompetensi');
        Route::get('/dashboard-matriks-kompetensi/{divisi_id}', [MatriksKompetensiController::class, 'detail'])->name('matriks-kompetensi-detail');
        Route::post('/dashboard-matriks-kompetensi/store', [MatriksKompetensiController::class, 'store'])->name('matriks-kompetensi-store');
        Route::put('/dashboard-matriks-kompetensi/update/{id}', [MatriksKompetensiController::class, 'update'])->name('matriks-kompetensi-update');

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
            Route::get('/nilai/{course_id}', [NilaiController::class, 'detail'])->name('nilai.detail');
            Route::get('/get-review-data/{course_id}/{user_id}', [NilaiController::class, 'showReviewModal'])->name('nilai.showReviewModal');
            Route::post('/nilai/store', [NilaiController::class, 'store'])->name('nilai.store');
            Route::put('/nilai/update/{course_id}/{user_id}', [NilaiController::class, 'updateReview'])->name('nilai.updateReview');

            Route::get('/nilai-matriks', [NilaiMatriksController::class, 'index'])->name('nilai-matriks.index');
            Route::get('/nilai-matriks/{course_id}', [NilaiMatriksController::class, 'detail'])->name('nilai-matriks.detail');
            Route::get('/get-review-data-matriks/{course_id}/{user_id}', [NilaiMatriksController::class, 'showReviewModal'])->name('nilai-matriks.showReviewModal');
            Route::post('/nilai-matriks/store', [NilaiMatriksController::class, 'store'])->name('nilai.store');
            Route::put('/nilai-matriks/update/{course_id}/{user_id}', [NilaiMatriksController::class, 'updateReview'])->name('nilai-matriks.updateReview');
        });

        Route::group(['prefix' => 'calendar', 'as' => 'calendar.'], function () {
            Route::get('/course-schedule', [CalendarController::class, 'index'])->name('calendar.index');
            Route::get('/course-schedule/data', [CalendarController::class, 'data'])->name('calendar.data');
            Route::get('/course-schedule/export', [CalendarController::class, 'export'])->name('calendar.export');
            Route::post('/course-schedule/calendar', [CalendarController::class, 'store'])->name('calendar.store');
            Route::delete('/course-schedule/calendar/destroy/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
        });

        Route::group(['prefix' => 'preview', 'as' => 'preview.'], function () {
            Route::get('/preview-nilai', [NilaiController::class, 'previewNilai'])->name('preview-nilai');
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
        Route::get('/course-learning', [HomeCourseController::class, 'index'])->name('homeCourse');
        Route::get('/course-learning/{learning_id}', [HomeCourseController::class, 'subCourse'])->name('subCourse');

        Route::get('/preview-nilai', [PreviewNilaiController::class, 'index'])->name('preview-nilai');

        Route::group(['prefix' => 'course', 'as' => 'course.'], function () {
            Route::get('/{course_id}', [CourseController::class, 'detailcourse'])->name('course.detail');
            Route::get('/embed-video/{course_modul_id}/', [CourseController::class, 'embedVideo'])->name('course.video');
            Route::get('/{course_id}/first-modul', [CourseController::class, 'getFirstModul']);
        
            Route::get('/quiz/{course_modul_id}', [CourseController::class, 'quiz'])->name('course.quiz');
            Route::get('/essay/{course_modul_id}', [CourseController::class, 'essay'])->name('course.essay');

            Route::post('/quiz/{course_modul_id}/submit/{user_id}',[CourseController::class, 'submitQuiz'])->name('course.submitQuiz');
            Route::post('/essay/{course_modul_id}/submit/{user_id}',[CourseController::class, 'submitEssay'])->name('course.submitEssay');
            Route::post('/update-course-enrolls',[CourseController::class, 'updateCourseEnrollSummary'])->name('course.updateCourseEnrollSummary');
            Route::get('/course/{course}/review', [CourseController::class, 'review'])->name('course.review');
            Route::get('/getQuiz/{quiz_id}', [CourseController::class, 'getQuiz'])->name('course.getQuiz');
            Route::get('/get-time-spend-and-progress-bar/{course_id}/{user_id}', [CourseController::class, 'getTimeandProgress'])->name('course.getTimeandProgress');
            Route::post('/post-time-spend-and-progress-bar', [CourseController::class, 'postTimeandProgress'])->name('course.postTimeandProgress');
        });
    });
});
