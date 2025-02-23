@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Program List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programs as $program)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $program->name }}</td>
                <td>{{ $program->description }}</td>
                <td>
                    <a href="{{ route('programs.edit', $program->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('programs.destroy', $program->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this program?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('programs.create') }}" class="btn btn-primary">Create Program</a>
</div>
@endsection
