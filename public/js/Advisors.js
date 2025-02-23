document.addEventListener('DOMContentLoaded', function () {
    // Add event listener to delete buttons
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();  // Prevent form submission immediately

            // Show SweetAlert2 confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you really want to delete this student?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    form.submit();
                }
            });
        });
    });
});
document.getElementById("togglePassword").addEventListener("click", function() {
    const passwordField = document.getElementById("password_confirmation");
    const passwordIcon = document.getElementById("passwordIcon");

    // สลับระหว่างการมองเห็นและซ่อนรหัสผ่าน
    if (passwordField.type === "password") {
        passwordField.type = "text";  // เปิดมองเห็นรหัสผ่าน
        passwordIcon.classList.remove("fa-eye");
        passwordIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";  // ซ่อนรหัสผ่าน
        passwordIcon.classList.remove("fa-eye-slash");
        passwordIcon.classList.add("fa-eye");
    }
});
