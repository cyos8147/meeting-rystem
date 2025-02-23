{{-- resources/views/student/list.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('advisors.create') }}" class="btn btn-success btn-lg ml-3">
        <i class="fas fa-plus-circle"></i> Create Advisor
    </a>

    <!-- Table to display students -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Metric Number</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Program</th>
                <th>Semester</th>
                <th>Advisor</th>
                <th>Email</th>
                <th>Profile Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->metric_number }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->gender }}</td>
                <td>{{ $student->advisor ? $student->advisor->program : 'N/A' }}</td> <!-- Display program from advisor -->
                <td>{{ $student->semester }}</td>
                <td>{{ $student->advisor ? $student->advisor->name : 'N/A' }}</td> <!-- Show advisor's name if available -->
                <td>{{ $student->email }}</td>
                <td>
                    @if($student->profile_image)
                        <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile Image" width="50">
                    @else
                        No image
                    @endif
                </td>
                <td>
                    <!-- Edit and Delete Actions -->
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <!-- Delete Form -->
                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
