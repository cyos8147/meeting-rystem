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

    public function index(Request $request)
    {

        $query = Student::query();

        if ($request->has('advisor_id')) {
            $query->where('advisor_id', $request->advisor_id);
        }

        $students = $query->get(); // ดึงข้อมูลนักเรียนที่กรองแล้ว

        return view('student.list', compact('students'));
    }

    public function indexforadvisor(Request $request)
    {
        $advisor = Auth::guard('advisor')->user();
        $query   = Student::query();

                                    // Retrieve all advisors to pass to the view
        $advisors = Advisor::all(); // Assuming you have an Advisor model for advisors

        // Check if advisor_id is passed via the request
        if ($request->has('advisor_id')) {
            $query->where('advisor_id', $request->advisor_id);
        } else {
            $query->where('advisor_id', $advisor->id);
        }

        // Apply search filter if search term is provided
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('metric_number', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

                                                                                      // Apply sorting if parameters are passed
        $validSortColumns = ['metric_number', 'name', 'gender', 'semester', 'email']; // Removed 'advisor' from the list
        if ($request->has('sort_by') && in_array($request->sort_by, $validSortColumns)) {
            $sortBy        = $request->sort_by;
            $sortDirection = $request->get('sort_direction', 'asc'); // Default to ascending
            $query->orderBy($sortBy, $sortDirection);
        }

                                                                 // Ensure $sortDirection is defined for sorting by advisor's name
        $sortDirection = $request->get('sort_direction', 'asc'); // Default to ascending if not provided

        // If sorting by advisor's name, join the advisors table
        if ($request->has('sort_by') && $request->sort_by == 'advisor') {
            $query->join('advisors', 'students.advisor_id', '=', 'advisors.id')
                ->orderBy('advisors.name', $sortDirection);
        }

        // Pagination - Get 10 students per page
        $students = $query->paginate(10);

        // Return the view with the students data and advisors
        return view('advisor.ShowStudent', compact('students', 'advisor', 'advisors'));
    }

    public function indexforAdmin(Request $request)
    {
        $search        = $request->get('search');
        $filter        = $request->get('filter', 'name');
        $sortBy        = $request->get('sortBy', 'name');
        $sortDirection = $request->get('sortDirection', 'asc');

        $students = Student::query();

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

        if ($sortBy == 'program') {
            $students = $students->join('programs', 'programs.id', '=', 'students.program_id')
                ->orderBy('programs.name', $sortDirection);
        } elseif ($sortBy == 'advisor') {
            $students = $students->join('advisors', 'advisors.id', '=', 'students.advisor_id')
                ->orderBy('advisors.name', $sortDirection);
        } else {
            $students = $students->orderBy($sortBy, $sortDirection);
        }

        $students = $students->paginate(5);

        return view('auth.admin.listStudent', compact('students', 'sortBy', 'sortDirection'));
    }

    public function show($id)
    {
        $student = Student::with('advisor')->findOrFail($id);
        return view('auth.admin.showStudent', compact('student'));
    }

    public function edit($id)
    {
        $student   = Student::findOrFail($id);
        $advisors  = Advisor::all();
        $isEditing = true;

        return view('auth.admin.editStudents', compact('student', 'advisors', 'isEditing'));
    }

    public function showProfile($id)
    {
        $student = Student::with('advisor')->findOrFail($id);
        return view('student.show', compact('student'));
    }

    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        return view('student.dashboard', compact('student'));
    }

    public function meet()
    {
        $student    = Auth::guard('student')->user();
        $activities = Activity::with(['student', 'advisor'])
            ->where('student_id', $student->id)
            ->get();

        return view('student.meet', compact('activities', 'student'));
    }

    public function create()
    {
        $advisors = Advisor::all();
        $programs = Program::has('advisors')->get(); // Filter programs with advisors

        return view('student.create', compact('advisors', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metric_number' => 'required|unique:students',
            'name'          => 'required',
            'gender'        => 'required',
            'race'          => 'required',
            'program_id'    => 'required|exists:programs,id',
            'semester'      => 'required|integer',
            'email'         => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|unique:students',
            'password'      => 'required|confirmed',
            'phone_number'  => 'required|numeric',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
        ]);

        try {
            // If a profile image is uploaded, store it
            $profileImagePath = $request->hasFile('profile_image')
            ? $request->file('profile_image')->store('profile_images', 'public')
            : null;

            // Retrieve program and advisors
            $program  = Program::findOrFail($request->program_id);
            $advisors = Advisor::where('program_id', $request->program_id)->get();

            // Set default reason
            $reason = null;

            // Assign a random advisor
            $advisor = $this->assignRandomAdvisor($program, $advisors, $request->gender, $request->race, $reason);

            // Check if no advisor is available
            if (! $advisor) {
                return redirect()->back()->withInput()->withErrors([
                    'error' => $reason,
                ]);
            }

            // Create a new student
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
                'password'      => bcrypt($request->password),
                'advisor_id'    => $advisor->id,
            ]);

            $student->save();

            // Clear old input data and redirect with success message
            session()->forget('_old_input');

            return redirect()->route('students.indexforAdmin')->with('success', 'Student registered successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }

    private function assignRandomAdvisor($program, $advisors, $studentGender, $studentRace, &$reason)
    {
        // Check if there are advisors available to accept new students
        $availableAdvisors = $advisors->filter(function ($advisor) {
            return $advisor->max_students > $advisor->students()->count();
        });

        if ($availableAdvisors->isEmpty()) {
            $reason = 'No advisors are currently available to accept new students.';
            return null;
        }

        // Separate advisors by gender
        $maleAdvisors   = $availableAdvisors->where('gender', 'Male');
        $femaleAdvisors = $availableAdvisors->where('gender', 'Female');

        // Filter advisors by race
        $filteredMaleAdvisors = $maleAdvisors->filter(function ($advisor) use ($studentRace) {
            return $advisor->students()->where('race', $studentRace)->count() < $advisor->max_students;
        });

        $filteredFemaleAdvisors = $femaleAdvisors->filter(function ($advisor) use ($studentRace) {
            return $advisor->students()->where('race', $studentRace)->count() < $advisor->max_students;
        });

        // Select advisors based on gender
        $advisor = null;
        if ($studentGender === 'Male') {
            $advisor = $filteredMaleAdvisors->sortBy(fn($advisor) => $advisor->students()->count())->first();
        } else {
            $advisor = $filteredFemaleAdvisors->sortBy(fn($advisor) => $advisor->students()->count())->first();
        }

        // If no advisors match the gender and race conditions
        if (! $advisor) {
            $reason = 'Advisors matching the specified conditions have reached their student capacity.';
        }

        // As a last resort: Choose any available advisor with remaining capacity
        if (! $advisor) {
            $advisor = $availableAdvisors->sortBy(fn($advisor) => $advisor->students()->count())->first();
        }

        return $advisor;
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
        // First, handle the nullifying of foreign key relationships manually, if needed
        $student = Student::findOrFail($id);

                                     // Nullify foreign key relationships instead of deleting them
        $student->advisor_id = null; // Set advisor_id to null
        $student->program_id = null; // Set program_id to null

        // Save the changes to nullify the foreign keys
        $student->save();

                                          // Optionally delete other data like activities or profile image (if needed)
        $student->activities()->delete(); // Delete activities associated with the student

        // Delete profile image if exists
        if ($student->profile_image) {
            Storage::disk('public')->delete($student->profile_image);
        }

        // Now perform a raw DELETE query to delete the student record
        DB::table('students')->where('id', $id)->delete();

        // Redirect back to the student list with a success message
        return redirect()->route('students.indexforAdmin')->with('success', 'Student and related records deleted successfully');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout(); // For admin guard
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function import(Request $request)
    {
        // ตรวจสอบว่าอัปโหลดไฟล์ถูกต้อง
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // นับจำนวนนักเรียนก่อน Import
            $beforeCount = \App\Models\Student::count();

            // นำเข้าข้อมูลจากไฟล์ Excel
            Excel::import(new StudentsImport, $request->file('file'));

            // นับจำนวนนักเรียนหลัง Import
            $afterCount = \App\Models\Student::count();

            // ตรวจสอบว่ามีการเพิ่มข้อมูลหรือไม่
            if ($afterCount > $beforeCount) {
                return redirect()->back()->with('swal', [
                    'type'  => 'success',
                    'title' => 'Data imported successfully!',
                    'text'  => '✅ Data imported successfully!',
                ]);
            } else {
                return redirect()->back()->with('swal', [
                    'type'  => 'error',
                    'title' => 'No data was imported',
                    'text'  => '❌ No data was imported. Please check your file.',
                ]);
            }
        } catch (\Exception $e) {
            // หากมีข้อผิดพลาดในการนำเข้าแสดงผล
            $errors   = session('import_errors', []);
            $messages = [];

            // วนลูปข้อผิดพลาดและเพิ่มข้อความให้ละเอียด
            foreach ($errors as $error) {
                $messages[] = "Row {$error['row']}: Missing fields - " . implode(', ', $error['missing_fields']);
            }

            return redirect()->back()->with('swal', [
                'type'  => 'error',
                'title' => 'Import Error',
                'text'  => '❌ ' . implode('<br>', $messages),
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }
}
