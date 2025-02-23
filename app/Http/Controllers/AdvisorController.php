<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Advisor;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class AdvisorController extends Controller
{
    /**
     * Display a listing of advisors.
     */
// In AdvisorController.php
public function index(Request $request)
{
    // Get search and filter values from request
    $search = $request->get('search');
    $filter = $request->get('filter', 'name');
    $sortBy = $request->get('sortBy', 'name');
    $sortDirection = $request->get('sortDirection', 'asc');

    // Build query for advisors
    $advisors = Advisor::when($search, function ($query) use ($search, $filter) {
        return $query->where($filter, 'like', '%' . $search . '%');
    })
    ->orderBy($sortBy, $sortDirection)
    ->paginate(10); // Adjust items per page if needed

    // Fetch students with an advisor_id, if any
    foreach ($advisors as $advisor) {
        $advisor->students = Student::where('advisor_id', $advisor->id)->get();
    }

    // Pass data to the view
    return view('advisor.index', compact('advisors', 'search', 'filter', 'sortBy', 'sortDirection'));
}



public function show($id)
{
    // Fetch the advisor details
    // Retrieve the advisor by ID
    $advisor = Advisor::findOrFail($id);

    // Retrieve students who have the given advisor's ID and count them by gender
    $maleStudents = Student::where('advisor_id', $id)->where('gender', 'Male')->get();
    $femaleStudents = Student::where('advisor_id', $id)->where('gender', 'Female')->get();

    // Count the number of male and female students
    $maleCount = $maleStudents->count();
    $femaleCount = $femaleStudents->count();

    // Return the show view with the advisor and student data
    return view('advisor.show', compact('advisor', 'maleStudents', 'femaleStudents', 'maleCount', 'femaleCount'));

    // Pass the advisor to the view
    return view('advisor.show', compact('advisor'));
}

public function showStudentForadvisor($id)
{
    // Fetch the advisor details
    $advisor = Advisor::findOrFail($id);

    // Retrieve students for the advisor and count them by gender
    $maleStudents = Student::where('advisor_id', $id)->where('gender', 'Male')->get();
    $femaleStudents = Student::where('advisor_id', $id)->where('gender', 'Female')->get();

    // Count the number of male and female students
    $maleCount = $maleStudents->count();
    $femaleCount = $femaleStudents->count();

    // Return the view with advisor and student data
    return view('advisor.ShowStudent', compact('advisor', 'maleStudents', 'femaleStudents', 'maleCount', 'femaleCount'));
}


public function meet()
{
    // Get the currently logged-in advisor
    $advisor = Auth::guard('advisor')->user();

    // Retrieve activities for the logged-in advisor
    $activities = Activity::with(['student', 'advisor']) // Load related student and advisor models
        ->where('advisor_id', $advisor->id) // Filter activities by the logged-in advisor ID
        ->get();

    // Calculate counts for each status
    $pendingCount = $activities->where('status', 'Pending')->count();
    $approvedCount = $activities->where('status', 'Approved')->count();
    $rejectedCount = $activities->where('status', 'Rejected')->count();

    // Pass the advisor, activities, and status counts to the view
    return view('advisor.meet', [
        'activities' => $activities,
        'advisor' => $advisor,
        'pendingCount' => $pendingCount,
        'approvedCount' => $approvedCount,
        'rejectedCount' => $rejectedCount,
    ]);
}



public function dashboard()
{
    $advisor = Auth::guard('advisor')->user();

    // Count the total number of students assigned to the advisor
    $studentCount = Student::where('advisor_id', $advisor->id)->count();

    // Count male and female students
    $maleCount = Student::where('advisor_id', $advisor->id)->where('gender', 'Male')->count();
    $femaleCount = Student::where('advisor_id', $advisor->id)->where('gender', 'Female')->count();

    // Retrieve activities for the advisor
    $activities = Activity::where('advisor_id', $advisor->id)->get();

    // Calculate counts for each status
    $pendingCount = $activities->where('status', 'Pending')->count();
    $approvedCount = $activities->where('status', 'Approved')->count();
    $rejectedCount = $activities->where('status', 'Rejected')->count();

    return view('advisor.dashboard', compact(
        'advisor', 'studentCount', 'maleCount', 'femaleCount',
        'pendingCount', 'approvedCount', 'rejectedCount'
    ));
}




    /**
     * Show the form to create a new advisor.
     */
    public function create()
    {
        $programs = Program::all();
        $programsByType = $programs->groupBy('type');
        return view('advisor.create', compact('programsByType'));
    }





    /**
     * Store a newly created advisor in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|unique:advisors,email',
            'password'      => 'required|confirmed|string|min:8',
            'metric_number' => 'required|unique:advisors,metric_number',
            'phone_number'  => 'required|string|max:15|unique:advisors,phone_number', // เพิ่ม validation
            'max_students'  => 'required|integer',
            'program_id'    => 'required|exists:programs,id',
            'profile_image' => 'nullable|image|max:20048',
        ]);

        $advisor = new Advisor($request->except('password', 'profile_image'));

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $advisor->profile_image = $path;
        }

        $advisor->password = bcrypt($request->password);
        $advisor->save();

        return redirect()->route('advisors.index')->with('success', 'Advisor created successfully.');
    }


    /**
     * Show the form for editing the specified advisor.
     */
    public function edit($id)
    {
        // Retrieve the advisor by ID
        $advisor = Advisor::findOrFail($id);

        // Retrieve students who have the given advisor's ID and count them by gender
        $maleStudents = Student::where('advisor_id', $id)->where('gender', 'Male')->get();
        $femaleStudents = Student::where('advisor_id', $id)->where('gender', 'Female')->get();

        // Count the number of male and female students
        $maleCount = $maleStudents->count();
        $femaleCount = $femaleStudents->count();

        // Return the edit view with both the advisor and the associated students
        return view('advisor.edit', compact('advisor', 'maleStudents', 'femaleStudents', 'maleCount', 'femaleCount'));
    }


    /**
     * Update the specified advisor in storage.
     */
    public function update(Request $request, $id)
    {
        $advisor = Advisor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|unique:advisors,email,' . $advisor->id,
            'password' => 'nullable|string|min:8',
            'metric_number' => 'required|unique:advisors,metric_number,' . $advisor->id,
            'phone_number' => 'required|string|max:15|unique:advisors,phone_number,' . $advisor->id, // เพิ่ม validation
            'max_students' => 'required|integer',
            'program' => 'required|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $advisor->fill($request->except('password', 'profile_image'));

        if ($request->hasFile('profile_image')) {
            if ($advisor->profile_image && Storage::exists('public/' . $advisor->profile_image)) {
                Storage::delete('public/' . $advisor->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $advisor->profile_image = $path;
        }

        if ($request->password) {
            $advisor->password = bcrypt($request->password);
        }

        $advisor->save();

        return redirect()->route('advisor.show', ['id' => $advisor->id])->with('success', 'Advisor updated successfully.');
    }


    /**
     * Remove the specified advisor from storage.
     */
    public function destroy($id)
    {
        $advisor = Advisor::findOrFail($id);
        $advisor->delete();

        return redirect()->route('advisors.index')->with('success', 'Advisor deleted successfully.');
    }

    /**
     * Handle the login for advisors.
     */
    public function login(Request $request)
    {
        $request->validate([
            'metric_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('metric_number', 'password');

        if (Auth::guard('advisor')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('advisor.dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'metric_number' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle the logout for advisors.
     */
    public function logout(Request $request)
    {
        Auth::guard('advisor')->logout(); // Use advisor guard
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
