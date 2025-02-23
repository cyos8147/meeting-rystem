@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>You are logged in as an Admin.</p>

        <!-- Logout Form -->
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
@endsection
