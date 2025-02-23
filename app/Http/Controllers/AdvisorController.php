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
    // ดึงค่าการค้นหาและการกรองจากคำขอ (request)
    $search = $request->get('search');
    $filter = $request->get('filter', 'name');
    $sortBy = $request->get('sortBy', 'name');
    $sortDirection = $request->get('sortDirection', 'asc');

    // สร้างคำค้นหาสำหรับที่ปรึกษา (advisors)
    $advisors = Advisor::when($search, function ($query) use ($search, $filter) {
        return $query->where($filter, 'like', '%' . $search . '%');
    })
    ->orderBy($sortBy, $sortDirection) // เรียงลำดับตามตัวเลือกที่เลือก
    ->paginate(10); // ปรับจำนวนรายการต่อหน้า (ถ้าต้องการ)

    // ดึงข้อมูลนักเรียนที่มี advisor_id, ถ้ามี
    foreach ($advisors as $advisor) {
        $advisor->students = Student::where('advisor_id', $advisor->id)->get();
    }

    // ส่งข้อมูลไปยังหน้ามุมมอง (view)
    return view('advisor.index', compact('advisors', 'search', 'filter', 'sortBy', 'sortDirection'));
}



public function show($id)
{
    // ดึงรายละเอียดของที่ปรึกษา
    // ค้นหาที่ปรึกษาตาม ID
    $advisor = Advisor::findOrFail($id);

    // ดึงข้อมูลนักเรียนที่มี advisor_id ตรงกับที่ปรึกษานั้นๆ และนับแยกตามเพศ
    $maleStudents = Student::where('advisor_id', $id)->where('gender', 'Male')->get();
    $femaleStudents = Student::where('advisor_id', $id)->where('gender', 'Female')->get();

    // นับจำนวนของนักเรียนชายและหญิง
    $maleCount = $maleStudents->count();
    $femaleCount = $femaleStudents->count();

    // ส่งข้อมูลที่ปรึกษาและข้อมูลนักเรียนไปยังหน้าแสดงผล
    return view('advisor.show', compact('advisor', 'maleStudents', 'femaleStudents', 'maleCount', 'femaleCount'));

    // ส่งข้อมูลที่ปรึกษาไปยังหน้าแสดงผล
    return view('advisor.show', compact('advisor'));
}


public function showStudentForadvisor($id)
{
    // ดึงรายละเอียดของที่ปรึกษา
    $advisor = Advisor::findOrFail($id);

    // ดึงข้อมูลนักเรียนที่มี advisor_id ตรงกับที่ปรึกษานั้นๆ และนับแยกตามเพศ
    $maleStudents = Student::where('advisor_id', $id)->where('gender', 'Male')->get();
    $femaleStudents = Student::where('advisor_id', $id)->where('gender', 'Female')->get();

    // นับจำนวนของนักเรียนชายและหญิง
    $maleCount = $maleStudents->count();
    $femaleCount = $femaleStudents->count();

    // ส่งข้อมูลที่ปรึกษาและข้อมูลนักเรียนไปยังหน้าแสดงผล
    return view('advisor.ShowStudent', compact('advisor', 'maleStudents', 'femaleStudents', 'maleCount', 'femaleCount'));
}



