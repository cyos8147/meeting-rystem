<?php
namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Models\Activity;
use App\Models\Advisor;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{

  // แสดงรายการนักเรียนทั้งหมดที่กรองตาม advisor_id ถ้ามี
public function index(Request $request)
{
    $query = Student::query();

    // หากมีการส่ง advisor_id มาจากคำขอ
    if ($request->has('advisor_id')) {
        $query->where('advisor_id', $request->advisor_id); // กรองตาม advisor_id
    }

    $students = $query->get(); // ดึงข้อมูลนักเรียนที่กรองแล้ว

    return view('student.list', compact('students')); // ส่งข้อมูลไปยัง view
}

// แสดงรายการนักเรียนสำหรับ Advisor
public function indexforadvisor(Request $request)
{
    $advisor = Auth::guard('advisor')->user();  // รับข้อมูลผู้ที่ล็อกอินเป็น Advisor
    $query   = Student::query();

    // ดึงข้อมูลทั้งหมดของ Advisor เพื่อส่งให้กับ view
    $advisors = Advisor::all(); // ดึงข้อมูล Advisor ทั้งหมดจากฐานข้อมูล

    // ตรวจสอบว่า request มี advisor_id หรือไม่
    if ($request->has('advisor_id')) {
        $query->where('advisor_id', $request->advisor_id); // กรองตาม advisor_id
    } else {
        $query->where('advisor_id', $advisor->id); // ถ้าไม่มีให้กรองตาม advisor ที่ล็อกอิน
    }

    // หากมีคำค้นหา ให้กรองข้อมูล
    if ($request->has('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('metric_number', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    // กรองข้อมูลตามลำดับหากมีการระบุ parameter ของการจัดลำดับ
    $validSortColumns = ['metric_number', 'name', 'gender', 'semester', 'email']; // ลบ advisor ออกจากรายการ
    if ($request->has('sort_by') && in_array($request->sort_by, $validSortColumns)) {
        $sortBy        = $request->sort_by;
        $sortDirection = $request->get('sort_direction', 'asc'); // เรียงจากน้อยไปหามากเป็นค่าเริ่มต้น
        $query->orderBy($sortBy, $sortDirection);
    }

    // ตรวจสอบว่ามีการระบุการจัดลำดับตามชื่อของ Advisor หรือไม่
    $sortDirection = $request->get('sort_direction', 'asc'); // เรียงจากน้อยไปหามาก

    // หากจัดลำดับตามชื่อของ Advisor ให้ทำการ join ตาราง advisor
    if ($request->has('sort_by') && $request->sort_by == 'advisor') {
        $query->join('advisors', 'students.advisor_id', '=', 'advisors.id')
            ->orderBy('advisors.name', $sortDirection);
    }

    // ใช้การแบ่งหน้าเพื่อแสดง 10 นักเรียนต่อหน้า
    $students = $query->paginate(10);

    // ส่งข้อมูลนักเรียนและ Advisor ไปยัง view
    return view('advisor.ShowStudent', compact('students', 'advisor', 'advisors'));
}

// แสดงรายการนักเรียนทั้งหมดสำหรับ Admin
public function indexforAdmin(Request $request)
{
    $search        = $request->get('search');  // คำค้นหาที่ผู้ใช้ระบุ
    $filter        = $request->get('filter', 'name');  // ตัวกรองเริ่มต้นเป็น 'name'
    $sortBy        = $request->get('sortBy', 'name');  // การจัดลำดับเริ่มต้นเป็น 'name'
    $sortDirection = $request->get('sortDirection', 'asc');  // ทิศทางการจัดลำดับเริ่มต้นเป็น 'asc'

    $students = Student::query();  // เริ่มต้น query สำหรับดึงข้อมูลนักเรียน

    // หากมีการค้นหา ให้กรองตามตัวกรองที่เลือก
    if ($search) {
        switch ($filter) {
            case 'race':
                $students->where('race', 'like', '%' . $search . '%');
                break;
            case 'name':
                $students->where('name', 'like', '%' . $search . '%');
                break;
            case 'metric_number':
                $students->where('metric_number', 'like', '%' . $search . '%');
                break;
            case 'advisor':
                $students->whereHas('advisor', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                break;
            case 'program':
                $students->whereHas('program', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                break;
            case 'semester':
                $students->where('semester', 'like', '%' . $search . '%');
                break;
            case 'email':
                $students->where('email', 'like', '%' . $search . '%');
                break;
            default:
                $students->where('name', 'like', '%' . $search . '%');
                break;
        }
    }

    // กรองข้อมูลตามโปรแกรมหากมีการจัดลำดับตามโปรแกรม
    if ($sortBy == 'program') {
        $students = $students->join('programs', 'programs.id', '=', 'students.program_id')
            ->orderBy('programs.name', $sortDirection);
    } elseif ($sortBy == 'advisor') {
        // กรองข้อมูลตาม Advisor
        $students = $students->join('advisors', 'advisors.id', '=', 'students.advisor_id')
            ->orderBy('advisors.name', $sortDirection);
    } else {
        $students = $students->orderBy($sortBy, $sortDirection);
    }

    // ใช้การแบ่งหน้าเพื่อแสดง 5 นักเรียนต่อหน้า
    $students = $students->paginate(5);

    return view('auth.admin.listStudent', compact('students', 'sortBy', 'sortDirection')); // ส่งข้อมูลไปยัง view
}

// แสดงรายละเอียดของนักเรียน
public function show($id)
{
    $student = Student::with('advisor')->findOrFail($id);  // ดึงข้อมูลนักเรียนพร้อมกับข้อมูล Advisor
    return view('auth.admin.showStudent', compact('student'));  // ส่งข้อมูลไปยัง view
}

// แสดงฟอร์มแก้ไขข้อมูลนักเรียน
public function edit($id)
{
    $student   = Student::findOrFail($id);  // ดึงข้อมูลนักเรียนที่ต้องการแก้ไข
    $advisors  = Advisor::all();  // ดึงข้อมูลทั้งหมดของ Advisor
    $isEditing = true;  // ตั้งค่าตัวแปรบ่งชี้ว่าเป็นการแก้ไข

    return view('auth.admin.editStudents', compact('student', 'advisors', 'isEditing'));  // ส่งข้อมูลไปยังฟอร์มการแก้ไข
}

// // แสดงโปรไฟล์ของนักเรียน
// public function showProfile($id)
// {
//     $student = Student::with('advisor')->findOrFail($id);  // ดึงข้อมูลนักเรียนพร้อมกับข้อมูล Advisor
//     return view('student.show', compact('student'));  // ส่งข้อมูลไปยัง view
// }

public function dashboard()
{
    $student = Auth::guard('student')->user(); // ดึงข้อมูลนักเรียนที่เข้าสู่ระบบ
    return view('student.dashboard', compact('student')); // ส่งข้อมูลนักเรียนไปยังหน้าแดชบอร์ด
}

public function meet()
{
    $student    = Auth::guard('student')->user(); // ดึงข้อมูลนักเรียนที่เข้าสู่ระบบ
    $activities = Activity::with(['student', 'advisor']) // ดึงข้อมูลกิจกรรมที่เชื่อมโยงกับนักเรียนและอาจารย์
        ->where('student_id', $student->id) // กรองกิจกรรมที่เกี่ยวข้องกับนักเรียน
        ->get();

    return view('student.meet', compact('activities', 'student')); // ส่งข้อมูลกิจกรรมและนักเรียนไปยังหน้า meet
}

public function create()
{
    $advisors = Advisor::all(); // ดึงข้อมูลอาจารย์ทั้งหมด
    $programs = Program::has('advisors')->get(); // ดึงข้อมูลโปรแกรมที่มีอาจารย์

    return view('student.create', compact('advisors', 'programs')); // ส่งข้อมูลอาจารย์และโปรแกรมไปยังหน้า create
}


public function store(Request $request)
{
    $validated = $request->validate([ // ทำการตรวจสอบข้อมูลที่กรอก
        'metric_number' => 'required|unique:students', // รหัสประจำตัวนักเรียนต้องกรอกและไม่ซ้ำ
        'name'          => 'required', // ชื่อนักเรียนต้องกรอก
        'gender'        => 'required', // เพศต้องกรอก
        'race'          => 'required', // เชื้อชาติต้องกรอก
        'program_id'    => 'required|exists:programs,id', // โปรแกรมต้องกรอกและต้องมีในฐานข้อมูล
        'semester'      => 'required|integer', // ภาคการศึกษาต้องกรอกและต้องเป็นตัวเลข
        'email'         => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|unique:students', // อีเมลต้องกรอกและต้องเป็นอีเมลที่ถูกต้องและไม่ซ้ำ
        'password'      => 'required|confirmed', // รหัสผ่านต้องกรอกและยืนยันรหัสผ่านต้องตรงกัน
        'phone_number'  => 'required|numeric', // หมายเลขโทรศัพท์ต้องกรอกและต้องเป็นตัวเลข
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048', // รูปโปรไฟล์ต้องกรอกและต้องเป็นรูปภาพประเภทที่กำหนด
    ]);

    try {
        // หากมีการอัปโหลดภาพโปรไฟล์ ให้จัดเก็บ
        $profileImagePath = $request->hasFile('profile_image')
        ? $request->file('profile_image')->store('profile_images', 'public') // เก็บภาพโปรไฟล์ในโฟลเดอร์ profile_images
        : null;

        // ดึงข้อมูลโปรแกรมและอาจารย์
        $program  = Program::findOrFail($request->program_id); // ค้นหาโปรแกรมตามรหัส
        $advisors = Advisor::where('program_id', $request->program_id)->get(); // ดึงข้อมูลอาจารย์ที่สอนในโปรแกรมนั้น

        // กำหนดเหตุผลเริ่มต้น
        $reason = null;

        // มอบหมายอาจารย์แบบสุ่ม
        $advisor = $this->assignRandomAdvisor($program, $advisors, $request->gender, $request->race, $reason);

        // ตรวจสอบหากไม่มีอาจารย์
        if (! $advisor) {
            return redirect()->back()->withInput()->withErrors([ // หากไม่มีอาจารย์ที่เหมาะสม ให้กลับไปพร้อมแสดงข้อผิดพลาด
                'error' => $reason,
            ]);
        }

        // สร้างนักเรียนใหม่
        $student = new Student([
            'metric_number' => $request->metric_number,
            'name'          => $request->name,
            'gender'        => $request->gender,
            'race'          => $request->race,
            'program_id'    => $request->program_id,
            'semester'      => $request->semester,
            'email'         => $request->email,
            'phone_number'  => $request->phone_number,
            'profile_image' => $profileImagePath,
            'password'      => bcrypt($request->password), // เข้ารหัสรหัสผ่าน
            'advisor_id'    => $advisor->id,
        ]);

        $student->save(); // บันทึกนักเรียนใหม่ลงในฐานข้อมูล

        // ลบข้อมูลเก่าที่กรอกและส่งกลับไปที่หน้าด้วยข้อความสำเร็จ
        session()->forget('_old_input');

        return redirect()->route('students.indexforAdmin')->with('success', 'Student registered successfully.'); // ส่งข้อความสำเร็จ
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred. Please try again.']); // หากเกิดข้อผิดพลาดในขั้นตอนการบันทึก
    }
}

private function assignRandomAdvisor($program, $advisors, $studentGender, $studentRace, &$reason)
{
    // ตรวจสอบว่ามีอาจารย์ที่สามารถรับนักเรียนได้หรือไม่
    $availableAdvisors = $advisors->filter(function ($advisor) { // กรองอาจารย์ที่มีจำนวนนักเรียนไม่เกินขีดจำกัด
        return $advisor->max_students > $advisor->students()->count();
    });

    if ($availableAdvisors->isEmpty()) { // หากไม่พบอาจารย์ที่สามารถรับได้
        $reason = 'No advisors are currently available to accept new students.'; // แจ้งเหตุผล
        return null;
    }

    // แยกอาจารย์ตามเพศ
    $maleAdvisors   = $availableAdvisors->where('gender', 'Male'); // อาจารย์ชาย
    $femaleAdvisors = $availableAdvisors->where('gender', 'Female'); // อาจารย์หญิง

    // กรองอาจารย์ตามเชื้อชาติ
    $filteredMaleAdvisors = $maleAdvisors->filter(function ($advisor) use ($studentRace) {
        return $advisor->students()->where('race', $studentRace)->count() < $advisor->max_students; // กรองอาจารย์ชายที่มีนักเรียนที่เชื้อชาติเดียวกันน้อยกว่าขีดจำกัด
    });

    $filteredFemaleAdvisors = $femaleAdvisors->filter(function ($advisor) use ($studentRace) {
        return $advisor->students()->where('race', $studentRace)->count() < $advisor->max_students; // กรองอาจารย์หญิงที่มีนักเรียนที่เชื้อชาติเดียวกันน้อยกว่าขีดจำกัด
    });

    // เลือกอาจารย์ตามเพศ
    $advisor = null;
    if ($studentGender === 'Male') {
        $advisor = $filteredMaleAdvisors->sortBy(fn($advisor) => $advisor->students()->count())->first(); // เลือกอาจารย์ชายที่มีจำนวนนักเรียนน้อยที่สุด
    } else {
        $advisor = $filteredFemaleAdvisors->sortBy(fn($advisor) => $advisor->students()->count())->first(); // เลือกอาจารย์หญิงที่มีจำนวนนักเรียนน้อยที่สุด
    }

    // หากไม่มีอาจารย์ที่ตรงกับเงื่อนไขเพศและเชื้อชาติ
    if (! $advisor) {
        $reason = 'Advisors matching the specified conditions have reached their student capacity.'; // แจ้งเหตุผล
    }

    // หากไม่มีอาจารย์ที่ตรงกับเงื่อนไข ให้เลือกอาจารย์ที่ยังมีความจุ
    if (! $advisor) {
        $advisor = $availableAdvisors->sortBy(fn($advisor) => $advisor->students()->count())->first(); // เลือกอาจารย์ที่มีจำนวนนักเรียนต่ำสุด
    }

    return $advisor; // ส่งคืนอาจารย์
}


    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validator = $request->validate([
            'metric_number' => 'required|unique:students,metric_number,' . $student->id,
            'name'          => 'required',
            'gender'        => 'required',
            'race'          => 'required',
            'semester'      => 'required|integer',
            'email'         => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|unique:students,email,' . $student->id,
            'password'      => 'nullable|confirmed',
            'phone_number'  => 'nullable|numeric',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
        ]);

        $data = $request->only([
            'metric_number',
            'name',
            'gender',
            'race',
            'semester',
            'email',
            'phone_number',
        ]);

        // ตรวจสอบรหัสผ่าน
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // อัปโหลดไฟล์ใหม่หากมีการอัปโหลด
        if ($request->hasFile('profile_image')) {
            // ลบภาพเก่าหากมี
            if ($student->profile_image) {
                Storage::disk('public')->delete($student->profile_image);
            }
            // อัปโหลดภาพใหม่
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // อัพเดทข้อมูลนักเรียน
        $student->update($data);

        // ส่งข้อความแจ้งเตือนสำเร็จ
        return redirect()->route('students.indexforAdmin')->with('success', 'Student updated successfully');
    }


    public function destroy($id)
    {
        // ก่อนอื่นจัดการกับการตั้งค่าค่าของ foreign key ให้เป็น null ตามความจำเป็น
        $student = Student::findOrFail($id); // ค้นหานักเรียนตาม ID หรือถ้าไม่พบให้โยนข้อผิดพลาด

        // ตั้งค่า foreign key ให้เป็น null แทนการลบ
        $student->advisor_id = null; // ตั้งค่า advisor_id เป็น null
        $student->program_id = null; // ตั้งค่า program_id เป็น null

        // บันทึกการเปลี่ยนแปลงเพื่อตั้งค่า foreign key ให้เป็น null
        $student->save();

        // ลบข้อมูลอื่น ๆ เช่น กิจกรรม หรือรูปโปรไฟล์ (ถ้าจำเป็น)
        $student->activities()->delete(); // ลบกิจกรรมที่เชื่อมโยงกับนักเรียน

        // ลบรูปโปรไฟล์หากมี
        if ($student->profile_image) {
            Storage::disk('public')->delete($student->profile_image); // ลบรูปโปรไฟล์จากที่จัดเก็บ
        }

        // ตอนนี้ทำการลบข้อมูลนักเรียนจากฐานข้อมูล
        DB::table('students')->where('id', $id)->delete(); // ลบข้อมูลนักเรียนจากฐานข้อมูล

        // ส่งกลับไปยังหน้ารายชื่อนักเรียนพร้อมข้อความสำเร็จ
        return redirect()->route('students.indexforAdmin')->with('success', 'Student and related records deleted successfully'); // ส่งข้อความสำเร็จ
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout(); // ออกจากระบบของผู้ใช้ประเภทนักเรียน
        $request->session()->invalidate(); // ยกเลิก session
        $request->session()->regenerateToken(); // สร้าง token ใหม่เพื่อความปลอดภัย
        return redirect('/login'); // เปลี่ยนเส้นทางไปยังหน้าล็อกอิน
    }


    // public function import(Request $request)
    // {
    //     // ตรวจสอบว่าอัปโหลดไฟล์ถูกต้อง
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls',
    //     ]);

    //     try {
    //         // นับจำนวนนักเรียนก่อน Import
    //         $beforeCount = \App\Models\Student::count();

    //         // นำเข้าข้อมูลจากไฟล์ Excel
    //         Excel::import(new StudentsImport, $request->file('file'));

    //         // นับจำนวนนักเรียนหลัง Import
    //         $afterCount = \App\Models\Student::count();

    //         // ตรวจสอบว่ามีการเพิ่มข้อมูลหรือไม่
    //         if ($afterCount > $beforeCount) {
    //             return redirect()->back()->with('swal', [
    //                 'type'  => 'success',
    //                 'title' => 'Data imported successfully!',
    //                 'text'  => '✅ Data imported successfully!',
    //             ]);
    //         } else {
    //             return redirect()->back()->with('swal', [
    //                 'type'  => 'error',
    //                 'title' => 'No data was imported',
    //                 'text'  => '❌ No data was imported. Please check your file.',
    //             ]);
    //         }
    //     } catch (\Exception $e) {
    //         // หากมีข้อผิดพลาดในการนำเข้าแสดงผล
    //         $errors   = session('import_errors', []);
    //         $messages = [];

    //         // วนลูปข้อผิดพลาดและเพิ่มข้อความให้ละเอียด
    //         foreach ($errors as $error) {
    //             $messages[] = "Row {$error['row']}: Missing fields - " . implode(', ', $error['missing_fields']);
    //         }

    //         return redirect()->back()->with('swal', [
    //             'type'  => 'error',
    //             'title' => 'Import Error',
    //             'text'  => '❌ ' . implode('<br>', $messages),
    //         ]);
    //     }
    // }

    // public function export()
    // {
    //     return Excel::download(new StudentsExport, 'students.xlsx');
    // }
}
