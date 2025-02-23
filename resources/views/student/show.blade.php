@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column align-items-center">
    <div class="card w-75 w-md-65 p-4 shadow-lg">
        <div class="text-center mb-4">
            <h2 class="mb-0 title-bar">Student Details</h2>
            <br>
            <br>
            @if($student->profile_image)
                <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile Image" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <div class="text-muted">No Image</div>
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                <div class="mb-2">
                    <div class="p-3 border rounded shadow-sm">
                        <div class="mb-3">
                            <strong>Metric Number:</strong> <span class="text-muted">{{ $student->metric_number }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Name:</strong> <span class="text-muted">{{ $student->name }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Gender:</strong> <span class="text-muted">{{ $student->gender }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Program:</strong> <span class="text-muted">{{ $student->advisor ? $student->advisor->program : 'N/A' }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Semester:</strong> <span class="text-muted">{{ $student->semester }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Advisor:</strong> <span class="text-muted">{{ $student->advisor->name ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong> <span class="text-muted">{{ $student->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <a href="{{ route('students.index') }}" class="btn btn-secondary mt-4">Back to List</a> --}}
    </div>
</div>
@endsection
