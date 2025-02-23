@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="text-center mb-4">
        <h1 class="display-4">Advisor Details</h1>
    </div>

    <div class="card shadow-sm border-0 rounded">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">{{ $advisor->name }}</h4>
            <p class="mb-0 small">Advisor ID: {{ $advisor->metric_number }}</p>
        </div>
        <div class="card-body">
            <!-- Display Profile Image -->
            @if($advisor->profile_image)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $advisor->profile_image) }}" alt="Profile Image" class="img-thumbnail rounded-circle border-primary" style="width: 180px; height: 180px;">
                </div>
            @else
                <div class="text-center mb-4">
                    <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Image" class="img-thumbnail rounded-circle border-primary" style="width: 180px; height: 180px;">
                </div>
            @endif

            <!-- Display Advisor Information -->
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Email:</strong> {{ $advisor->email }}</p>
                    <p><strong>Phone Number:</strong> {{ $advisor->phone_number }}</p>
                    <p><strong>Program:</strong> {{ $advisor->program->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Max Students:</strong> {{ $advisor->max_students }}</p>
                    <!-- View students button -->
                    <a href="{{ route('students.index', ['advisor_id' => $advisor->id]) }}" class="btn btn-info ml-2">
                        <i class="fas fa-users"></i> View Students
                    </a>
                    
                    <p><strong>Male Students:</strong> {{ $maleCount }}</p>
                    <p><strong>Female Students:</strong> {{ $femaleCount }}</p>
                </div>
            </div>
        </div>

        <div class="card-footer text-center bg-light">
            <a href="{{ route('advisors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('advisors.edit', $advisor->id) }}" class="btn btn-primary ml-2">
                <i class="fas fa-edit"></i> Edit Advisor
            </a>
        </div>
    </div>
</div>
@endsection
