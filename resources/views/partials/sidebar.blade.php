<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="relative navbar-brand-box" style="background:  url({{ asset('') }}image/background.png) no-repeat ; ">
        <!-- Dark Logo-->
        <a href="{{ url('/') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('') }}image/favicon.png" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('') }}image/logo.png" alt="" height="50">
            </span>
        </a>
        <!-- Light Logo-->
        <a href={{ url('/') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('') }}image/favicon.png" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('') }}image/logo.png" alt="" height="50">
            </span>
        </a>
        <button type="button" class="p-0 btn btn-sm fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('dashboard*') ? 'active' : '' }}"
                        href="{{ url('/') }}">
                        <i data-feather="home" class="icon-dual"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('booking*') ? 'active' : '' }}"
                        href="{{ route('booking.index') }}">
                        <i data-feather="calendar" class="icon-dual"></i> <span data-key="t-widgets">Booking</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('guest*') ? 'active' : '' }}"
                        href="{{ route('guest.index') }}">
                        <i data-feather="users" class="icon-dual"></i> <span data-key="t-widgets">Guest</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('event*') ? 'active' : '' }}"
                        href="{{ route('event.index') }}">
                        <i data-feather="book" class="icon-dual"></i> <span data-key="t-widgets">Event/Class</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('room*') ? 'active' : '' }}"
                        href="{{ route('room.index') }}">
                        <i data-feather="key" class="icon-dual"></i> <span data-key="t-widgets">Room</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('status-room*') ? 'active' : '' }}"
                        href="{{ route('status.room') }}">
                        <i data-feather="bar-chart-2" class="icon-dual"></i> <span data-key="t-widgets">Room
                            Status</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('room-report*') ? 'active' : '' }}"
                        href="{{ route('room.report') }}">
                        <i data-feather="file-text" class="icon-dual"></i> <span data-key="t-widgets">Room Report</span>
                    </a>
                </li>


                @if (Auth::user()->branch_id == 0)
                    <li class="menu-title"><span data-key="t-menu">Administator</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('user*') ? 'active' : '' }}"
                            href="{{ route('user.index') }}">
                            <i data-feather="user" class="icon-dual"></i> <span data-key="t-widgets">Users</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('branch*') ? 'active' : '' }}"
                            href="{{ route('branch.index') }}">
                            <i data-feather="grid" class="icon-dual"></i> <span data-key="t-widgets">Branch</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('role*') ? 'active' : '' }}"
                            href="{{ route('role.index') }}">
                            <i data-feather="lock" class="icon-dual"></i> <span data-key="t-widgets">Role</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#">
                            <i data-feather="activity" class="icon-dual"></i> <span data-key="t-widgets">Activity
                                Log</span>
                        </a>
                    </li>
                @endif

            </ul>


        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
