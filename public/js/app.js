// JavaScript for Sidebar Toggle
const toggleSidebar = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const content = document.getElementById('content');

toggleSidebar.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
    content.classList.toggle('fullwidth');
});
