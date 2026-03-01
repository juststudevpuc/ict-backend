<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query("q");

        $student = Student::where("full_name", "like", "%" . $query . "%")->get();

        return response()->json([
            "Query"   => $query,
            "data"    => $student,
            "message" => "Search Course successfully"
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $student = Student::query()->get();

        return response()->json([
            "data" => $student,
            "message" => "Get Student successfully. "
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            "full_name"     => "required|string|max:255",
            "gender"     => "required|string|max:255",
            "phone"     => "required|string|max:255",
            "email"     => "nullable|email|max:255",
            "date_of_birth"     => "nullable|string",
            "address"     => "nullable|string",
            "status" => "required|boolean"
        ]);
        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;

        try {
            $student = Student::create($validate);

            return response()->json([
                "message" => "Created successfully",
                "data"    => $student
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
        //
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                "message" => "Student not found"
            ]);
        }
        return response()->json([
            "data" => $student,
            "message " => "Get one student successfully"
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                "message" => "Student not found"
            ]);
        }

        $validate = $request->validate([
            "full_name"     => "required|string|max:255",
            "gender"     => "required|string|max:255",
            "phone"     => "required|string|max:255",
            "email"     => "nullable|email|max:255",
            "date_of_birth"     => "nullable|string",
            "address"     => "nullable|string",
            "status" => "required|boolean"
        ]);
        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;

        try {
            $student->update($validate);

            return response()->json([
                "message" => "Updated successfully",
                "data"    => $student
            ], 200);
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
        //
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                "message" => "Student not found"
            ]);
        }

        $student->delete();

        return response()->json([
            "data" => $student,
            "message" => "Deleted successfully"
        ]);
    }
}
