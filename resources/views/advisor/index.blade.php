{{-- resources/views/advisor/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid">


        <!-- Search Form -->
        <div class="d-flex justify-content-between mb-0">
            <a href="{{ route('advisors.create') }}" class="btn btn-success btn-lg ml-3">
                <i class="fas fa-plus-circle"></i> Create Advisor
            </a>
            <form action="{{ route('advisors.index') }}" method="GET" class="w-50">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by Name, Email, or Metric..." value="{{ request()->get('search') }}">
                    <select name="filter" class="form-control ml-2">
                        <option value="name" {{ request()->get('filter') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="metric_number" {{ request()->get('filter') == 'metric_number' ? 'selected' : '' }}>
                            Metric Number</option>
                        <option value="email" {{ request()->get('filter') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>

        <!-- Responsive Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm rounded">
                <thead class="thead-dark bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>
                            <a
                                href="{{ route('advisors.index', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'name', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                                Name
                                @if (request()->get('sortBy') == 'name')
                                    <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}"
                                        aria-hidden="true"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a
                                href="{{ route('advisors.index', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'email', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                                Email
                                @if (request()->get('sortBy') == 'email')
                                    <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}"
                                        aria-hidden="true"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a
                                href="{{ route('advisors.index', ['search' => request()->get('search'), 'filter' => request()->get('filter'), 'sortBy' => 'metric_number', 'sortDirection' => request()->get('sortDirection') == 'asc' ? 'desc' : 'asc']) }}">
                                Metric Number
                                @if (request()->get('sortBy') == 'metric_number')
                                    <i class="fas fa-sort-{{ request()->get('sortDirection') == 'asc' ? 'up' : 'down' }}"
                                        aria-hidden="true"></i>
                                @endif
                            </a>
                        </th>
                        <th>Assigned / Max Students</th>
                        <th>Program</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($advisors as $advisor)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $advisor->name }}</td>
                            <td>{{ $advisor->email }}</td>
                            <td>{{ $advisor->metric_number }}</td>
                            <td>{{ $advisor->students->count() }} / {{ $advisor->max_students }}</td>

                            <td>({{ $advisor->program->type ?? 'N/A' }}) {{ $advisor->program->name ?? 'N/A' }}</td>
                            <td>
                                <!-- View Details button with an icon -->
                                <a href="{{ route('advisor.show', $advisor->id) }}" class="btn btn-info btn-sm"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <form action="{{ route('advisors.destroy', $advisor->id) }}" method="POST"
                                    class="d-inline-block delete-form">
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
                    @if ($advisors->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $advisors->previousPageUrl() }}"
                                aria-label="Previous">Previous</a>
                        </li>
                    @endif

                    <!-- Pagination Links -->
                    @foreach ($advisors->getUrlRange(1, $advisors->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $advisors->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Next Button -->
                    @if ($advisors->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $advisors->nextPageUrl() }}" aria-label="Next">Next</a>
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

    <!-- Include External Styles -->
    <link href="{{ asset('css/AdvisorsIndex.css') }}" rel="stylesheet">
    <script src="{{ asset('js/Advisors.js') }}"></script>
@endsection
