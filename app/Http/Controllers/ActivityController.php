<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Advisor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
{
    // รับข้อมูลของนักศึกษาที่ล็อกอินอยู่ในปัจจุบัน
    $student = Auth::guard('student')->user();

    // ดึงกิจกรรมที่เกี่ยวข้องกับนักศึกษาที่ล็อกอินอยู่
    $activities = Activity::with(['student', 'advisor'])
        ->where('student_id', $student->id) // ดึงกิจกรรมเฉพาะของนักศึกษาคนนี้
        ->get();

    // ตรวจสอบข้อมูลกิจกรรมว่าได้รับค่าหรือไม่
    // dd($activities);

    // ส่งข้อมูลกิจกรรมไปยังหน้า view
    return view('student.meet', ['activities' => $activities]);
}


    public function create(Request $request)
    {
    // ใช้ guard 'student' เพื่อรับข้อมูลของนักศึกษาที่ล็อกอินอยู่
    $student = Auth::guard('student')->user();

    // // ดีบั๊ก: ตรวจสอบข้อมูลของตัวแปร $student
    // dd($student);

    // ตรวจสอบว่านักศึกษามีที่ปรึกษาหรือไม่
    if (!$student || !$student->advisor) {
        return redirect()->back()->withErrors(['advisor_id' => 'ยังไม่มีอาจารย์ที่ปรึกษา กรุณาติดต่อผู้ดูแลระบบ']);
    }

    // รับค่าวันที่นัดหมายจาก query parameter
    $meeting_date = $request->query('meeting_date');

    // ส่งข้อมูลไปยังหน้า view สำหรับสร้างการนัดหมาย
    return view('student.createmeet', [
        'students' => Student::all(), // ดึงรายชื่อนักศึกษาทั้งหมด
        'advisors' => Advisor::all(), // ดึงรายชื่ออาจารย์ที่ปรึกษาทั้งหมด
        'meeting_date' => $meeting_date, // ส่งค่าวันที่นัดหมายไปยังหน้า view
    ], compact('student'));
    }


    // Store a new activity
    public function store(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่รับมา
        $request->validate([
            'student_id' => 'required|exists:students,id', // ต้องระบุ student_id และต้องมีอยู่ในตาราง students
            'advisor_id' => 'nullable|exists:advisors,id', // อนุญาตให้เป็นค่าว่างได้ หากไม่มีอาจารย์ที่ปรึกษา
            'meeting_date' => 'required|date', // ต้องระบุวันที่ และต้องเป็นรูปแบบวันที่ที่ถูกต้อง
            'discussion_content' => 'required|string', // ต้องระบุเนื้อหาการสนทนา และต้องเป็นข้อความ
            'evidence.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // หลักฐานต้องเป็นไฟล์รูปภาพที่กำหนด และขนาดไม่เกิน 2MB
        ]);

        // รับค่าข้อมูลจากฟอร์ม
        $data = $request->all();

        // หากไม่ได้เลือกอาจารย์ที่ปรึกษา ระบบจะตั้งค่าเป็นอาจารย์ที่ปรึกษาของนักศึกษาที่ล็อกอินอยู่
        if (empty($data['advisor_id']) && Auth::user()->advisor) {
            $data['advisor_id'] = Auth::user()->advisor->id;
        }

        // จัดการอัปโหลดไฟล์หลักฐาน
        $evidence = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('evidence', 'public'); // จัดเก็บไฟล์ในโฟลเดอร์ 'evidence' บน disk 'public'
                $evidence[] = $path; // เก็บ path ของไฟล์ไว้ใน array
            }
        }
        $data['evidence'] = json_encode($evidence); // แปลง array เป็น JSON เพื่อบันทึกลงฐานข้อมูล

        // บันทึกข้อมูลกิจกรรม (การนัดหมาย)
        Activity::create($data);

        // เปลี่ยนเส้นทางกลับไปยังหน้ารายการนัดหมาย พร้อมแสดงข้อความแจ้งเตือน
        return redirect()->route('meet.meet')->with('success', 'สร้างกิจกรรมสำเร็จ');
    }


