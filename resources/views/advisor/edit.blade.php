@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="text-center mb-4">
        <h1 class="display-4">Edit Advisor</h1>
        <p class="text-muted">Faculty of Academic Excellence</p>
    </div>

    <div class="card shadow-sm border-0 rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">Update Advisor Information</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('advisors.update', $advisor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Name -->
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $advisor->name) }}" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $advisor->email) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" required value="{{ old('phone_number', $advisor->phone_number) }}">
                    </div>
                    
                </div>

                <div class="row">
                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
                    </div>

                    <!-- Metric Number -->
                    <div class="col-md-6 mb-3">
                        <label for="metric_number" class="form-label">Metric Number</label>
                        <input type="text" name="metric_number" id="metric_number" class="form-control" value="{{ old('metric_number', $advisor->metric_number) }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Max Students -->
                    <div class="col-md-6 mb-3">
                        <label for="max_students" class="form-label">Max Students</label>
                        <input type="number" name="max_students" id="max_students" class="form-control" value="{{ old('max_students', $advisor->max_students) }}" required>
                    </div>

                    <!-- Program -->
                    <div class="col-md-6 mb-3">
                        <label for="program" class="form-label">Program</label>
                        <input type="text" name="program" id="program" class="form-control" value="{{ old('program', $advisor->program->name ?? '') }}" required>
                    </div>
                </div>

                <!-- Profile Image -->
                <div class="mb-3">
                    <label for="profile_image" class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <div class="mt-3 text-center">
                        <img id="preview" src="{{ $advisor->profile_image ? asset('storage/' . $advisor->profile_image) : asset('images/default-profile.png') }}" 
                             alt="Profile Preview" class="img-thumbnail" style="width: 150px; height: 150px;">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Update Advisor</button>
                    <a href="{{ route('advisors.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
