<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Employee as ModelsEmployee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $employee = Employee::query()->get();

        return response()->json([
            "data" => $employee,
            "message" => "Get Employee successfully"
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender'    => 'required|string|max:255',
            // ✅ Added table and column for unique check
            'email'     => 'required|email|unique:employees,email|max:255',
            // ✅ Removed the |email rule from birthday and address
            'birthday'  => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'position'  => 'nullable|string|max:255',
            'status'    => 'required|boolean',
        ]);

        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;


        $employee = new Employee();
        $employee->fill($validate);
        $employee->save();

        return response()->json([
            "data" => $employee,
            "message" => "Create Employee Successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                "message" => "employee not found"

            ]);
        }
        return response()->json([
            "data" => $employee,
            "message" => "Get one employee successfully."
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        //
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                "message" => "employee not found ."
            ]);
        }
        $validate = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender'    => 'required|string|max:255',
            // ✅ Added table and column for unique check
            // ✅ THE FIX: Ignore the current ID during the unique check
            // It says: "Must be unique in 'employees' table 'email' column, EXCEPT for this ID"
            'email'     => 'required|email|max:255|unique:employees,email,' . $id . ',_id',
            // ✅ Removed the |email rule from birthday and address
            'birthday'  => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'position'  => 'nullable|string|max:255',
            'status'    => 'required|boolean',
        ]);


        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;


        $employee->update($validate);

        return response()->json([
            "data" => $employee,
            "message" => "Update employee Successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                "message" => "employee not found"
            ], 404);
        }
        $employee->delete();

        return response()->json([
            "data" => $employee,
            "message" => "Delete employee successfully"
        ]);
    }
}
