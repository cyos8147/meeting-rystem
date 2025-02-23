@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Appointments with advisor</h2>
        </div>

        <!-- Status Summary -->
        <div class="mb-3">
            <div>
                <span class="badge bg-warning">Pending: <span id="pendingCount">0</span></span>
                <span class="badge bg-success">Approved: <span id="approvedCount">0</span></span>
                <span class="badge bg-danger">Rejected: <span id="rejectedCount">0</span></span>
            </div>
        </div>

        <!-- Calendar Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-light" onclick="changeMonth(-1)">&lt;</button>
            <h3 id="currentMonth">January 2023</h3>
            <button class="btn btn-light" onclick="changeMonth(1)">&gt;</button>
            <button class="btn btn-outline-primary" onclick="goToToday()">Today</button>
        </div>

        <!-- Days of the Week -->
        <div class="row g-0 text-center bg-light">
            <div class="col border p-2">Sun</div>
            <div class="col border p-2">Mon</div>
            <div class="col border p-2">Tue</div>
            <div class="col border p-2">Wed</div>
            <div class="col border p-2">Thu</div>
            <div class="col border p-2">Fri</div>
            <div class="col border p-2">Sat</div>
        </div>

        <!-- Calendar Grid -->
        <div id="calendarGrid" class="calendar-grid row g-0">
            <!-- Days will be dynamically generated here -->
        </div>
    </div>

    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Appointment content will be displayed here -->
                </div>
            </div>
        </div>
    </div>


    <script>
        const activities = @json($activities).map(activity => ({
            meeting_date: activity.meeting_date,
            discussion_content: activity.discussion_content,
            evidence: activity.evidence,
            status: activity.status,
            id: activity.id,
            advisor_comment: activity.advisor_comment,
            advisor: activity.advisor ? activity.advisor.name : 'No Advisor',
        }));

        let currentDate = new Date();
        let selectedDate = null;

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

            let pendingCount = 0,
                approvedCount = 0,
                rejectedCount = 0;

            for (let i = 0; i < firstDay; i++) {
                calendarGrid.innerHTML += `<div class="col border p-2 text-center bg-light"></div>`;
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const events = activities.filter(a => a.meeting_date === dateKey);
                const isToday = (new Date()).toLocaleDateString() === (new Date(year, month, day)).toLocaleDateString();

                let eventHTML = '';
                events.forEach(event => {
                    eventHTML += `
        <div class="bg-${getEventClass(event.status)} p-1 mt-1 rounded text-white" onclick="viewAppointment('${dateKey}', ${event.id})">
            <small>${event.discussion_content}</small>
        </div>`;
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
                });

                calendarGrid.innerHTML += `
        <div class="col border p-2 text-center ${isToday ? 'bg-info' : ''}">
            <div>${day}</div>
            ${eventHTML}
            <div class="mt-2">
                <button class="btn btn-sm btn-outline-primary" onclick="openAppointmentModal('${dateKey}')">+</button>
            </div>
        </div>`;
            }

            document.getElementById('pendingCount').textContent = pendingCount;
            document.getElementById('approvedCount').textContent = approvedCount;
            document.getElementById('rejectedCount').textContent = rejectedCount;
        }


        function getEventClass(status) {
            switch (status) {
                case 'Pending':
                    return 'warning'; // Yellow
                case 'Rejected':
                    return 'danger'; // Red
                case 'Approved':
                    return 'success'; // Green
                default:
                    return 'secondary';
            }
        }

        function changeMonth(offset) {
            currentDate.setMonth(currentDate.getMonth() + offset);
            renderCalendar();
        }

        function goToToday() {
            currentDate = new Date();
            renderCalendar();
        }
        // Open appointment modal
        function openAppointmentModal(dateKey) {
            selectedDate = dateKey; // เก็บวันที่ที่เลือก
            const url = "{{ route('meetings.create') }}?date=" + dateKey; // ไปที่หน้าเพิ่มนัดใหม่
            window.location.href = url; // เปลี่ยนหน้าไปที่ URL ที่ต้องการ
        }

        function viewAppointment(dateKey, id) {
            console.debug("Activities: ", activities);
            console.debug("Selected Date: ", dateKey);
            console.debug("Selected Activity ID: ", id);

            const event = activities.find(a => a.meeting_date === dateKey && a.id === id);
            console.debug("Filtered Event: ", event);

            const modalLabel = document.getElementById('appointmentModalLabel');
            const modalBody = document.querySelector('#appointmentModal .modal-body');

            let contentHTML = ''; // Initialize contentHTML here to ensure it's defined

            if (event) {
                const date = event.meeting_date || 'No Date';
                const status = event.status || 'No Status';
                const advisors = event.advisor || 'No Advisor';
                const discussionContent = event.discussion_content || 'No Discussion Content';
                const advisorComment = event.advisor_comment != null ? event.advisor_comment : 'No Advisor Comment';

                // Parse the evidence JSON string to get an array of file paths
                const evidenceFiles = JSON.parse(event.evidence || '[]');
                let evidenceHTML = '';

                // Generate <img> elements for each file in evidenceFiles
                evidenceFiles.forEach(filePath => {
                    const imageUrl = `/storage/${filePath}`; // Assuming you're using the public disk
                    evidenceHTML += `
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <img src="${imageUrl}" alt="Evidence Image" class="img-fluid rounded shadow-sm" style="max-width: 100%; height: auto;">
            </div>`;
                });

                contentHTML = `
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <strong>Date:</strong> ${date} (${status})<br>
                    <small><strong>Advisor:</strong> ${advisors}</small><br>
                    <small><strong>Discussion:</strong> ${discussionContent}</small><br>
                    <small><strong>Advisor's Comment:</strong> ${advisorComment}</small><br>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h5 class="mt-3">Evidence:</h5>
                    <div class="row">${evidenceHTML}</div>
                </div>
            </div>
        </div>`;

                modalLabel.textContent = `Appointment on ${dateKey}`;
            } else {
                modalLabel.textContent = `No Appointment on ${dateKey} with ID ${id}`;
                contentHTML = `<p>No events available for this date and ID.</p>`;
            }

            modalBody.innerHTML = contentHTML;

            if (event) {
                // Check if status is not Approved or Rejected
                const canEditOrDelete = event.status !== 'Approved' && event.status !== 'Rejected';

                if (canEditOrDelete) {
                    // Add an "Edit Appointment" button
                    const appointmentButton = document.createElement('button');
                    appointmentButton.classList.add('btn', 'btn-primary', 'mt-3');
                    appointmentButton.innerHTML = '<i class="fas fa-edit"></i>';
                    appointmentButton.onclick = function() {
                        window.location.href = `/appointments/${event.id}/edit`; // Adjust the route as needed
                    };
                    modalBody.appendChild(appointmentButton);

                    // Add a "Delete Appointment" button
                    const deleteButton = document.createElement('button');
                    deleteButton.classList.add('btn', 'btn-danger', 'mt-3', 'ms-2'); // Add margin-left for spacing
                    deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                    deleteButton.onclick = function() {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'Do you really want to delete this appointment?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect to the delete route
                                window.location.href =
                                `/appointments/${event.id}/delete`; // Adjust the route as needed
                            }
                        });
                    };
                    modalBody.appendChild(deleteButton);
                } else {
                    // Display a SweetAlert2 warning if editing or deleting is restricted
                }

            }

            const modalElement = document.getElementById('appointmentModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }







        goToToday();
    </script>

    <link href="{{ asset('css/students/appointments.css') }}" rel="stylesheet">
@endsection
