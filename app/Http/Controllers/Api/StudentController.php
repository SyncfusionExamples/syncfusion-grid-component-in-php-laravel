<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        try {
            $query = Student::query();
            
            // Get total count before applying pagination
            $totalCount = $query->count();
            
            // Apply sorting
            if ($request->has('sortBy')) {
                $direction = $request->input('sortDirection', 'asc') === 'descending' ? 'desc' : 'asc';
                $query->orderBy($request->input('sortBy'), $direction);
            } else {
                $query->orderBy('StudentID', 'asc');
            }
            
            // Apply pagination
            $skip = (int) $request->input('skip', 0);
            $take = (int) $request->input('take', 10);
            
            $students = $query->skip($skip)->take($take)->get();
            
            return response()->json([
                'result' => $students,
                'count' => $totalCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        try {
            // Get request data
            $data = $request->json()->all() ?: $request->all();
            
            // Validate required fields
            if (empty($data['FirstName']) || empty($data['Course'])) {
                return response()->json(['error' => 'FirstName and Course are required'], 422);
            }
            
            // Create student
            $student = Student::create($data);
            
            return response()->json($student, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, $id)
    {
        try {
            // Get request data
            $data = $request->json()->all() ?: $request->all();
            
            // Find student
            $student = Student::findOrFail($id);
            
            // Update only provided fields
            $student->fill($data);
            $student->save();
            
            return response()->json($student);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Remove the specified student
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Find and delete student
            $student = Student::findOrFail($id);
            $student->delete();
            
            return response()->json(['success' => true]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
