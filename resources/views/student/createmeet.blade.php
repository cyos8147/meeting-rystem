@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Appointments</h2>
    <form action="{{ route('meetings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <br>
        <div class="form-group">
            <label for="student_id">Student:</label>
            <select class="form-control" id="student_id" name="student_id" required>
                <option value="{{ Auth::guard('student')->user()->id }}" selected>{{ Auth::guard('student')->user()->name }}</option>
            </select>
        </div>
       
        <div class="form-group">
            <label for="advisor_id">Advisor:</label>
            <select class="form-control" id="advisor_id" name="advisor_id" required>
                @if(Auth::guard('student')->user()->advisor)
                    <option value="{{ Auth::guard('student')->user()->advisor->id }}" selected>{{ Auth::guard('student')->user()->advisor->name }}</option>
                @else
                    <option value="" disabled selected>No Advisor Assigned</option>
                @endif
            </select>
            @error('advisor_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="meeting_date">Meeting Date:</label>
            <input 
                type="date" 
                class="form-control" 
                id="meeting_date" 
                name="meeting_date" 
                value="{{ old('meeting_date') ?: request()->query('date') }}" 
                required>
            @error('meeting_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="discussion_content">Discussion Content:</label>
            <textarea 
                class="form-control" 
                id="discussion_content" 
                name="discussion_content" 
                rows="3" 
                required>{{ old('discussion_content') }}</textarea>
            @error('discussion_content')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <br>
        <div class="form-group">
            <label for="evidence">Evidence (Photos):</label>
            <input 
            type="file" 
            class="form-control-file" 
            id="evidence" 
            name="evidence[]" 
            multiple 
            accept="image/*"
            onchange="previewImages()">
            @error('evidence')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div id="image-preview" class="mt-3"></div>

        <script>
            function previewImages() {
            var preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            var files = document.getElementById('evidence').files;

            if (files) {
                [].forEach.call(files, function(file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var img = document.createElement('img');
                    img.src = event.target.result;
                    img.style.maxWidth = '200px';
                    img.style.margin = '10px';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
                });
            }
            }
        </script>
        <br>
        <div class="form-group d-flex justify-content-center">
            <button type="submit" class="btn btn-success mx-2">Confirm</button>
            <button type="reset" class="btn btn-danger mx-2">Reset</button>
        </div>
        
    </form>
</div>
@endsection
