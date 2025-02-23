@extends('layouts.app')

@section('title', 'Register Student')

@section('content')
    <h1>Register Student</h1>
    <div class="container">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Profile Image Section -->
            <div class="row justify-content-center mb-3">
                <div class="col-md-6 text-center">
                    <label for="profile_image" class="form-label" style="font-weight: bold; color: #333;">Profile Image
                        (Required)</label>
                    <label for="profile_image" class="btn btn-light rounded-circle"
                        style="padding: 20px; cursor: pointer; border: 2px dashed #007bff; background-color: #f8f9fa;">
                        <i class="fa fa-camera" style="font-size: 24px; color: #007bff;"></i>
                    </label>

                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*"
                        onchange="previewImage(event)" style="display: none;">
                    <div class="d-flex justify-content-center mt-3">
                        <img id="image_preview" src="#" alt="Profile Image Preview"
                            style="display: none; width: 200px; height: 200px; border: 2px solid #007bff; border-radius: 50%;">
                    </div>
                    <div id="imageAlert" class="alert alert-danger mt-3" style="display: none;">Please select a profile
                        image.</div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="metric_number" class="form-label">Metric Number</label>
                    <input type="text" class="form-control" id="metric_number" name="metric_number"
                        value="{{ old('metric_number') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
            
                
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-control" id="semester" name="semester" required>
                        <option value="">Select a Semester</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                                Semester {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="program_type" class="form-label">Program Type</label>
                    <select class="form-control" id="program_type" name="program_type" required>
                        <option value="">Select Program Type</option>
                        <option value="Diploma" {{ old('program_type') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                        <option value="Bachelor" {{ old('program_type') == 'Bachelor' ? 'selected' : '' }}>Bachelor
                        </option>
                    </select>
                </div>
                <div class="col-md-4 mb-3" id="program_section" style="display: none;">
                    <label for="program_id" class="form-label">Program</label>
                    <select class="form-control" id="program_id" name="program_id" required>
                        <option value="">Select a Program</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}" class="{{ $program->type }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="race" class="form-label">Race</label>
                    <select class="form-control" id="race" name="race" required>
                        <option value="">Select Race</option>
                        <option value="Malay" {{ old('race') == 'Malay' ? 'selected' : '' }}>Malay</option>
                        <option value="Chinese" {{ old('race') == 'Chinese' ? 'selected' : '' }}>Chinese</option>
                        <option value="Indian" {{ old('race') == 'Indian' ? 'selected' : '' }}>Indian</option>
                        <option value="Others" {{ old('race') == 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email') }}" required
                        pattern="^[\w\.-]+@unimap\.edu\.my$"
                        title="Please use an email ending with @unimap.edu.my">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="number" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                            style="cursor: pointer;">
                            <i class="fa fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" value="{{ old('password_confirmation') }}" required>
                        <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword"
                            style="cursor: pointer;">
                            <i class="fa fa-eye" id="confirmPasswordIcon"></i>
                        </button>
                    </div>
                </div>
            </div>
            

            <!-- Registration Button -->
            <div class="row justify-content-center mt-3">
                <div class="col-md-4 text-center">
                    <button type="submit" class="btn btn-primary" style="width: 50%;">Register</button>
                </div>
            </div>

        </form>

    </div>
    <script src="{{ asset('js/Createstudent.js') }}"></script>
@endsection
