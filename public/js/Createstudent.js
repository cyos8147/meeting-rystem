// JavaScript to toggle the visibility of program sections based on program type
document.addEventListener('DOMContentLoaded', function () {
    const programTypeSelect = document.getElementById('program_type');
    const programSection = document.getElementById('program_section');
    const programOptions = document.querySelectorAll('#program_id option');

    programTypeSelect.addEventListener('change', function () {
        const selectedType = programTypeSelect.value;

        // Toggle visibility of the Program section based on program type
        programSection.style.display = selectedType ? 'block' : 'none';

        // Filter programs based on selected program type
        programOptions.forEach(option => {
            option.style.display = option.classList.contains(selectedType) || !selectedType ? 'block' : 'none';
        });
    });

    programTypeSelect.dispatchEvent(new Event('change'));
});

// Function to preview image before uploading
function previewImage(event) {
    const reader = new FileReader();
    const imagePreview = document.getElementById('image_preview');

    reader.onload = function() {
        imagePreview.src = reader.result;
        imagePreview.style.display = 'block';  // Show the image preview
    };

    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}


document.querySelector('form').addEventListener('submit', function(event) {
    const profileImage = document.getElementById('profile_image');
    const imageAlert = document.getElementById('imageAlert');

    if (profileImage.files.length === 0) {
        event.preventDefault();
        imageAlert.style.display = 'block';

        // ชั่วคราวแสดงให้ input สามารถ focus ได้
        profileImage.style.display = 'block';
        profileImage.focus();
    } else {
        imageAlert.style.display = 'none';
        profileImage.style.display = 'none'; // ซ่อนกลับหลังจากการตรวจสอบผ่าน
    }
});

document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const confirmPasswordField = document.getElementById('password_confirmation');
    const confirmPasswordIcon = document.getElementById('confirmPasswordIcon');
    if (confirmPasswordField.type === 'password') {
        confirmPasswordField.type = 'text';
        confirmPasswordIcon.classList.remove('fa-eye');
        confirmPasswordIcon.classList.add('fa-eye-slash');
    } else {
        confirmPasswordField.type = 'password';
        confirmPasswordIcon.classList.remove('fa-eye-slash');
        confirmPasswordIcon.classList.add('fa-eye');
    }
});

function previewImage(event) {
    const imagePreview = document.getElementById('image_preview');
    imagePreview.style.display = 'block';
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
}
