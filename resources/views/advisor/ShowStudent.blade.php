@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-dark">LIST OF STUDENTS</h1>

        <!-- Search Form -->
<form method="GET" action="{{ route('students.indexforadvisor') }}" class="mb-3">
    <div class="form-row align-items-center">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" name="search" class="form-control me-3"
                    placeholder="Search by Metric Number, Name, or Email" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>
    </div>
</form>



        <!-- Responsive Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>
                            <a href="{{ route('students.indexforadvisor', [
                                'sort_by' => 'metric_number',
                                'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                            ]) }}"
                                class="text-black">
                                #
                                @if (request('sort_by') === 'metric_number')
                                    <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('students.indexforadvisor', [
                                'sort_by' => 'metric_number',
                                'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                            ]) }}"
                                class="text-black">
                                Metric Number
                                @if (request('sort_by') === 'metric_number')
                                    <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('students.indexforadvisor', [
                                'sort_by' => 'name',
                                'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                            ]) }}"
                                class="text-black">
                                Name
                                @if (request('sort_by') === 'name')
                                    <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('students.indexforadvisor', [
                                'sort_by' => 'gender',
                                'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                            ]) }}"
                                class="text-black">
                                Gender
                                @if (request('sort_by') === 'gender')
                                    <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('students.indexforadvisor', [
                                'sort_by' => 'race',
                                'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                            ]) }}"
                                class="text-black">
                                Race
                                @if (request('sort_by') === 'race')
                                    <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('students.indexforadvisor', [
                                'sort_by' => 'program',
                                'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                            ]) }}"
                                class="text-black">
                                Program
                                @if (request('sort_by') === 'program')
                                    <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('students.indexforadvisor', [
                                'sort_by' => 'semester',
                                'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                            ]) }}"
                                class="text-black">
                                Semester
                                @if (request('sort_by') === 'semester')
                                    <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>

                        <th>
                            <a href="{{ route('students.indexforadvisor', [
                                'sort_by' => 'email',
                                'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                            ]) }}"
                                class="text-black">
                                Email
                                @if (request('sort_by') === 'email')
                                    <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->metric_number }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->gender }}</td>
                            <td>{{ $student->race }}</td>
                            <td>{{ $student->advisor ? $student->advisor->program->name . ' (' . $student->advisor->program->type . ')' : 'N/A' }}</td>
                            <td>{{ $student->semester }}</td>
                            <td>{{ $student->email }}</td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
