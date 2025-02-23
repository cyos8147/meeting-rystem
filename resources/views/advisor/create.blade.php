@extends('layouts.app')

@section('title', 'Register Advisor')

@section('content')
    <h1>Register Advisor</h1>
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

        <form action="{{ route('advisors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Profile Image Section -->
            <div class="row justify-content-center mb-3">
                <div class="col-md-6 text-center">
                    <label for="profile_image" class="form-label" style="font-weight: bold; color: #333;">Profile Image  (Required)</label>
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
                </div>
                <div id="imageAlert" class="alert alert-warning" style="display: none; margin-top: 10px;">
                    Please upload a profile image.
                </div>

            </div>

            <!-- Form Fields -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="metric_number" class="form-label">Staff Number</label>
                    <input type="text" class="form-control" id="metric_number" name="metric_number"
                        value="{{ old('metric_number') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                        required>
                </div>
                
              
                <div class="col-md-4 mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="number" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                </div>
                
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                        required pattern="^[\w\.-]+@unimap\.edu\.my$" 
                        title="Please use an email ending with @unimap.edu.my">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="max_students" class="form-label">Max Students</label>
                    <input type="number" class="form-control" id="max_students" name="max_students"
                        value="{{ old('max_students') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="program_type" class="form-label">Program Type</label>
                    <select class="form-control" id="program_type" name="program_type" required>
                        <option value="">Select Program Type</option>
                        <option value="Bachelor">Bachelor</option>
                        <option value="Diploma">Diploma</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="program_id" class="form-label">Program</label>
                    <select class="form-control" id="program_id" name="program_id" required>
                        <option value="">Select Program</option>
                    </select>
                </div>
            </div>

           <!-- Password Field -->
<div class="col-md-6 mb-3">
    <label for="password" class="form-label">Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password" name="password" required>
        <button type="button" class="btn btn-outline-secondary" id="togglePassword1" style="cursor: pointer;">
            <i class="fa fa-eye" id="passwordIcon1"></i>
        </button>
    </div>
</div>

<!-- Confirm Password Field -->
<div class="col-md-6 mb-3">
    <label for="password_confirmation" class="form-label">Confirm Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        <button type="button" class="btn btn-outline-secondary" id="togglePassword2" style="cursor: pointer;">
            <i class="fa fa-eye" id="passwordIcon2"></i>
        </button>
    </div>
</div>


            <!-- Registration Button -->
            <div class="row justify-content-center mt-3">
                <div class="col-md-4 text-center">
                    <button type="submit" class="btn btn-primary" style="width: 50%;">Register Advisor</button>
                </div>
            </div>
        </form>

    </div>

    <script>
        // Toggle password visibility
function togglePasswordVisibility(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const passwordIcon = document.getElementById(iconId);

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

// Event listeners for password visibility toggle
document.getElementById('togglePassword1').addEventListener('click', function () {
    togglePasswordVisibility('password', 'passwordIcon1');
});
document.getElementById('togglePassword2').addEventListener('click', function () {
    togglePasswordVisibility('password_confirmation', 'passwordIcon2');
});

    
        // Show programs based on selected program type
        document.getElementById('program_type').addEventListener('change', function() {
            const selectedType = this.value;
            const programSelect = document.getElementById('program_id');
            programSelect.innerHTML = '<option value="">Select Program</option>';
    
            const programsByType = @json($programsByType);
    
            if (selectedType && programsByType[selectedType]) {
                programsByType[selectedType].forEach(function(program) {
                    const option = document.createElement('option');
                    option.value = program.id;
                    option.textContent = program.name;
                    programSelect.appendChild(option);
                });
            }
        });
    
        // Image preview function
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image_preview');
            const imageAlert = document.getElementById('imageAlert');
    
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    imageAlert.style.display = 'none'; // Hide alert if image is selected
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                imageAlert.style.display = 'block'; // Show alert if no image selected
            }
        }
    
        // Before form submission, check if an image is uploaded
        document.querySelector('form').addEventListener('submit', function(event) {
            const profileImage = document.getElementById('profile_image').files[0];
            const imageAlert = document.getElementById('imageAlert');
    
            // If no image is selected, show the alert and prevent form submission
            if (!profileImage) {
                imageAlert.style.display = 'block';
                event.preventDefault(); // Prevent form submission
            }
        });
        
    </script>
    
@endsection
