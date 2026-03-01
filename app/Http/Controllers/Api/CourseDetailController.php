<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseDetail;
use Illuminate\Http\Request;

class CourseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $course = $request->course;
        $schedule = $request->schedule;

        $courseDetail = CourseDetail::query()
            ->when($course, function ($query) use ($course) {
                return $query->where("course_id", $course);
            })
            ->when($schedule, function ($query) use ($schedule) {
                return $query->where("schedule_id", $schedule);
            })
            ->with(['course', 'schedule'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            "data" => $courseDetail->load(['course', 'schedule']),
            "message"  => "Get Course Detail"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'course_id' => 'required|string|exists:courses,_id',
            'schedule_id' => 'required|string|exists:schedules,_id',

            'curriculum'   => 'required|array',  // Forces it to be an array
            'curriculum.*' => 'required|string',
        ]);
        $courseDetail = CourseDetail::create($validate);

        return response()->json([
            "data" => $courseDetail->load(['course', 'schedule']),
            "messgae" => "Create course details successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $courseDetail = CourseDetail::find($id);

        if (!$courseDetail) {
            return response()->json([
                "message" => "Not Found"
            ]);
        }

        return response()->json([
            "data" => $courseDetail->load(['course', 'schedule']),
            "message" => "Get one successfully"
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $courseDetail = CourseDetail::find($id);

        if (!$courseDetail) {
            return response()->json([
                "message" => "Not Found"
            ]);
        }
        $validate = $request->validate([
            'course_id' => 'required|string|exists:courses,_id',
            'schedule_id' => 'required|string|exists:schedules,_id',

            'curriculum'   => 'required|array',  // Forces it to be an array
            'curriculum.*' => 'required|string',
        ]);
        $courseDetail->update($validate);

        return response()->json([
            "data" => $courseDetail->load(['course', 'schedule']),
            "messgae" => "Update course details successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $courseDetail = CourseDetail::find($id);

        if (!$courseDetail) {
            return response()->json([
                "message" => "Not Found"
            ],404);
        }
        $courseDetail->delete();

        return response()->json([
            "data" => $courseDetail->load(['course', 'schedule']),
            "message" => "Delete Course detail successfully"
        ]);
    }
}
