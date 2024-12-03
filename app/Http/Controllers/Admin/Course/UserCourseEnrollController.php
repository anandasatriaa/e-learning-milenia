<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\UserCourseEnroll;
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
            DB::beginTransaction();
            $enroll = UserCourseEnroll::where('course_id', $course_id)
                                        ->where('user_id', $user_id)
                                        ->firstOrFail();
            $enroll->delete();
    
            DB::commit();
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
