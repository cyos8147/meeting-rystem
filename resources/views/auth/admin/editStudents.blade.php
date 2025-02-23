@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="text-center mb-4">
            {{-- <h1 class="display-4">Edit Student</h1>
            <p class="text-muted">Manage Student Information</p> --}}
        </div>

        <div class="card shadow-sm border-0 rounded">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-center">Edit Student</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                    <div class="row">
                        <!-- Metric Number -->
                        <div class="col-md-6 mb-3">
                            <label for="metric_number" class="form-label">Metric Number</label>
                            <input type="text" name="metric_number" id="metric_number" class="form-control"
                                value="{{ old('metric_number', $student->metric_number) }}" required>
                                @error('metric_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $student->name) }}" required>
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Gender -->
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>
                                    Male</option>
                                <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>
                                    Female</option>
                            </select>
                            @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Race -->
                        <div class="col-md-6 mb-3">
                            <label for="race" class="form-label">Race</label>
                            <select name="race" id="race" class="form-control">
                                <option value="">Select Race</option>
                                <option value="Malay" {{ old('race', $student->race) == 'Malay' ? 'selected' : '' }}>Malay
                                </option>
                                <option value="Chinese" {{ old('race', $student->race) == 'Chinese' ? 'selected' : '' }}>
                                    Chinese</option>
                                <option value="Indian" {{ old('race', $student->race) == 'Indian' ? 'selected' : '' }}>
                                    Indian</option>
                                <option value="Other" {{ old('race', $student->race) == 'Other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                            @error('race')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>


                        <!-- Semester -->
                        <div class="col-md-6 mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="number" name="semester" id="semester" class="form-control"
                                value="{{ old('semester', $student->semester) }}" min="1" max="8" required>
                        </div>
                        @error('semester')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    </div>

                    <div class="row">
                        <!-- Phone Number -->
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control"
                                value="{{ old('phone_number', $student->phone_number) }}" required>
                                @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Advisor -->
                        <div class="col-md-6 mb-3">
                            <label for="advisor_id" class="form-label">Advisor</label>
                            <select name="advisor_id" id="advisor_id" class="form-control">
                                <option value="">None</option>
                                @foreach ($advisors as $advisor)
                                    <option value="{{ $advisor->id }}"
                                        {{ old('advisor_id', $student->advisor_id) == $advisor->id ? 'selected' : '' }}>
                                        {{ $advisor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('advisor_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email', $student->email) }}" required>
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">Show</button>
                                </div>
                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            </div>


                    <!-- Profile Image -->
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" id="profile_image" class="form-control file-input" accept="image/*" onchange="previewImage(event)">
                        <div class="mt-3 text-center">
                            <img id="preview"
                                src="{{ $student->profile_image ? asset('storage/' . $student->profile_image) : asset('images/default-profile.png') }}"
                                alt="Profile Preview" class="img-thumbnail" style="width: 150px; height: 150px;">
                        </div>
                        @error('profile_image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    </div>
                    
                    
                    <script>
                        function previewImage(event) {
                            const preview = document.getElementById('preview');
                            const file = event.target.files[0];
                    
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    preview.src = e.target.result;
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    </script>
                    <script>
                        // Function to toggle password visibility
                       document.getElementById('toggle-password').addEventListener('click', function() {
                           var passwordField = document.getElementById('password');
                           var type = passwordField.type === 'password' ? 'text' : 'password';
                           passwordField.type = type;
                       
                           // Set button text based on password field state
                           if (passwordField.value === '' && type === 'text') {
                               this.textContent = 'Show';
                           } else {
                               this.textContent = type === 'password' ? 'Show' : 'Hide';
                           }
                       });
                       
                       
                           // Function to preview image before upload
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
                    

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Update Student</button>
                        <a href="{{ route('students.indexforAdmin') }}" class="btn btn-secondary">Cancel</a>
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
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
