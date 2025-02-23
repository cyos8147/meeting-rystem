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
 // แสดงรายการผู้ดูแลระบบทั้งหมด
public function index()
{
    // ดึงข้อมูลผู้ดูแลระบบทั้งหมดจากฐานข้อมูล
    $admins = Admin::all();

    // ส่งข้อมูลไปยังหน้าแสดงรายการผู้ดูแลระบบ
    return view('admins.index', compact('admins'));
}

// แสดงแดชบอร์ดของผู้ดูแลระบบ
public function dashboard()
{
    // ดึงจำนวนรวมของนักศึกษาทั้งหมด
    $totalStudents = Student::count();

    // ดึงจำนวนนักศึกษาชาย
    $maleStudents = Student::where('gender', 'Male')->count();

    // ดึงจำนวนนักศึกษาหญิง
    $femaleStudents = Student::where('gender', 'Female')->count();

    // ดึงจำนวนอาจารย์ที่ปรึกษาทั้งหมด
    $totalAdvisors = Advisor::count();

    // ดึงจำนวนการนัดหมายที่ได้รับการอนุมัติ
    $approvedAppointments = Activity::where('status', 'Approved')->count();

    // ดึงจำนวนการนัดหมายที่ถูกปฏิเสธ
    $rejectedAppointments = Activity::where('status', 'Rejected')->count();

    // ดึงจำนวนการนัดหมายที่รอการอนุมัติ
    $pendingAppointments = Activity::where('status', 'pending')->count();

    // ส่งข้อมูลไปยังหน้าแดชบอร์ดของผู้ดูแลระบบ
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


    // แสดงฟอร์มสำหรับสร้างผู้ดูแลระบบใหม่
public function create()
{
    return view('admins.create');
}

public function calendar(Request $request)
{
    // คำสั่ง Query สำหรับดึงข้อมูลกิจกรรมทั้งหมด
    $query = Activity::with(['student', 'advisor']);

    // กรองข้อมูลในตาราง ถ้ามีการส่งค่า status หรือช่วงเวลา
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



  // บันทึกผู้ดูแลระบบใหม่
public function store(Request $request)
{
    // ตรวจสอบความถูกต้องของข้อมูลที่ได้รับจากฟอร์ม
    $request->validate([
        'username' => 'required|string|max:255|unique:admins', // ชื่อผู้ใช้งานต้องไม่ซ้ำ
        'email' => 'required|email|unique:admins,email', // อีเมลต้องไม่ซ้ำและมีรูปแบบที่ถูกต้อง
        'password' => 'required|string|min:8', // รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร
    ]);

    // สร้างผู้ดูแลระบบใหม่จากข้อมูลที่ได้รับ
    $admin = new Admin($request->all());
    $admin->password = bcrypt($request->password); // เข้ารหัสรหัสผ่าน
    $admin->save(); // บันทึกข้อมูลผู้ดูแลระบบใหม่ในฐานข้อมูล

    // เปลี่ยนเส้นทางไปยังหน้ารายการผู้ดูแลระบบและแสดงข้อความสำเร็จ
    return redirect()->route('admins.index')->with('success', 'Admin created successfully');
}

   // แสดงฟอร์มสำหรับแก้ไขผู้ดูแลระบบที่มีอยู่แล้ว
public function edit($id)
{
    $admin = Admin::findOrFail($id); // ค้นหาผู้ดูแลระบบตาม ID ที่ส่งมา
    return view('admins.edit', compact('admin')); // ส่งข้อมูลผู้ดูแลระบบไปยังหน้าฟอร์มแก้ไข
}

   // อัปเดตข้อมูลผู้ดูแลระบบที่มีอยู่แล้ว
public function update(Request $request, $id)
{
    // ตรวจสอบความถูกต้องของข้อมูลที่ได้รับจากฟอร์ม
    $request->validate([
        'username' => 'required|string|max:255|unique:admins,username,' . $id, // ชื่อผู้ใช้งานต้องไม่ซ้ำ และยกเว้นผู้ใช้ปัจจุบัน
        'email' => 'required|email|unique:admins,email,' . $id, // อีเมลต้องไม่ซ้ำ และยกเว้นผู้ใช้ปัจจุบัน
        'password' => 'nullable|string|min:8', // รหัสผ่านสามารถเว้นว่างได้ แต่หากกรอกต้องมีความยาวไม่น้อยกว่า 8 ตัวอักษร
    ]);

    // ค้นหาผู้ดูแลระบบตาม ID ที่ส่งมา
    $admin = Admin::findOrFail($id);

    // อัปเดตข้อมูลผู้ดูแลระบบจากข้อมูลที่ได้รับ
    $admin->update($request->all());

    // หากมีการกรอกรหัสผ่านใหม่
    if ($request->password) {
        $admin->password = bcrypt($request->password); // เข้ารหัสรหัสผ่าน
        $admin->save(); // บันทึกการเปลี่ยนแปลง
    }

    // เปลี่ยนเส้นทางไปยังหน้ารายการผู้ดูแลระบบและแสดงข้อความสำเร็จ
    return redirect()->route('admins.index')->with('success', 'Admin updated successfully');
}


// ลบผู้ดูแลระบบ
public function destroy($id)
{
    // ค้นหาผู้ดูแลระบบตาม ID ที่ส่งมา
    $admin = Admin::findOrFail($id);

    // ลบผู้ดูแลระบบ
    $admin->delete();

    // เปลี่ยนเส้นทางไปยังหน้ารายการผู้ดูแลระบบและแสดงข้อความสำเร็จ
    return redirect()->route('admins.index')->with('success', 'Admin deleted successfully');
}

// ออกจากระบบผู้ดูแลระบบ
public function logout(Request $request)
{
    Auth::guard('admin')->logout(); // ออกจากระบบสำหรับผู้ดูแลระบบ
    $request->session()->invalidate(); // ยกเลิกเซสชัน
    $request->session()->regenerateToken(); // สร้างโทเค็นใหม่สำหรับความปลอดภัย
    return redirect('/'); // เปลี่ยนเส้นทางไปยังหน้าแรก
}

}
