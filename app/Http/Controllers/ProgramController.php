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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Program::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('programs.index')->with('success', 'Program created successfully');
    }

    // แสดงฟอร์มแก้ไขโปรแกรม
    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return view('program.edit', compact('program'));
    }

    // อัพเดทข้อมูลโปรแกรม
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $program = Program::findOrFail($id);
        $program->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('programs.index')->with('success', 'Program updated successfully');
    }

    // ลบโปรแกรม
    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Program deleted successfully');
    }
}
