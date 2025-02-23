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
