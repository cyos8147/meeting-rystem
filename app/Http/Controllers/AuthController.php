<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   // แสดงฟอร์มเข้าสู่ระบบ
public function showLoginForm()
{
    return view('auth.login'); // แสดงวิวสำหรับฟอร์มการเข้าสู่ระบบ
}

// แสดงฟอร์มลงทะเบียน
public function showRegisterForm()
{
    return view('auth.register'); // แสดงวิวสำหรับฟอร์มการลงทะเบียน
}

// แสดงฟอร์มเข้าสู่ระบบสำหรับผู้ดูแลระบบ
public function showAdminLoginForm()
{
    return view('auth.admin.login'); // แสดงวิวสำหรับฟอร์มการเข้าสู่ระบบของผู้ดูแลระบบ
}

// จัดการคำขอเข้าสู่ระบบของผู้ดูแลระบบ
public function adminLogin(Request $request)
{
    // ตรวจสอบความถูกต้องของข้อมูลที่ได้รับ
    $validated = $request->validate([
        'email'    => 'required|email', // ต้องกรอกอีเมลที่ถูกต้อง
        'password' => 'required|min:8',  // ต้องกรอกรหัสผ่านที่มีความยาวอย่างน้อย 8 ตัวอักษร
    ]);

    // พยายามเข้าสู่ระบบ
    if (Auth::guard('admin')->attempt([
        'email'    => $request->email,
        'password' => $request->password,
    ])) {
        return redirect()->route('admin.dashboard'); // ถ้าสำเร็จ เปลี่ยนเส้นทางไปยังแดชบอร์ดของผู้ดูแลระบบ
    }

    // หากเข้าสู่ระบบล้มเหลว ให้แสดงข้อผิดพลาดและเปลี่ยนเส้นทางกลับ
    return back()->withErrors(['email' => 'Invalid credentials'])->withInput(); // แสดงข้อผิดพลาด "ข้อมูลรับรองไม่ถูกต้อง"
}


public function login(Request $request)
{
    $credentials = $request->only(['login_input', 'password']);

    // ตรวจสอบว่าเป็น Email หรือ Metric Number
    if (filter_var($credentials['login_input'], FILTER_VALIDATE_EMAIL)) {
        $request->validate([
            'login_input' => 'required|email', // ตรวจสอบว่าเป็นอีเมล
            'password'    => 'required|min:8',  // รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร
        ]);

        // ล็อกอิน Advisor ด้วย Email
        if (Auth::guard('advisor')->attempt(['email' => $credentials['login_input'], 'password' => $credentials['password']])) {
            return redirect()->route('advisor.dashboard')->with(['advisor' => Auth::guard('advisor')->user()]);
        }
    } else {
        $request->validate([
            'login_input' => 'required', // ตรวจสอบว่าได้กรอกข้อมูล
            'password'    => 'required', // ตรวจสอบรหัสผ่าน
        ]);

        // ล็อกอิน Student ด้วย Metric Number
        if (Auth::guard('student')->attempt(['metric_number' => $credentials['login_input'], 'password' => $credentials['password']])) {
            return redirect()->route('student.dashboard')->with(['student' => Auth::guard('student')->user()]);
        }

        // ล็อกอิน Advisor ด้วย Metric Number
        if (Auth::guard('advisor')->attempt(['metric_number' => $credentials['login_input'], 'password' => $credentials['password']])) {
            return redirect()->route('advisor.dashboard')->with(['advisor' => Auth::guard('advisor')->user()]);
        }
    }

    // ถ้าข้อมูลไม่ถูกต้องให้แสดงข้อผิดพลาด
    return redirect()->back()->withErrors(['error' => '❌ Invalid Email/Metric Number or Password.']);
}

public function logout()
{
    Auth::logout(); // ออกจากระบบทุก guard
    session()->invalidate(); // ยกเลิกการใช้งาน session
    session()->regenerateToken(); // สร้าง token ใหม่เพื่อป้องกัน CSRF
    return redirect()->route('login'); // เปลี่ยนเส้นทางไปยังหน้าล็อกอิน
}


}
