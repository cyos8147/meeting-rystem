<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
  // แสดงรายการโปรแกรมทั้งหมด
public function index()
{
    $programs = Program::all();  // ดึงข้อมูลทั้งหมดจากตารางโปรแกรม
    return view('program.index', compact('programs')); // ส่งข้อมูลไปยัง view
}

// แสดงฟอร์มสร้างโปรแกรมใหม่
public function create()
{
    return view('programs.create'); // ส่งไปที่ฟอร์มการสร้างโปรแกรม
}

// เก็บข้อมูลโปรแกรมใหม่
public function store(Request $request)
{
    $request->validate([  // ตรวจสอบข้อมูลที่ได้รับ
        'name' => 'required|string|max:255',  // ชื่อโปรแกรมต้องกรอกและมีความยาวไม่เกิน 255 ตัวอักษร
        'description' => 'nullable|string',   // คำอธิบายเป็นข้อมูลที่ไม่จำเป็น
    ]);

    Program::create([  // สร้างโปรแกรมใหม่ในฐานข้อมูล
        'name' => $request->name,  // ชื่อโปรแกรม
        'description' => $request->description,  // คำอธิบาย
    ]);

    return redirect()->route('programs.index')->with('success', 'Program created successfully');  // เปลี่ยนเส้นทางไปยังหน้ารายการโปรแกรมพร้อมข้อความสำเร็จ
}

// แสดงฟอร์มแก้ไขโปรแกรม
public function edit($id)
{
    $program = Program::findOrFail($id);  // ค้นหาโปรแกรมที่มี ID ตรงกับที่รับมา
    return view('program.edit', compact('program'));  // ส่งข้อมูลโปรแกรมไปยังฟอร์มการแก้ไข
}

// อัพเดทข้อมูลโปรแกรม
public function update(Request $request, $id)
{
    $request->validate([  // ตรวจสอบข้อมูลที่ได้รับ
        'name' => 'required|string|max:255',  // ชื่อโปรแกรมต้องกรอกและมีความยาวไม่เกิน 255 ตัวอักษร
        'description' => 'nullable|string',   // คำอธิบายเป็นข้อมูลที่ไม่จำเป็น
    ]);

    $program = Program::findOrFail($id);  // ค้นหาโปรแกรมที่มี ID ตรงกับที่รับมา
    $program->update([  // อัพเดทข้อมูลโปรแกรม
        'name' => $request->name,  // ชื่อโปรแกรม
        'description' => $request->description,  // คำอธิบาย
    ]);

    return redirect()->route('programs.index')->with('success', 'Program updated successfully');  // เปลี่ยนเส้นทางไปยังหน้ารายการโปรแกรมพร้อมข้อความสำเร็จ
}

// ลบโปรแกรม
public function destroy($id)
{
    $program = Program::findOrFail($id);  // ค้นหาโปรแกรมที่มี ID ตรงกับที่รับมา
    $program->delete();  // ลบโปรแกรมออกจากฐานข้อมูล

    return redirect()->route('programs.index')->with('success', 'Program deleted successfully');  // เปลี่ยนเส้นทางไปยังหน้ารายการโปรแกรมพร้อมข้อความสำเร็จ
}

}
