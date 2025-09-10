<!-- Mobile Bottom Navigation -->
<nav class="mobile-nav">
    <ul class="mobile-nav-list">
        <li class="mobile-nav-item">
            <a href="{{ route('dashboard.admin.index') }}"
                class="mobile-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <span class="mobile-nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="{{ route('admin.students.index') }}"
                class="mobile-nav-link {{ request()->is('admin/students') ? 'active' : '' }} {{ request()->is('admin/students/create') ? 'active' : '' }}">
                <span class="mobile-nav-icon">👥</span>
                <span>Students</span>
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="{{ route('admin.counseling.index') }}"
                class="mobile-nav-link {{ request()->is('admin/counseling') ? 'active' : '' }}">
                <span class="mobile-nav-icon">📖</span>
                <span>Counseling</span>
            </a>
        </li>
        @if (auth()->user()->role === 'masteradmin' || auth()->user()->role === 'superadmin')
            <li class="mobile-nav-item">
                <a href="{{ route('user.admin.main') }}"
                    class="mobile-nav-link {{ request()->is('admin/user/main') ? 'active' : '' }}">
                    <span class="mobile-nav-icon">📖</span>
                    <span>Lecturers</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
