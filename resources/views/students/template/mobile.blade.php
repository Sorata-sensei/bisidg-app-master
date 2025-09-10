<!-- Mobile Bottom Navigation -->
<nav class="mobile-nav">
    <ul class="mobile-nav-list">
        <li class="mobile-nav-item">
            <a href="{{ route('student.personal.index') }}"
                class="mobile-nav-link {{ request()->is('student/personal') ? 'active' : '' }}">
                <span class="mobile-nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="{{ route('student.personal.editDataIndex', session('student_id')) }}"
                class="mobile-nav-link {{ request()->is('student/personal/' . session('student_id') . '/edit') ? 'active' : '' }}">
                <span class="mobile-nav-icon">👥</span>
                <span>Personal Info</span>
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="{{ route('student.counseling.show', session('student_id')) }}"
                class="mobile-nav-link {{ request()->is('student/counseling') ? 'active' : '' }}">
                <span class="mobile-nav-icon">📚</span>
                <span>Counseling</span>
            </a>
        </li>
    </ul>
</nav>
