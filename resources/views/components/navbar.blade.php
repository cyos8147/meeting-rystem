<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:rgba(179, 181, 215, 1);">
    <div class="container-fluid">
     <!-- Toggle Button for Sidebar -->
        <button class="btn btn-primary me-3" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand" href="" style="font-size: 24px; font-weight: bold;">meeting system</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 18px; font-weight: bold; padding-right: 20px; color: black;">
                        @if(Auth::guard('admin')->check())
                            {{ Auth::guard('admin')->user()->username }}
                        @elseif(Auth::guard('advisor')->check() && isset($advisor))
                            <img src="{{ asset('storage/' . $advisor->profile_image) }}" alt="Profile Image" class="img-fluid rounded-circle" style="width: 30px; height: 30px;">
                            {{ Auth::guard('advisor')->user()->name }}
                        @elseif(Auth::guard('student')->check() && isset($student))
                            <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile Image" class="img-fluid rounded-circle" style="width: 30px; height: 30px;">
                            <span style="color: black;">{{ $student->name }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            @if(Auth::guard('admin')->check())
                                {{-- <a class="dropdown-item" href="{{ route('admin.profile', Auth::guard('admin')->user()->id) }}">Profile</a> --}}
                            @elseif(Auth::guard('advisor')->check())
                                {{-- <a class="dropdown-item" href="{{ route('advisor.profile', Auth::guard('advisor')->user()->id) }}">Profile</a> --}}
                            @elseif(Auth::guard('student')->check())
                                <!-- <a class="dropdown-item" href="{{ route('student.showProfile', Auth::guard('student')->user()->id) }}">Profile</a> -->
                            @endif
                        </li>
                        <li class="dropdown-item">
                            <form action="
                                @if(Auth::guard('admin')->check())
                                    {{ route('admin.logout') }}
                                @elseif(Auth::guard('advisor')->check())
                                    {{ route('advisor.logout') }}
                                @elseif(Auth::guard('student')->check())
                                    {{ route('student.logout') }}
                                @endif
                            " method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 text-start">Logout</button>
                            </form>
                        </li>
                    </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}" style="font-size: 16px; font-weight: normal;">Login</a>
                    </li>
                    @endauth
                    </ul>
                    </div>
                    </div>
                    </nav>
