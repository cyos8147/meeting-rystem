<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Admin;
use App\Models\Advisor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Show the list of admins
    public function index()
    {
        $admins = Admin::all();
        return view('admins.index', compact('admins'));
    }

    public function dashboard()
{
    // ดึงข้อมูลสำหรับการแสดงในแดชบอร์ด
    $totalStudents = Student::count();
    $maleStudents = Student::where('gender', 'Male')->count();
    $femaleStudents = Student::where('gender', 'Female')->count();

    $totalAdvisors = Advisor::count();

    $approvedAppointments = Activity::where('status', 'Approved')->count();
    $rejectedAppointments = Activity::where('status', 'Rejected')->count();
    $pendingAppointments = Activity::where('status', 'pending')->count();

    return view('admin.dashboard', compact(
        'totalStudents',
        'maleStudents',
        'femaleStudents',
        'totalAdvisors',
        'approvedAppointments',
        'rejectedAppointments',
        'pendingAppointments'
    ));
}

    // Show the form to create a new admin
    public function create()
    {
        return view('admins.create');
    }

    public function calendar(Request $request)
    {
        // Query สำหรับดึงข้อมูลกิจกรรมทั้งหมด
        $query = Activity::with(['student', 'advisor']);

        // กรองข้อมูลในตารางเท่านั้น ถ้ามีการส่ง status หรือช่วงเวลา
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('meeting_date', [$request->input('date_from'), $request->input('date_to')]);
        }

        // ดึงข้อมูลกิจกรรมพร้อมการแบ่งหน้า
        $activities = $query->paginate(10);

        // นับจำนวนสถานะทั้งหมดโดยไม่ขึ้นกับการกรอง
        $pendingCount = Activity::where('status', 'Pending')->count();
        $approvedCount = Activity::where('status', 'Approved')->count();
        $rejectedCount = Activity::where('status', 'Rejected')->count();

        return view('meetStudentWithAdvisor', [
            'activities' => $activities,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'filters' => $request->only(['status', 'date_from', 'date_to']),
        ]);
    }


    // Store a new admin
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admins',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8',
        ]);

        $admin = new Admin($request->all());
        $admin->password = bcrypt($request->password); // Hash the password
        $admin->save();

        return redirect()->route('admins.index')->with('success', 'Admin created successfully');
    }

    // Show the form to edit an existing admin
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    // Update an existing admin
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admins,username,' . $id,
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $admin = Admin::findOrFail($id);
        $admin->update($request->all());

        if ($request->password) {
            $admin->password = bcrypt($request->password); // Hash the password
            $admin->save();
        }

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully');
    }

    // Delete an admin
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully');
    }

    public function logout(Request $request)
{
    Auth::guard('admin')->logout(); // For admin guard
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
}
}
