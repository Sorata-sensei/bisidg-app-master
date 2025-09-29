<!-- Desktop Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="logo">
        <h1>BISDIG</h1>
    </div>
    <nav>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('dashboard.admin.index') }}"
                    class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie nav-icon"></i>
                    Dashboard
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{ route('admin.students.index') }}"
                    class="nav-link {{ request()->is('admin/students') ? 'active' : '' }} {{ request()->is('admin/students/create') ? 'active' : '' }}">
                    <i class="fa-solid fa-users nav-icon"></i>
                    Students
                </a>
            </li> --}}
            <li class="nav-item">
                <a href="{{ route('admin.counseling.index') }}"
                    class="nav-link {{ request()->is('admin/counseling') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open nav-icon"></i>
                    Counseling
                </a>
            </li>
            @if (auth()->user()->role === 'masteradmin' || auth()->user()->role === 'superadmin')
                <li class="nav-item">
                    <a href="{{ route('user.admin.main') }}"
                        class="nav-link {{ request()->is('admin/user/main') ? 'active' : '' }}">
                        <i class="fa-solid fa-chalkboard-user nav-icon"></i>
                        Lecturers
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
