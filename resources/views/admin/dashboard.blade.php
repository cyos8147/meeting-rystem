@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container text-center">
        <h1 class="text-center mb-4">Admin Dashboard</h1>
    </div>
    <div class="container text-center">
        <div class="row mb-4">
            <div class="col-sm-6"> <a href="{{ route('students.indexforAdmin') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body text-center">
                            <h3 class="display-6 fw-bold">{{ $totalStudents }}</h3>
                            <p class="fs-6 mb-1">All Students</p>
                          

                            <i class="fas fa-users me-2"></i>
                        </div>
                    </div>
                </a></div>
            <div class="col-sm-6"> <a href="{{ route('advisors.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm bg-secondary text-white">
                        <div class="card-body text-center">
                            <h3 class="display-6 fw-bold">{{ $totalAdvisors }}</h3>
                            <p class="fs-6 mb-1">All Advisors</p>
                            <i class="fas fa-users me-2"></i>
                        </div>
                    </div>
                </a></div>
        </div>
        <div class="row" style="margin-left: 0px;">
            <h3>status for meetings</h3>
        </div>
        <br>
        <div class="row">
            <div class="col-sm">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="display-6 fw-bold">{{ $approvedAppointments }}</h3>
                        <p class="fs-6">Approved</p>
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="display-6 fw-bold">{{ $pendingAppointments }}</h3>
                        <p class="fs-6">Pending</p>
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body text-center">
                        <h3 class="display-6 fw-bold">{{ $rejectedAppointments }}</h3>
                        <p class="fs-6">Rejected</p>
                        <i class="bi bi-x-circle-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
