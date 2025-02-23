<?php
namespace App\Imports;

use App\Models\Student;
use App\Models\Advisor;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StudentsImport implements ToModel, WithValidation, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    // กำหนดคอลัมน์ที่จำเป็นต้องมี
    private $requiredColumns = [
        'metric_number', 'name', 'gender', 'race',
        'program_id', 'semester', 'phone_number', 'email'
    ];  

    /**
     * ตรวจสอบคอลัมน์ก่อนนำเข้า
     */
    public function validateColumns(array $headingRow)
    {
        $missingColumns = array_diff($this->requiredColumns, $headingRow);

        if (!empty($missingColumns)) {
            throw ValidationException::withMessages([
                'file' => 'Missing required columns: ' . implode(', ', $missingColumns),
            ]);
        }
    }

    /**
     * กำหนดกฎการตรวจสอบข้อมูล
     */
    public function rules(): array
    {
        return [
            'metric_number' => 'required|unique:students,metric_number',
            'email'         => 'required|email|unique:students,email',
            'phone_number'  => 'nullable|numeric',
            'gender'        => 'required|in:Male,Female',
            'program_id'    => 'required|exists:programs,id',
        ];
    }

    /**
     * ฟังก์ชันหลักสำหรับนำเข้าข้อมูล
     */
    public function model(array $row)
    {
        // ตรวจสอบคอลัมน์
        $this->validateColumns(array_keys($row));
    
        // ตรวจสอบว่ามีฟิลด์ที่จำเป็นหายไปหรือไม่
        $missingFields = [];
        $requiredFields = [
            'metric_number', 'name', 'gender', 'race',
            'program_id', 'semester', 'phone_number', 'email'
        ];
    
        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                $missingFields[] = $field;
            }
        }
    
        if (!empty($missingFields)) {
            session()->push('import_errors', [
                'row' => $row['row_number'] ?? 'Unknown',
                'missing_fields' => $missingFields
            ]);
        }
    
        // แปลงค่า gender
        $gender = strtolower($row['gender']) === 'male' ? 'Male' : 'Female';
    
        // แปลงค่า race
        $race = ucfirst(strtolower($row['race'])); // Malay, Chinese, Indian
    
        // กำหนดรหัสผ่านเริ่มต้น
        $password = bcrypt('default_password');
    
        // ค้นหา advisor ที่เหมาะสม
        $programId = $row['program_id'];
        $advisors = Advisor::where('program_id', $programId)->get();
    
        // กรองที่ปรึกษาที่สามารถรับเพิ่มได้
        $advisor = $advisors->filter(function ($advisor) use ($gender, $race) {
            $totalStudents = $advisor->students()->count();
            $genderCount = $advisor->students()->where('gender', $gender)->count();
            $raceCount = $advisor->students()->where('race', $race)->count();
    
            return $totalStudents < $advisor->max_students &&
                   $genderCount < ($advisor->max_students / 2) &&
                   $raceCount < ($advisor->max_students / 3); // ควรแบ่ง 3 เชื้อชาติเท่าๆ กัน
        })->sortBy(function ($advisor) {
            return $advisor->students()->count();
        })->first();
    
        // 👉 จัดการอัปโหลดรูปภาพจาก Excel ถ้ามี
        $profileImage = null;
        if (isset($row['profile_image']) && $row['profile_image']) {
            // อัปโหลดไฟล์จาก path หรือ URL ที่ระบุใน Excel
            $imagePath = $row['profile_image'];
            $profileImage = Storage::disk('public')->put('profile_images', file_get_contents($imagePath));
        }
    
        return new Student([
            'metric_number' => $row['metric_number'],
            'name'          => $row['name'],
            'gender'        => $gender,
            'race'          => $race,
            'program_id'    => $programId,
            'semester'      => $row['semester'],
            'advisor_id'    => $advisor ? $advisor->id : null,
            'phone_number'  => $row['phone_number'],
            'email'         => $row['email'],
            'password'      => $password,
            'profile_image' => $profileImage, // 👉 บันทึกชื่อไฟล์รูปภาพลงในฐานข้อมูล
        ]);
    }
    
}