public function meet()
{
    // ดึงข้อมูลที่ปรึกษาที่เข้าสู่ระบบปัจจุบัน
    $advisor = Auth::guard('advisor')->user();

    // ดึงกิจกรรมทั้งหมดที่เกี่ยวข้องกับที่ปรึกษาที่เข้าสู่ระบบ
    $activities = Activity::with(['student', 'advisor']) // โหลดข้อมูลที่เกี่ยวข้องกับนักเรียนและที่ปรึกษา
        ->where('advisor_id', $advisor->id) // กรองกิจกรรมโดยใช้ ID ของที่ปรึกษาที่เข้าสู่ระบบ
        ->get();

    // คำนวณจำนวนกิจกรรมตามสถานะ
    $pendingCount = $activities->where('status', 'Pending')->count(); // จำนวนกิจกรรมที่รอดำเนินการ
    $approvedCount = $activities->where('status', 'Approved')->count(); // จำนวนกิจกรรมที่อนุมัติแล้ว
    $rejectedCount = $activities->where('status', 'Rejected')->count(); // จำนวนกิจกรรมที่ถูกปฏิเสธ

    // ส่งข้อมูลที่ปรึกษา, กิจกรรม, และจำนวนสถานะต่างๆ ไปยังหน้าแสดงผล
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

    // นับจำนวนนักเรียนทั้งหมดที่ได้รับมอบหมายให้กับที่ปรึกษา
    $studentCount = Student::where('advisor_id', $advisor->id)->count();

    // นับจำนวนนักเรียนชายและหญิง
    $maleCount = Student::where('advisor_id', $advisor->id)->where('gender', 'Male')->count();
    $femaleCount = Student::where('advisor_id', $advisor->id)->where('gender', 'Female')->count();

    // ดึงข้อมูลกิจกรรมทั้งหมดที่เกี่ยวข้องกับที่ปรึกษา
    $activities = Activity::where('advisor_id', $advisor->id)->get();

    // คำนวณจำนวนกิจกรรมตามสถานะ
    $pendingCount = $activities->where('status', 'Pending')->count(); // จำนวนกิจกรรมที่รอดำเนินการ
    $approvedCount = $activities->where('status', 'Approved')->count(); // จำนวนกิจกรรมที่อนุมัติแล้ว
    $rejectedCount = $activities->where('status', 'Rejected')->count(); // จำนวนกิจกรรมที่ถูกปฏิเสธ

    return view('advisor.dashboard', compact(
        'advisor', 'studentCount', 'maleCount', 'femaleCount',
        'pendingCount', 'approvedCount', 'rejectedCount'
    ));
}

/**
 * แสดงฟอร์มสำหรับการสร้างที่ปรึกษาใหม่
 */
public function create()
{
    $programs = Program::all(); // ดึงข้อมูลโปรแกรมทั้งหมด
    $programsByType = $programs->groupBy('type'); // จัดกลุ่มโปรแกรมตามประเภท

    return view('advisor.create', compact('programsByType')); // ส่งข้อมูลโปรแกรมที่จัดกลุ่มไปยังหน้าแสดงผล
}


/**
 * จัดเก็บข้อมูลที่ปรึกษาที่ถูกสร้างใหม่ลงในฐานข้อมูล
 */
public function store(Request $request)
{
    $request->validate([
        'name'          => 'required|string|max:255', // ชื่อที่ปรึกษาต้องการ
        'email'         => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|unique:advisors,email', // อีเมลต้องตรงตามรูปแบบที่กำหนดและไม่ซ้ำกัน
        'password'      => 'required|confirmed|string|min:8', // รหัสผ่านต้องยืนยันและมีความยาวไม่น้อยกว่า 8 ตัว
        'metric_number' => 'required|unique:advisors,metric_number', // หมายเลขมาตรวัดต้องไม่ซ้ำกัน
        'phone_number'  => 'required|string|max:15|unique:advisors,phone_number', // เบอร์โทรศัพท์ต้องไม่เกิน 15 ตัวและไม่ซ้ำกัน
        'max_students'  => 'required|integer', // จำนวนสูงสุดของนักเรียนต้องเป็นจำนวนเต็ม
        'program_id'    => 'required|exists:programs,id', // ต้องเลือกโปรแกรมที่มีอยู่ในระบบ
        'profile_image' => 'nullable|image|max:20048', // รูปภาพประจำตัวต้องเป็นไฟล์รูปภาพและขนาดไม่เกิน 20MB
    ]);

    $advisor = new Advisor($request->except('password', 'profile_image')); // สร้างที่ปรึกษาใหม่จากข้อมูลที่ส่งมาทั้งหมด ยกเว้นรหัสผ่านและรูปภาพ

    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public'); // เก็บไฟล์รูปภาพในโฟลเดอร์ 'profile_images'
        $advisor->profile_image = $path; // เก็บที่อยู่ของไฟล์รูปภาพในฐานข้อมูล
    }

    $advisor->password = bcrypt($request->password); // แฮชรหัสผ่านก่อนเก็บในฐานข้อมูล
    $advisor->save(); // บันทึกข้อมูลที่ปรึกษาลงในฐานข้อมูล

    return redirect()->route('advisors.index')->with('success', 'Advisor created successfully.'); // เปลี่ยนเส้นทางไปยังหน้ารายการที่ปรึกษาและแสดงข้อความสำเร็จ
}



  /**
 * แสดงฟอร์มสำหรับการแก้ไขข้อมูลที่ปรึกษาที่ระบุ
 */
