document.addEventListener('DOMContentLoaded', () => {
    // Handle the Manage Students Dropdown Arrow
    const studentMenu = document.getElementById('studentMenu');
    const studentMenuArrow = document.getElementById('studentMenuArrow');

    studentMenu.addEventListener('shown.bs.collapse', () => {
        studentMenuArrow.classList.remove('fa-chevron-down');
        studentMenuArrow.classList.add('fa-chevron-left');
    });

    studentMenu.addEventListener('hidden.bs.collapse', () => {
        studentMenuArrow.classList.remove('fa-chevron-left');
        studentMenuArrow.classList.add('fa-chevron-down');
    });

    // Handle the Manage Advisors Dropdown Arrow
    const advisorsMenu = document.getElementById('advisorsMenu');
    const advisorsMenuArrow = document.getElementById('advisorsMenuArrow');

    advisorsMenu.addEventListener('shown.bs.collapse', () => {
        advisorsMenuArrow.classList.remove('fa-chevron-down');
        advisorsMenuArrow.classList.add('fa-chevron-left');
    });

    advisorsMenu.addEventListener('hidden.bs.collapse', () => {
        advisorsMenuArrow.classList.remove('fa-chevron-left');
        advisorsMenuArrow.classList.add('fa-chevron-down');
    });
});
document.getElementById('fileInput').addEventListener('change', function () {
    const file = this.files[0];
    const submitBtn = document.getElementById('submitBtn');

    if (file) {
        const fileType = file.name.split('.').pop().toLowerCase();
        if (fileType === 'xlsx' || fileType === 'xls') {
            submitBtn.disabled = false;
        } else {
            alert('Only Excel files (.xlsx, .xls) are allowed!');
            this.value = '';
            submitBtn.disabled = true;
        }
    } else {
        submitBtn.disabled = true;
    }
});