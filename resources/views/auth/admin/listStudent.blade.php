@extends('layouts.app')

@section('content')
<div class="container mt-0">


    <!-- Search Form -->
    <div class="d-flex justify-content-between mb-0">
        <a href="{{ route('students.create') }}" class="btn btn-success btn-lg ml-3">
            <i class="fas fa-plus-circle"></i> Create student
        </a>
        <form action="{{ route('students.indexforAdmin') }}" method="GET" class="w-50">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request()->get('search') }}">
                <select name="filter" class="form-control ml-2">
                    <option value="name" {{ request()->get('filter') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="metric_number" {{ request()->get('filter') == 'metric_number' ? 'selected' : '' }}>Metric Number</option>
                    <option value="gender" {{ request()->get('filter') == 'gender' ? 'selected' : '' }}>Gender</option>
                    <option value="program" {{ request()->get('filter') == 'program' ? 'selected' : '' }}>Program</option>
                    <option value="semester" {{ request()->get('filter') == 'semester' ? 'selected' : '' }}>Semester</option>
                    <option value="advisor" {{ request()->get('filter') == 'advisor' ? 'selected' : '' }}>Advisor</option>
                    <option value="email" {{ request()->get('filter') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="race" {{ request()->get('filter') == 'race' ? 'selected' : '' }}>Race</option>
                </select>
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
    </div>

    <!-- Responsive table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm rounded">
            <thead class="thead-dark bg-primary text-white">
                <tr>
                    <th>#</th>
                    <th>
                        <a href="{{ route('students.indexforAdmin', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'metric_number', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                            Metric Number
                            @if(request()->get('sortBy') == 'metric_number')
                                <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('students.indexforAdmin', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'name', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                            Name
                            @if(request()->get('sortBy') == 'name')
                                <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('students.indexforAdmin', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'gender', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                            Gender
                            @if(request()->get('sortBy') == 'gender')
                                <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('students.indexforAdmin', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'race', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                            Race
                            @if(request()->get('sortBy') == 'race')
                                <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('students.indexforAdmin', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'program', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                            Program
                            @if(request()->get('sortBy') == 'program')
                                <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('students.indexforAdmin', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'semester', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                            Semester
                            @if(request()->get('sortBy') == 'semester')
                                <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('students.indexforAdmin', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'advisor', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                            Advisor
                            @if(request()->get('sortBy') == 'advisor')
                                <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('students.indexforAdmin', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'email', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                            Email
                            @if(request()->get('sortBy') == 'email')
                                <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
                
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->metric_number }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->race }}</td>
                    <td>{{ $student->advisor ? $student->advisor->program->name . ' (' . $student->advisor->program->type . ')' : 'N/A' }}</td>
                    <td>{{ $student->semester }}</td>
                    <td>{{ $student->advisor->name ?? 'N/A' }}</td>
                    <td>{{ $student->email }}</td>
                    <td>
                        <!-- View Details button with an icon -->
                        <a href="{{ route('student.show', $student->id) }}" class="btn btn-info btn-sm" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <!-- Delete button with an icon -->
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline-block delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-3">
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm pagination-responsive">
            <!-- Previous Button -->
            @if ($students->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $students->previousPageUrl() }}" aria-label="Previous">Previous</a>
                </li>
            @endif

            <!-- Pagination Links -->
            @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                <li class="page-item {{ $page == $students->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Next Button -->
            @if ($students->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $students->nextPageUrl() }}" aria-label="Next">Next</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
            @endif
        </ul>
    </nav>
</div>
</div>

<link href="{{ asset('css/ListStudent.css') }}" rel="stylesheet">
<script src="{{ asset('js/ListStudent.js') }}"></script>
@endsection
