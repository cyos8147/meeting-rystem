@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header" style="background-color: rgba(67, 70, 157, 1); color: white; text-align: center;">
            <h2 class="fw-bold">Student Details</h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4 text-center">
                    @if($student->profile_image)
                        <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile Image" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="d-flex justify-content-center align-items-center bg-secondary text-white rounded-circle" style="width: 150px; height: 150px;">
                            No Image
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h4 class="mb-3"><strong>Full Name:</strong> {{ $student->name }}</h4>
                    <p><strong>Metric Number:</strong> {{ $student->metric_number }}</p>
                    <p><strong>Gender:</strong> {{ $student->gender }}</p>
                    <p><strong>Email:</strong> {{ $student->email }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Program:</strong> {{ $student->advisor ? $student->advisor->program->type : 'N/A' }} {{ $student->advisor ? $student->advisor->program->name : 'N/A' }}</p>
                    <p><strong>Semester:</strong> {{ $student->semester }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Advisor:</strong> {{ $student->advisor->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer text-center bg-light">
            <a href="{{ route('meet.meet') }}" class="btn btn-primary">Schedule a Meeting</a>
        </div>
    </div>
</div>
@endsection
