<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule; // Capitalized S
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $course = $request->course;

        $schedules = Schedule::query()->when($course, function ($q) use ($course) {
            return $q->where('course_id', $course);
        })
            ->with(['course', 'instructor']) // Fetch course AND instructor data!
            ->get();

        return response()->json([
            "data" => $schedules,
            "message" => "Show schedules successfully"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Updated Validation for MongoDB and new fields
        $validate = $request->validate([
            "course_id"     => "required|string|exists:courses,_id", // String for MongoDB ID
            "instructor_id" => "nullable|string|exists:instructors,_id",
            "group_name"    => "required|string|max:100",
            "room"          => "nullable|string|max:100",
            "shift"         => "required|string|max:100",
            "start_date"    => "required|date",
            "end_date"      => "required|date|after:start_date",
            "days_of_week"  => "required|string|max:100", // Array for React checkboxes
            "start_time"    => "required|date_format:H:i",
            "end_time"      => "required|date_format:H:i|after:start_time",
            "status"        => "required|boolean",
        ]);

        // Ensure status is a strict boolean

        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;


        try {
            $schedule = Schedule::create($validate);

            return response()->json([
                "message" => "Created successfully",
                "data"    => $schedule
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Failed to create record",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Load relationships when showing a single schedule
        $schedule = Schedule::with(['course', 'instructor'])->find($id);

        if (!$schedule) {
            return response()->json([
                "message" => "Schedule not found"
            ], 404);
        }

        return response()->json([
            "data" => $schedule,
            "message" => "Get one schedule successfully."
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                "message" => "Schedule not found"
            ], 404);
        }

        $validate = $request->validate([
            "course_id"     => "required|string|exists:courses,_id",
            "instructor_id" => "nullable|string|exists:instructors,_id",
            "group_name"    => "required|string|max:100",
            "room"          => "nullable|string|max:100",
            "shift"         => "required|string|max:100",
            "start_date"    => "required|date",
            "end_date"      => "required|date|after:start_date",
            "days_of_week"  => "required|string|max:100",
            "start_time"    => "required|date_format:H:i",
            "end_time"      => "required|date_format:H:i|after:start_time",
            "status"        => "required|boolean",
        ]);

        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;


        try {
            $schedule->update($validate);

            return response()->json([
                "message" => "Update successfully",
                "data"    => $schedule
            ], 200); // Changed to 200 (Standard for successful update)
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Failed to update record",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                "message" => "Schedule not found",
            ], 404);
        }

        $schedule->delete();

        return response()->json([
            "data" => $schedule,
            "message" => "Deleted successfully", // Fixed typo in message
        ], 200);
    }
}
