 @extends('layouts.app') <!-- Make sure you have a layout file -->

@section('content')
    <div class="container">
        <h1>Advisor Dashboard</h1>
        <!-- Section for Managing Students -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Manage Students
            </div>
            <div class="card-body">
                <p>You have <strong>{{ $studentCount }}</strong> students assigned to you.</p>
                <p>
                    Breakdown:
                    <ul>
                        <li>Male: <strong>{{ $maleCount }}</strong></li>
                        <li>Female: <strong>{{ $femaleCount }}</strong></li>
                    </ul>
                </p>
                <a href="{{ route('students.indexforadvisor', ['advisor_id' => $advisor->id]) }}" class="btn btn-primary">View Students</a>
            </div>
        </div>

        <!-- Section for Meeting Activities -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                Meeting Activities
            </div>
            <div class="card-body">
                <p>Meeting Status Overview:</p>
                <div>
                    <span class="badge bg-warning text-dark me-2">
                        Pending: <span id="pendingCount">{{ $pendingCount }}</span>
                    </span>
                    <span class="badge bg-success me-2">
                        Approved: <span id="approvedCount">{{ $approvedCount }}</span>
                    </span>
                    <span class="badge bg-danger">
                        Rejected: <span id="rejectedCount">{{ $rejectedCount }}</span>
                    </span>
                </div>
                <a href="{{ route('calendar.meet') }}" class="btn btn-success mt-3">View Meetings</a>
            </div>
        </div>

    </div>
@endsection
