@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Advisor for Appointments</h2>
        </div>

        <!-- Status Summary -->
        <div class="mb-3">
            <div>
                <span class="badge bg-warning">Pending: <span id="pendingCount">{{ $pendingCount }}</span></span>
                <span class="badge bg-success">Approved: <span id="approvedCount">{{ $approvedCount }}</span></span>
                <span class="badge bg-danger">Rejected: <span id="rejectedCount">{{ $rejectedCount }}</span></span>
            </div>
        </div>


        <!-- Students Table -->
        <div class="d-flex justify-content-center"> <!-- Center the table horizontally -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center"> <!-- Add text-center for the entire table -->
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Date</th>
                            <th>Discussion Content</th>
                            <th>Your Comment</th>
                            <th>Images</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activities as $index => $activity)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $activity->student->name ?? 'Unknown Student' }}</td>
                                <td>{{ $activity->meeting_date }}</td>
                                <td>
                                    @if (strlen($activity->discussion_content) > 50)
                                        <a href="#discussionModal{{ $activity->id }}" data-bs-toggle="modal"
                                            data-bs-target="#discussionModal{{ $activity->id }}">
                                            {{ Str::limit($activity->discussion_content, 50) }}...
                                        </a>
                                    @else
                                        {{ $activity->discussion_content ?? 'No Content' }}
                                    @endif
                                </td>
                                <!-- Modal for Full Discussion Content -->
                                <div class="modal fade" id="discussionModal{{ $activity->id }}" tabindex="-1"
                                    aria-labelledby="discussionModalLabel{{ $activity->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="discussionModalLabel{{ $activity->id }}">
                                                    Discussion Content</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body"
                                                style="max-height: 300px; overflow-y: auto; word-wrap: break-word;">
                                                <!-- Show full discussion content -->
                                                <p>{{ $activity->discussion_content ?? 'No Content' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <td>
                                    <!-- Display truncated comment -->
                                    <span id="commentPreview{{ $activity->id }}">
                                        {{ \Illuminate\Support\Str::limit($activity->advisor_comment ?? 'No Comment', 20) }}
                                    </span>
                                    @if (strlen($activity->advisor_comment ?? '') > 50)
                                        <!-- Link to open the full comment -->
                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                            data-bs-target="#commentModal{{ $activity->id }}">Read More</a>
                                    @endif
                                </td>

                                <!-- Modal for Full Comment -->
                                <div class="modal fade" id="commentModal{{ $activity->id }}" tabindex="-1"
                                    aria-labelledby="commentModalLabel{{ $activity->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="commentModalLabel{{ $activity->id }}">Advisor
                                                    Comment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body"
                                                style="max-height: 300px; overflow-y: auto; word-wrap: break-word;">
                                                <!-- Show full comment -->
                                                <p>{{ $activity->advisor_comment ?? 'No Comment' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <td>
                                    @if (!empty($activity->evidence))
                                        @php
                                            $evidenceArray = json_decode($activity->evidence, true);
                                        @endphp
                                        <div class="carousel slide" id="carousel{{ $activity->id }}">
                                            <div class="carousel-inner">
                                                @foreach ($evidenceArray as $index => $evidencePath)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <a href="{{ asset('storage/' . $evidencePath) }}" target="_blank"
                                                            data-fancybox="gallery{{ $activity->id }}">
                                                            <img src="{{ asset('storage/' . $evidencePath) }}"
                                                                class="d-block mx-auto w-50 img-thumbnail" alt="Evidence">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#carousel{{ $activity->id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#carousel{{ $activity->id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    @else
                                        No Evidence
                                    @endif

                                </td>

                                <td>
                                    <span
                                        class="badge bg-{{ $activity->status == 'Pending' ? 'warning' : ($activity->status == 'Approved' ? 'success' : 'danger') }}">
                                        {{ $activity->status }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                        onclick="openEditModal({{ $activity->id }}, '{{ $activity->advisor_comment }}', '{{ $activity->status }}')">
                                        Comment & Change Status
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Editing -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST"
                            action="{{ route('appointments.updateForAdvisor', ['id' => '__ID__']) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="activityId">

                            <div class="mb-3">
                                <label for="advisorComment" class="form-label">Advisor Comment</label>
                                <textarea name="advisor_comment" id="advisorComment" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        @if ($activities->isEmpty())
            <tr>
                <td colspan="8" class="text-center text-muted">No Appointments Available</td>
            </tr>
        @endif
    </div>

    <script src="{{ asset('js/advisor/meet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/advisor/meet.css') }}">
@endsection
