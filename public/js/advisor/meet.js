
// openEditModal
function openEditModal(id, advisorComment = '', status = 'Pending') {
    console.log(`Opening edit modal for activity with ID: ${id}`);
    console.log(`Advisor Comment: ${advisorComment}`);
    console.log(`Status: ${status}`);
    
    // Set the hidden input value for activity ID
    document.getElementById('activityId').value = id;

    // Set the advisor comment
    document.getElementById('advisorComment').value = advisorComment;

    // Update the dropdown for status
    if (['Pending', 'Approved', 'Rejected'].includes(status)) {
        document.getElementById('status').value = status;
    } else {
        console.warn(`Invalid status: ${status}. Defaulting to 'Pending'.`);
        document.getElementById('status').value = 'Pending';
    }

    // Update the form action dynamically
    const editForm = document.getElementById('editForm');
    editForm.action = `/appointments/advisor/${id}`;

    // Show the modal
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
    console.log('Modal shown');
}

