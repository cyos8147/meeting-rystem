document.addEventListener("DOMContentLoaded", () => {
    const calendarApp = document.getElementById('calendarApp');

    if (calendarApp) {
        const activities = JSON.parse(calendarApp.getAttribute('data-activities')).map(activity => ({
            meeting_date: activity.meeting_date,
            discussion_content: activity.discussion_content,
            evidence: activity.evidence,
            status: activity.status,
            advisor_comment: activity.advisor_comment,
            advisor: activity.advisor ? activity.advisor.name : 'No Advisor'
        }));

        let currentDate = new Date();
        let selectedDate = null;

        function goToToday() {
            currentDate = new Date();
            renderCalendar();
        }

        // Initialize
        goToToday();
    } else {
        console.error('Element with id "calendarApp" not found in the DOM.');
    }
});

// ฟังก์ชัน renderCalendar
function renderCalendar() {
    const calendarGrid = document.getElementById('calendarGrid');
    const currentMonth = document.getElementById('currentMonth');
    calendarGrid.innerHTML = '';

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    currentMonth.textContent = currentDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
    });

    let pendingCount = 0, approvedCount = 0, rejectedCount = 0;

    for (let i = 0; i < firstDay; i++) {
        calendarGrid.innerHTML += `<div class="col border p-2 text-center bg-light"></div>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const event = activities.find(a => a.meeting_date === dateKey);

        const isToday = (new Date()).toLocaleDateString() === (new Date(year, month, day)).toLocaleDateString();

        calendarGrid.innerHTML += `
            <div class="col border p-2 text-center ${isToday ? 'bg-info' : ''}">
                <div>${day}</div>
                ${
                    event ? `
                        <div class="bg-${getEventClass(event.status)} p-1 mt-1 rounded text-white" onclick="viewAppointment('${dateKey}')">
                            <small>${event.discussion_content}</small><br>
                        </div>` 
                    : `<div onclick="openAppointmentModal('${dateKey}')">+</div>`
                }
            </div>`;

        if (event) {
            switch (event.status) {
                case 'Pending':
                    pendingCount++;
                    break;
                case 'Approved':
                    approvedCount++;
                    break;
                case 'Rejected':
                    rejectedCount++;
                    break;
            }
        }
    }

    document.getElementById('pendingCount').textContent = pendingCount;
    document.getElementById('approvedCount').textContent = approvedCount;
    document.getElementById('rejectedCount').textContent = rejectedCount;
}
