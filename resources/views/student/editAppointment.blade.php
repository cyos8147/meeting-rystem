@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Edit Appointment</h1>

        <form action="{{ route('activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="student_id" class="form-label">Student</label>
                    <input type="text" class="form-control" value="{{ $activity->student->name }}" readonly style="background-color: #e9ecef;">
                </div>
                <div class="col-md-6">
                    <label for="advisor_id" class="form-label">Advisor</label>
                    <input type="text" class="form-control" value="{{ $activity->advisor->name }}" readonly style="background-color: #e9ecef;">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="meeting_date" class="form-label">Meeting Date</label>
                    <input type="date" name="meeting_date" id="meeting_date" class="form-control @error('meeting_date') is-invalid @enderror" value="{{ old('meeting_date', $activity->meeting_date) }}">
                    @error('meeting_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="discussion_content" class="form-label">Discussion Content</label>
                    <textarea name="discussion_content" id="discussion_content" class="form-control @error('discussion_content') is-invalid @enderror">{{ old('discussion_content', $activity->discussion_content) }}</textarea>
                    @error('discussion_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="evidence" class="form-label">Evidence</label>
                    <input type="file" name="evidence[]" id="evidence" class="form-control @error('evidence.*') is-invalid @enderror" multiple>
                    @if(!empty($evidence))
                        <div class="mt-3">
                            <h5>Existing Evidence:</h5>
                            <div class="row g-2">
                                @foreach($evidence as $file)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <img src="{{ asset('storage/' . $file) }}" alt="Evidence Image" class="img-thumbnail">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @error('evidence.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">Update Appointment</button>
                <a href="{{ route('meet.meet') }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Link to the external JavaScript file -->
    <script src="{{ asset('js/student/editAppointment.js') }}"></script>
@endsection
