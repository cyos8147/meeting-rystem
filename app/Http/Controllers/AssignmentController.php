<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Student;
use App\Models\Advisor;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
  // แสดงรายการการมอบหมายงาน
public function index()
{
    $assignments = Assignment::with(['student', 'advisor'])->get(); // ดึงข้อมูลการมอบหมายงานทั้งหมดพร้อมข้อมูลนักเรียนและที่ปรึกษา
    return view('assignments.index', compact('assignments')); // ส่งข้อมูลการมอบหมายไปยังวิว
}

// แสดงฟอร์มสำหรับการสร้างการมอบหมายงานใหม่
public function create()
{
    $students = Student::all(); // รายชื่อนักเรียนทั้งหมด
    $advisors = Advisor::all(); // รายชื่อที่ปรึกษาทั้งหมด
    return view('assignments.create', compact('students', 'advisors')); // ส่งข้อมูลนักเรียนและที่ปรึกษาไปยังวิว
}

// บันทึกการมอบหมายงานใหม่
public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id', // ต้องระบุ ID ของนักเรียนและต้องมีอยู่ในฐานข้อมูล
        'advisor_id' => 'required|exists:advisors,id', // ต้องระบุ ID ของที่ปรึกษาและต้องมีอยู่ในฐานข้อมูล
        'assigned_at' => 'nullable|date', // วันที่มอบหมายงาน (สามารถไม่ระบุได้)
    ]);

    $data = $request->all(); // ดึงข้อมูลทั้งหมดจากฟอร์ม
    Assignment::create($data); // สร้างการมอบหมายงานใหม่ในฐานข้อมูล

    return redirect()->route('assignments.index')->with('success', 'Assignment created successfully'); // เปลี่ยนเส้นทางไปยังหน้ารายการการมอบหมายงานและแสดงข้อความสำเร็จ
}

// แสดงฟอร์มสำหรับการแก้ไขการมอบหมายงานที่มีอยู่
public function edit($id)
{
    $assignment = Assignment::findOrFail($id); // ดึงข้อมูลการมอบหมายงานจาก ID ที่ระบุ
    $students = Student::all(); // รายชื่อนักเรียนทั้งหมด
    $advisors = Advisor::all(); // รายชื่อที่ปรึกษาทั้งหมด
    return view('assignments.edit', compact('assignment', 'students', 'advisors')); // ส่งข้อมูลไปยังวิวสำหรับการแก้ไข
}

// อัพเดตการมอบหมายงานที่มีอยู่
public function update(Request $request, $id)
{
    $request->validate([
        'student_id' => 'required|exists:students,id', // ต้องระบุ ID ของนักเรียนและต้องมีอยู่ในฐานข้อมูล
        'advisor_id' => 'required|exists:advisors,id', // ต้องระบุ ID ของที่ปรึกษาและต้องมีอยู่ในฐานข้อมูล
        'assigned_at' => 'nullable|date', // วันที่มอบหมายงาน (สามารถไม่ระบุได้)
    ]);

    $assignment = Assignment::findOrFail($id); // ดึงข้อมูลการมอบหมายงานจาก ID ที่ระบุ
    $assignment->update($request->all()); // อัพเดตข้อมูลการมอบหมายงานในฐานข้อมูล

    return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully'); // เปลี่ยนเส้นทางไปยังหน้ารายการการมอบหมายงานและแสดงข้อความสำเร็จ
}

// ลบการมอบหมายงานที่มีอยู่
public function destroy($id)
{
    $assignment = Assignment::findOrFail($id); // ดึงข้อมูลการมอบหมายงานจาก ID ที่ระบุ
    $assignment->delete(); // ลบการมอบหมายงานจากฐานข้อมูล

    return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully'); // เปลี่ยนเส้นทางไปยังหน้ารายการการมอบหมายงานและแสดงข้อความสำเร็จ
}

}