// แก้ไขกิจกรรมที่มีอยู่
public function edit($id)
{
    // ค้นหากิจกรรมโดยใช้ ID
    $activity = Activity::findOrFail($id);

    // รับข้อมูลของนักศึกษาที่ล็อกอินอยู่ในปัจจุบัน
    $student = Auth::guard('student')->user(); // ใช้ 'student' แทน 'loggedInStudent'

    // ดึงรายชื่อนักศึกษาและอาจารย์ที่ปรึกษาทั้งหมดสำหรับตัวเลือกใน dropdown
    $students = Student::all();
    $advisors = Advisor::all();

    // แปลงข้อมูลหลักฐานที่บันทึกเป็น JSON ให้เป็น array (ถ้ามี)
    $evidence = json_decode($activity->evidence, true) ?? [];

    // ส่งข้อมูลไปยังหน้า view สำหรับแก้ไขการนัดหมาย
    return view('student.editAppointment', compact('activity', 'students', 'advisors', 'student', 'evidence'));
}




  // อัปเดตกิจกรรมที่มีอยู่
public function update(Request $request, $id)
{
    try {
        // ตรวจสอบความถูกต้องของข้อมูลที่รับมา
        $request->validate([
            'meeting_date' => 'required|date', // ต้องระบุวันที่และอยู่ในรูปแบบที่ถูกต้อง
            'discussion_content' => 'required|string', // ต้องระบุเนื้อหาการสนทนา และต้องเป็นข้อความ
            'evidence.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ตรวจสอบว่าไฟล์แนบเป็นรูปภาพที่ถูกต้อง
        ]);

        // ค้นหากิจกรรมโดยใช้ ID
        $activity = Activity::findOrFail($id);

        // จัดการอัปโหลดไฟล์หลักฐาน (รวมหลักฐานเก่ากับที่อัปโหลดใหม่)
        $evidence = json_decode($activity->evidence, true) ?? [];

        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('evidence', 'public'); // จัดเก็บไฟล์ในโฟลเดอร์ 'evidence' บน disk 'public'
                $evidence[] = $path; // เพิ่มไฟล์ใหม่เข้าไปใน array หลักฐาน
            }
        }

        // อัปเดตฟิลด์หลักฐานในฐานข้อมูล
        $activity->evidence = json_encode($evidence);

        // อัปเดตฟิลด์อื่น ๆ
        $activity->meeting_date = $request->input('meeting_date');
        $activity->discussion_content = $request->input('discussion_content');

        // บันทึกการเปลี่ยนแปลง
        $activity->save();

        // เปลี่ยนเส้นทางกลับไปยังหน้ารายการนัดหมาย พร้อมแสดงข้อความแจ้งเตือน
        return redirect()->route('meet.meet')->with('success', 'อัปเดตกิจกรรมสำเร็จ');

    } catch (\Exception $e) {
        // หากเกิดข้อผิดพลาด ให้ส่งข้อความผิดพลาดกลับเป็น JSON
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


   // อัปเดตข้อมูลกิจกรรมโดยอาจารย์ที่ปรึกษา
public function updateForAdvisor(Request $request, $id)
{
    // ตรวจสอบความถูกต้องของข้อมูลที่รับมา
    $request->validate([
        'advisor_comment' => 'required|string|max:500', // ต้องระบุความคิดเห็นของอาจารย์และจำกัดความยาวไม่เกิน 500 ตัวอักษร
        'status' => 'required|in:Pending,Approved,Rejected', // ต้องระบุสถานะ และต้องเป็นค่า Pending, Approved หรือ Rejected เท่านั้น
    ]);

    // ค้นหากิจกรรมโดยใช้ ID
    $activity = Activity::findOrFail($id);

    // อัปเดตความคิดเห็นของอาจารย์และสถานะของกิจกรรม
    $activity->update([
        'advisor_comment' => $request->input('advisor_comment'),
        'status' => $request->input('status'),
    ]);

    // เปลี่ยนเส้นทางกลับไปยังหน้าปฏิทินการนัดหมาย พร้อมแสดงข้อความแจ้งเตือน
    return redirect()->route('calendar.meet')->with('success', 'อาจารย์ที่ปรึกษาอัปเดตกิจกรรมสำเร็จ');
}



   // ลบกิจกรรมที่มีอยู่
public function destroy($id)
{
    // ค้นหากิจกรรมโดยใช้ ID
    $activity = Activity::findOrFail($id);

    // ลบไฟล์หลักฐานที่เกี่ยวข้อง
    if ($activity->evidence) {
        $evidence = json_decode($activity->evidence, true);
        foreach ($evidence as $file) {
            Storage::disk('public')->delete($file); // ลบไฟล์จากที่เก็บข้อมูล
        }
    }

    // ลบกิจกรรมออกจากฐานข้อมูล
    $activity->delete();

    // เปลี่ยนเส้นทางกลับไปยังหน้ารายการนัดหมาย พร้อมแสดงข้อความแจ้งเตือน
    return redirect()->route('meet.meet')->with('success', 'ลบกิจกรรมสำเร็จ');
}

}
