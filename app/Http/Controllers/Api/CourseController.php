<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\course;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query("q");

        $course = course::where("title", "like", "%" . $query . "%")->get();

        return response()->json([
            "Query"   => $query,
            "data"    => $course,
            "message" => "Search Course successfully"
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $course = course::query()->get();

        return response()->json(
            [
                "data" => $course,
                "message" => "get course successfully"
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            "title" => "required|string|max:225|min:3",
            "description" => "required|string|max:225|min:3",
            "duration_hours" => "required|string|max:225",
            "price" => "required|integer|min:0",
            "status" => "required|boolean",
            "image" => "nullable|file|max:2048"

        ]);
        $validate["status"] === "1" ? $validate["status"] = true : $validate["status"] = false;

        // if ($request->hasFile("image")) {
        //     $validate["image"] = $request->file("image")->store("courses", "public");
        // }
        if ($request->hasFile("image")) {
            $upload = Cloudinary::uploadApi()->upload(
                $validate["image"]->getRealPath(),
                ["folder" => config("cloudinary.upload_present", "ict_img")]
            );
            $validate["image_url"] = $upload["secure_url"];
            $validate["image_public_id"] = $upload["public_id"];
        }

        $course = new course();
        $course->fill($validate);
        $course->save();
        return [
            "data" => $course,
            "message" => "Create course Successfully",
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 1. 🛠️ THE MAGIC: Eager load the relationships from your Model!
        $course = Course::with(['schedules', 'courseDetail'])->find($id);

        if (!$course) {
            return response()->json([
                "message" => "Course not found"
            ], 404);
        }

        // 2. 🛠️ FORMAT THE DATA: Match the exact JSON structure your React page needs
        $formattedData = [
            [
                "course" => $course,
                // If there are schedules, grab the first one. Otherwise return null.
                "schedule" => $course->schedules->first(),
                // If there is a course detail, grab the curriculum array. Otherwise return empty array.
                "curriculum" => $course->courseDetail ? $course->courseDetail->curriculum : []
            ]
        ];

        return response()->json([
            "data" => $formattedData,
            "message" => "Get one course successfully"
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $course = course::find($id);

        if (!$course) {
            return [
                "message" => "Course not found"
            ];
        }
        $validate = $request->validate([
            "title" => "required|string|max:225|min:3",
            "description" => "required|string|max:225|min:3",
            "duration_hours" => "required|string|max:225",
            "price" => "required|integer|min:0",
            "status" => "required|boolean",
            "image" => "nullable|file|max:2048"

        ]);
        $validate["status"] === "1" ? $validate["status"] = true : $validate["status"] = false;

        // if ($request->hasFile("image")) {
        //     if ($course->image && Storage::disk("public")->exists($course->image)) {
        //         Storage::disk("public")->delete($course->image);
        //     }
        //     // create new image
        //     $validate["image"]  = $request->file("image")->store("courses", "public");
        // }

        if ($request->hasFile("image")) {
            if (!empty($course->image_public_id)) {
                Cloudinary::uploadApi()->destroy($course->image_public_id);
            }

            $upload = Cloudinary::uploadApi()->upload(
                $validate["image"]->getRealPath(),
                ["folder" => config("cloudinary.upload_present", "ict_img")]
            );
            $validate["image_url"] = $upload["secure_url"];
            $validate["image_public_id"] = $upload["public_id"];
        }
        $course->fill($validate);
        $course->save();
        return [
            "data" => $course,
            "message" => "Create course Successfully",
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $course = course::find($id);

        if (!$course) {
            return [
                "message" => "course not found "
            ];
        }
        // if ($course->image && Storage::disk("public")->exists($course->image)) {
        //     Storage::disk("public")->delete($course->image);
        // }

        if (!empty($course->image_public_id)) {
            Cloudinary::uploadApi()->destroy($course->image_public_id);
        }

        $course->delete();
        return [
            "data" => $course,
            "message" => " Delete course successfully"
        ];
    }
}
