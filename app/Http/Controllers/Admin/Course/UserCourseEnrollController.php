<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\UserCourseEnroll;
use App\Models\Course\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCourseEnrollController extends Controller
{
    public function userCourseEnroll(Request $request, $course_id)
    {
        try {
            DB::beginTransaction();
            foreach ($request->user_id as $value) {
                $data = new UserCourseEnroll();
                $data->course_id = $course_id;
                $data->user_id = $value;
                $data->enroll_date = now();
                $data->save();
            }
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroyUser($course_id, $user_id)
    {
        try {
            $course = Course::findOrFail($course_id);
            $user = User::findOrFail($user_id);

            // Hapus user dari course (misalnya melalui pivot table)
            $course->user()->detach($user_id);
            return redirect()->route('admin.course.modul.index', $course_id)->with([
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

    public function userCourseRollout(Request $request, $course_id)
    {
        try {
            DB::beginTransaction();
            foreach ($request->user_id as $value) {
                $data = new UserCourseEnroll();
                $data->course_id = $course_id;
                $data->user_id = $value;
                $data->enroll_date = now();
                $data->save();
            }
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
