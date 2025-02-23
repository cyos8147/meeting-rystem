{{-- @extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container" style="max-height: 80vh; overflow-y: auto; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
    <h1>Edit Student</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="metric_number" class="form-label">Metric Number</label>
            <input type="text" class="form-control" id="metric_number" name="metric_number" value="{{ old('metric_number', $student->metric_number) }}" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $student->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="semester" class="form-label">Semester</label>
            <select class="form-control" id="semester" name="semester" required>
                <option value="">Select a Semester</option>
                @for ($i = 1; $i <= 8; $i++)
                    <option value="{{ $i }}" {{ old('semester', $student->semester) == $i ? 'selected' : '' }}>
                        Semester {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="advisor_id" class="form-label">Advisor (Optional)</label>
            <select class="form-control" id="advisor_id" name="advisor_id">
                <option value="">Select an Advisor</option>
                @foreach($advisors as $advisor)
                    <option value="{{ $advisor->id }}" {{ old('advisor_id', $student->advisor_id) == $advisor->id ? 'selected' : '' }}>
                        {{ $advisor->name }} ({{ $advisor->program->name }} - {{ $advisor->program->type }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $student->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <div class="mb-3">
            <label for="profile_image" class="form-label">Profile Image (Optional)</label>
            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
            @if($student->profile_image)
                <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile Image" width="100" height="100">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Student</button>
    </form>
</div>
@endsection --}}