public function edit($id)
{
    // ดึงข้อมูลที่ปรึกษาจากฐานข้อมูลตาม ID ที่ระบุ
    $advisor = Advisor::findOrFail($id);

    // ดึงข้อมูลนักเรียนที่มี advisor_id ตรงกับที่ปรึกษาและแยกตามเพศ
    $maleStudents = Student::where('advisor_id', $id)->where('gender', 'Male')->get();
    $femaleStudents = Student::where('advisor_id', $id)->where('gender', 'Female')->get();

    // นับจำนวนของนักเรียนชายและหญิง
    $maleCount = $maleStudents->count();
    $femaleCount = $femaleStudents->count();

    // ส่งข้อมูลที่ปรึกษาและนักเรียนที่เกี่ยวข้องไปยังหน้าฟอร์มการแก้ไข
    return view('advisor.edit', compact('advisor', 'maleStudents', 'femaleStudents', 'maleCount', 'femaleCount'));
}


/**
 * อัพเดตข้อมูลของที่ปรึกษาที่ระบุในฐานข้อมูล
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

    // เติมข้อมูลที่ปรึกษาด้วยข้อมูลที่ได้รับจากฟอร์ม ยกเว้น 'password' และ 'profile_image'
    $advisor->fill($request->except('password', 'profile_image'));

    // หากมีไฟล์รูปโปรไฟล์ใหม่ อัพโหลดและลบไฟล์เก่าออกหากมี
    if ($request->hasFile('profile_image')) {
        if ($advisor->profile_image && Storage::exists('public/' . $advisor->profile_image)) {
            Storage::delete('public/' . $advisor->profile_image); // ลบไฟล์โปรไฟล์เก่าที่มีอยู่
        }

        $path = $request->file('profile_image')->store('profile_images', 'public');
        $advisor->profile_image = $path;
    }

    // หากมีการเปลี่ยนรหัสผ่าน ให้ทำการเข้ารหัสและอัพเดต
    if ($request->password) {
        $advisor->password = bcrypt($request->password);
    }

    $advisor->save(); // บันทึกข้อมูลที่ปรับปรุง

    return redirect()->route('advisor.show', ['id' => $advisor->id])->with('success', 'Advisor updated successfully.');
}

/**
 * ลบที่ปรึกษาที่ระบุออกจากฐานข้อมูล
 */
public function destroy($id)
{
    $advisor = Advisor::findOrFail($id);
    $advisor->delete(); // ลบที่ปรึกษาจากฐานข้อมูล

    return redirect()->route('advisors.index')->with('success', 'Advisor deleted successfully.');
}



/**
 * จัดการการออกจากระบบสำหรับที่ปรึกษา
 */
public function logout(Request $request)
{
    Auth::guard('advisor')->logout(); // ออกจากระบบโดยใช้ advisor guard
    $request->session()->invalidate(); // ทำให้ session เป็นโมฆะ
    $request->session()->regenerateToken(); // สร้าง token ใหม่เพื่อป้องกัน CSRF

    return redirect('/login'); // เปลี่ยนเส้นทางไปยังหน้าล็อกอิน
}

}
