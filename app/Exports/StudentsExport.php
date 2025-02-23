<?php
namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    /**
     * ดึงข้อมูลจากตาราง students
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Student::select(
            'metric_number',
            'name',
            'gender',
            'race',
            'program_id',
            'semester',
            'advisor_id',
            'phone_number',
            'email',
            'profile_image'
        )->get();
    }

    /**
     * กำหนดหัวข้อคอลัมน์ในไฟล์ Excel
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Metric Number',
            'Name',
            'Gender',
            'Race',
            'Program ID',
            'Semester',
            'Advisor ID',
            'Phone Number',
            'Email',
            'Profile Image'
        ];
    }
}