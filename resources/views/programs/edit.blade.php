@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Program</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('programs.update', $program->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Program Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $program->name) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $program->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Program</button>
    </form>
</div>
@endsection
