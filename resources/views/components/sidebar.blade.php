<script src="{{ asset('js/Sidebar.js') }}"></script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Example</title>
    <!-- Link to the sidebar CSS file -->
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Your sidebar HTML code here -->
    <nav class="sidebar bg-brown p-2 vh-100">
        @auth
            <div class="mb-3 mt-3">
                {{-- auth admin --}}
                @auth('admin')
                    <h5 class="text-white">Admin Panel</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>

                        <!-- Dropdown: Manage Students -->
                        <li class="nav-item">
                            <a class="nav-link text-white align-items-center justify-content-between {{ request()->routeIs('students.create') || request()->routeIs('students.indexforAdmin') ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#studentMenu" role="button" aria-expanded="false"
                                aria-controls="studentMenu">
                                <span>
                                    <i class="fas fa-users me-2"></i> Manage Students
                                </span>
                                <i class="fas fa-chevron-down" id="studentMenuArrow"></i>
                            </a>
                            <ul class="collapse list-unstyled ps-3" id="studentMenu">
                                <li class="nav-item">
                                    <a href="{{ route('students.create') }}"
                                        class="nav-link text-white {{ request()->routeIs('students.create') ? 'active' : '' }}">
                                        <i class="fas fa-user-plus me-2"></i> Create Student
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('students.indexforAdmin') }}"
                                        class="nav-link text-white {{ request()->routeIs('students.indexforAdmin') ? 'active' : '' }}">
                                        <i class="fas fa-users me-2"></i> View Students
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Dropdown: Manage Advisors -->
                        <li class="nav-item">
                            <a class="nav-link text-white align-items-center justify-content-between {{ request()->routeIs('advisors.create') || request()->routeIs('advisors.index') ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#advisorsMenu" role="button" aria-expanded="false"
                                aria-controls="advisorsMenu">
                                <span>
                                    <i class="fas fa-user-tie me-2"></i> Manage Advisors
                                </span>
                                <i class="fas fa-chevron-down" id="advisorsMenuArrow"></i>
                            </a>
                            <ul class="collapse list-unstyled ps-2" id="advisorsMenu">
                                <li class="nav-item">
                                    <a href="{{ route('advisors.create') }}"
                                        class="nav-link text-white {{ request()->routeIs('advisors.create') ? 'active' : '' }}">
                                        <i class="fas fa-user-plus me-2"></i> Create Advisors
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('advisors.index') }}"
                                        class="nav-link text-white {{ request()->routeIs('advisors.index') ? 'active' : '' }}">
                                        <i class="fas fa-users me-2"></i> View Advisors
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('calendar.calendar') }}"
                                class="nav-link text-white {{ request()->routeIs('calendar.calendar') ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt me-2"></i> Calendar
                            </a>
                        </li>
                        @if (session('swal'))
                        <script>
                           Swal.fire({
        icon: '{{ session("swal.type") }}',
        title: '{{ session("swal.title") }}',
        html: '{!! session("swal.text") !!}'  // ✅ รองรับข้อความ HTML
    });
                        </script>
                    @endif

                    </ul>
                        {{-- auth advisor --}}
                    @elseif(auth()->guard('advisor')->check())
                    <h5 class="text-white">Advisor Panel</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('advisor.dashboard') }}"
                                class="nav-link text-white {{ request()->routeIs('advisor.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Advisor Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('students.indexforadvisor', ['advisor_id' => $advisor->id]) }}"
                                class="nav-link text-white {{ request()->routeIs('students.indexforadvisor') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> View Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('calendar.meet') }}"
                                class="nav-link text-white {{ request()->routeIs('calendar.meet') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check me-2"></i> Appointments
                            </a>
                        </li>
                    </ul>
                       {{-- auth student --}}
                        @elseif(auth()->guard('student')->check())
                    <h5 class="text-white">Student Panel</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('meet.meet') }}"
                                class="nav-link text-white {{ request()->routeIs('meet.meet') ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt me-2"></i> Meet
                            </a>
                        </li>
                    </ul>
                    @endif
                </div>
            @else
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link text-white">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                    </li>
                </ul>
            @endauth
        </nav>

        <!-- Your other page content -->

        <!-- Include your sidebar JavaScript file (optional) -->
        <script src="{{ asset('js/Sidebar.js') }}"></script>
    </body>

    </html>
