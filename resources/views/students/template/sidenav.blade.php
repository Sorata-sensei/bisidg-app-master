<!-- Desktop Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="logo">
        <h1>BISDIG</h1>
    </div>
    <nav>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('student.personal.index') }}"
                    class="nav-link {{ request()->is('student/personal') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line nav-icon"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('student.personal.editDataIndex') }}"
                    class="nav-link {{ request()->is('student/personal/edit') ? 'active' : '' }} {{ request()->is('admin/students/create') ? 'active' : '' }}">
                    <i class="fa-solid fa-id-card nav-icon"></i>
                    Personal Information
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('student.counseling.show') }}"
                    class="nav-link {{ request()->is('student/counseling/') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments nav-icon"></i>
                    Counseling
                </a>
            </li>
        </ul>
    </nav>
</aside>
