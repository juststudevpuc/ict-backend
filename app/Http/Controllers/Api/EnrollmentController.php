<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    // public function search(Request $request)
    // {
    //     $query = $request->query("q");

    //     if (!$query) {
    //         return response()->json(["data" => [], "message" => "No search term provided"]);
    //     }

    //     // 1. Force retrieval of the Mongo _id field specifically
    //     $studentIds = \App\Models\Student::where('full_name', 'like', '%' . $query . '%')
    //         ->get(['_id']) // Fetch the actual _id column
    //         ->pluck('_id')
    //         ->map(fn($id) => (string) $id) // Convert BSON Object to String
    //         ->toArray();

    //     if (empty($studentIds)) {
    //         return response()->json(["Query" => $query, "data" => [], "message" => "No students found"]);
    //     }

    //     // 2. Search enrollments using the string versions of those IDs
    //     $enrollment = \App\Models\Enrollment::whereIn('student_id', $studentIds)
    //         ->with(['student', 'course', 'schedule'])
    //         ->get();

    //     return response()->json([
    //         "Query" => $query,
    //         "found_student_ids" => $studentIds, // This should now show real ID strings
    //         "data" => $enrollment,
    //         "message" => "Search completed"
    //     ]);
    // }


    /**
     * Display a listing of the resource. for search and filter data
     */
    // public function index(Request $request)
    // {


    //     $queryStr = $request->get('q');
    //     $sortBy = $request->get('sort_by', 'created_at');
    //     $sortOrder = $request->get('sort_order', 'desc');
    //     $status = $request->status;
    //     $paymentStatus = $request->payment_status;

    //     $enrollmentQuery = Enrollment::query();

    //     // 1. INTEGRATED SEARCH LOGIC (From your search method)
    //     if ($queryStr) {
    //         $studentIds = \App\Models\Student::where('full_name', 'like', '%' . $queryStr . '%')
    //             ->get(['_id'])
    //             ->pluck('_id')
    //             ->map(fn($id) => (string) $id)
    //             ->toArray();

    //         $enrollmentQuery->whereIn('student_id', $studentIds);
    //     }

    //     // 2. FILTERS
    //     // Change $request->course to $request->course_id
    //     $enrollmentQuery->when($request->course_id, function ($q) use ($request) {
    //         return $q->where("course_id", $request->course_id);
    //     })
    //         ->when($request->schedule, function ($q) use ($request) {
    //             return $q->where("schedule_id", $request->schedule);
    //         })
    //         ->when($paymentStatus, function ($q) use ($paymentStatus) {
    //             return $q->where("payment_status", $paymentStatus);
    //         })
    //         ->when($status !== null, function ($q) use ($status) {
    //             return $q->where("status", (bool)$status);
    //         });

    //     // 3. SORT & RELATIONSHIPS
    //     $enrollment = $enrollmentQuery->with(['course', 'student', 'schedule'])
    //         ->orderBy($sortBy, $sortOrder)
    //         ->get();

    //     return response()->json([
    //         "data" => $enrollment,
    //         "message" => "Data retrieved successfully",
    //         "debug_query" => $queryStr // Helpful for testing
    //     ]);
    // }
    public function index(Request $request)
    {
        $queryStr = $request->get('q');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $status = $request->status;
        $paymentStatus = $request->payment_status;
        $month = $request->month;
        $year = $request->year;

        $enrollmentQuery = Enrollment::query();

        // 1. Search Logic
        if ($queryStr) {
            // 1. Find students matching the search (Case-insensitive)
            $students = \App\Models\Student::where('full_name', 'regexp', '/.*' . $queryStr . '.*/i')->get();

            if ($students->isEmpty()) {
                // If no student matches the search name, force the table to return 0 results
                $enrollmentQuery->where('_id', null);
            } else {
                // 2. Extract IDs safely as STRINGS
                $studentIds = $students->map(function ($student) {
                    // Using _id explicitly and casting to string to match your database exactly
                    return (string) $student->_id;
                })->toArray();

                // 3. Filter enrollments (String matching String)
                $enrollmentQuery->whereIn('student_id', $studentIds);
            }
        }

        // 2. Filters
        $enrollmentQuery->when($request->course_id, function ($q) use ($request) {
            return $q->where("course_id", $request->course_id);
        })
            ->when($month, fn($q) => $q->whereMonth("created_at", $month))
            ->when($year, fn($q) => $q->whereYear("created_at", $year))
            ->when($request->schedule, function ($q) use ($request) {
                return $q->where("schedule_id", $request->schedule);
            })
            ->when($paymentStatus, function ($q) use ($paymentStatus) {
                return $q->where("payment_status", $paymentStatus);
            })
            ->when($status !== null, function ($q) use ($status) {
                return $q->where("status", (bool)$status);
            });

        // 3. Financial Summary (The "Safety First" approach)
        // We fetch the collection once to handle the string-to-float conversion safely
        $allFiltered = $enrollmentQuery->get();

        $stats = [
            'total_expected' => (float) $allFiltered->sum(function ($item) {
                // Calculate the real price after the % discount is removed
                $discountAmount = ($item->total_price * $item->discount) / 100;
                return $item->total_price - $discountAmount;
            }),
            'total_paid' => (float) $allFiltered->sum(fn($item) => (float)$item->paid_amount),
        ];

        // Now Debt is simply what is left over from the discounted price
        $stats['total_debt'] = $stats['total_expected'] - $stats['total_paid'];

        // 4. Final Data with Relationships
        // We sort the collection we already fetched to avoid a second database hit
        $enrollment = $allFiltered->load(['course', 'student', 'schedule'])
            ->sortBy([[$sortBy, $sortOrder]]);

        return response()->json([
            "stats" => $stats,
            "data" => $enrollment->values(), // values() resets array keys after sorting
            "message" => "Data retrieved successfully"
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'student_id' => 'required|string|exists:students,_id',
            'course_id' => 'required|string|exists:courses,_id',
            'schedule_id' => 'required|string|exists:schedules,_id',
            'enrollment_date' => 'nullable|date',

            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|string',

            'status' => 'required|boolean'
        ]);

        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;

        $validate['total_price'] = (float) $request->total_price;
        $validate['paid_amount'] = (float) $request->paid_amount;
        $validate['discount']    = (float) ($request->discount ?? 0);
        $enrollment = Enrollment::create($validate);

        return response()->json([
            "data" => $enrollment->load(['student', 'course', 'schedule']),
            "message" => "Create Enrollment successfully"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                "message" => "Not found"

            ], 404);
        }
        return response()->json([
            "data" => $enrollment->load(['student', 'course', 'schedule']),
            "message" => "get one successfully"
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                "message" => "Not found"

            ], 404);
        }
        $validate = $request->validate([
            'student_id' => 'required|string|exists:students,_id',
            'course_id' => 'required|string|exists:courses,_id',
            'schedule_id' => 'required|string|exists:schedules,_id',
            'enrollment_date' => 'nullable|date',

            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|string',

            'status' => 'required|boolean'
        ]);
        // safely check discount
        $validate['discount'] = $validate['discount'] ?? 0;

        $validate["status"] == 1 ? $validate["status"] = true : $validate["status"] = false;

        $enrollment->update($validate);

        return response()->json([
            "data" => $enrollment->load(['student', 'course', 'schedule']),
            "message" => "Update Enrollment successfully"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                "message" => "Not found"

            ], 404);
        }

        $enrollment->delete();

        return response()->json([
            "data" => $enrollment,
            "message" => "Delete enrollment successfully"
        ]);
    }
}
