<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Student;
use App\Models\Advisor;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    // Show the list of assignments
    public function index()
    {
        $assignments = Assignment::with(['student', 'advisor'])->get();
        return view('assignments.index', compact('assignments'));
    }

    // Show the form to create a new assignment
    public function create()
    {
        $students = Student::all(); // List of all students
        $advisors = Advisor::all(); // List of all advisors
        return view('assignments.create', compact('students', 'advisors'));
    }

    // Store a new assignment
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'advisor_id' => 'required|exists:advisors,id',
            'assigned_at' => 'nullable|date',
        ]);

        $data = $request->all();
        Assignment::create($data);

        return redirect()->route('assignments.index')->with('success', 'Assignment created successfully');
    }

    // Show the form to edit an existing assignment
    public function edit($id)
    {
        $assignment = Assignment::findOrFail($id);
        $students = Student::all(); // List of all students
        $advisors = Advisor::all(); // List of all advisors
        return view('assignments.edit', compact('assignment', 'students', 'advisors'));
    }

    // Update an existing assignment
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'advisor_id' => 'required|exists:advisors,id',
            'assigned_at' => 'nullable|date',
        ]);

        $assignment = Assignment::findOrFail($id);
        $assignment->update($request->all());

        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully');
    }

    // Delete an existing assignment
    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully');
    }
}
