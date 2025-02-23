@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="text-center mb-4">
    </div>
        
    
    <div class="card shadow-sm border-0 rounded">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="h3">Student Details</h5>     
        </div>
        <div class="card-body">
            <!-- Display Profile Image -->
            @if ($student->profile_image)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile Image" 
                         class="img-thumbnail rounded-circle border-primary" 
                         style="width: 180px; height: 180px;">
                </div>
            @else
                <div class="text-center mb-4">
                    <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Image" 
                         class="img-thumbnail rounded-circle border-primary" 
                         style="width: 180px; height: 180px;">
                </div>
            @endif

            <!-- Display Student Information -->
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Metric Number: </strong> {{ $student->metric_number }}</p>
                    <p><strong>Email:</strong> {{ $student->email }}</p>
                    <p><strong>Phone Number:</strong> {{ $student->phone_number ?? 'N/A' }}</p>
                    <p><strong>Gender:</strong> {{ $student->gender }}</p> <p><strong>RACE</strong> {{ $student->race }}</p> 
                </div>
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $student->name }}</p>
                    <p><strong>Program:</strong> 
                        {{ $student->advisor ? $student->advisor->program->name . ' (' . $student->advisor->program->type . ')' : 'N/A' }}
                    </p>
                    <p><strong>Semester:</strong> {{ $student->semester }}</p>
                    <p><strong>Advisor:</strong> {{ $student->advisor->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer text-center bg-light">
            <a href="{{ route('students.indexforAdmin') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary ml-2">
                <i class="fas fa-edit"></i> Edit Student
            </a>
        </div>
    </div>
</div>
@endsection
