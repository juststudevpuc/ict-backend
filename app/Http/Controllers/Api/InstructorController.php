<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $instructor = Instructor::query()->get();

        return response()->json([
            "data" => $instructor,
            "message" => "Get instructor successfully"
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|string|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'status'         => 'required|boolean',
        ]);

        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;


        $instructor = Instructor::create($validate);

        return response()->json([
            "data" => $instructor,
            "message" => "Create instructor Successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $instructor = Instructor::find($id);

        if (!$instructor) {
            return response()->json([
                "message" => "Instructor not found"

            ]);
        }
        return response()->json([
            "data" => $instructor,
            "message" => "Get one instructor successfully. "
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        //
        $instructor = Instructor::find($id);

        if (!$instructor) {
            return response()->json([
                "message" => "Instructor not found ."
            ]);
        }
        $validate = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|string|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'status'         => 'required|boolean',
        ]);


        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;


        $instructor->update($validate);

        return response()->json([
            "data" => $instructor,
            "message" => "Update instructor Successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $instructor = Instructor::find($id);

        if(!$instructor) {
            return response()->json([
                "message" => "Instructor not found"
            ],404);

        }
        $instructor->delete();

        return response()->json([
            "data" => $instructor,
            "message"=> "Delete instructor successfully"
        ]);
    }
}
