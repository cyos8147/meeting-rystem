<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); // Ensure the correct view is returned
    }

    // Show the registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }


    // Show the admin login form
    public function showAdminLoginForm()
    {
        return view('auth.admin.login');
    }

    // Handle the admin login request
    public function adminLogin(Request $request)
    {

        // Validate the request
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Attempt login
        if (Auth::guard('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            return redirect()->route('admin.dashboard');
        }

        // If login fails, redirect back with error
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }


    public function login(Request $request)
    {
        $credentials = $request->only(['login_input', 'password']);

        // ตรวจสอบว่าเป็น Email หรือ Metric Number
        if (filter_var($credentials['login_input'], FILTER_VALIDATE_EMAIL)) {
            $request->validate([
                'login_input' => 'required|email',
                'password'    => 'required|min:8',
            ]);

            // ล็อกอิน Advisor ด้วย Email
            if (Auth::guard('advisor')->attempt(['email' => $credentials['login_input'], 'password' => $credentials['password']])) {
                return redirect()->route('advisor.dashboard')->with(['advisor' => Auth::guard('advisor')->user()]);
            }
        } else {
            $request->validate([
                'login_input' => 'required',
                'password'    => 'required',
            ]);

            // ล็อกอิน Student
            if (Auth::guard('student')->attempt(['metric_number' => $credentials['login_input'], 'password' => $credentials['password']])) {
                return redirect()->route('student.dashboard')->with(['student' => Auth::guard('student')->user()]);
            }

            // ล็อกอิน Advisor ด้วย Metric Number
            if (Auth::guard('advisor')->attempt(['metric_number' => $credentials['login_input'], 'password' => $credentials['password']])) {
                return redirect()->route('advisor.dashboard')->with(['advisor' => Auth::guard('advisor')->user()]);
            }
        }

        return redirect()->back()->withErrors(['error' => '❌ Invalid Email/Metric Number or Password.']);
    }


    public function logout()
    {
        Auth::logout(); // ออกจากระบบทุก guard
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }

}
