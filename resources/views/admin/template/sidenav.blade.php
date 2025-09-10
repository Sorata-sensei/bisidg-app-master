  <!-- Desktop Sidebar -->
  <aside class="sidebar" id="sidebar">
      <div class="logo">
          <h1>BISDIG</h1>
      </div>
      <nav>
          <ul class="nav-menu">
              <li class="nav-item">
                  <a href="{{ route('dashboard.admin.index') }}"
                      class="nav-link  {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                      <span class="nav-icon">📊</span>
                      Dashboard
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.students.index') }}"
                      class="nav-link {{ request()->is('admin/students') ? 'active' : '' }}{{ request()->is('admin/students/create') ? 'active' : '' }}">
                      <span class="nav-icon">👥</span>
                      Students
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.counseling.index') }}"
                      class="nav-link {{ request()->is('admin/counseling') ? 'active' : '' }}">
                      <span class="nav-icon">📖</span>
                      Counseling
                  </a>
              </li>
              @if (auth()->user()->role === 'masteradmin' || auth()->user()->role === 'superadmin')
                  <li class="nav-item">
                      <a href="{{ route('user.admin.main') }}"
                          class="nav-link {{ request()->is('admin/user/main') ? 'active' : '' }}">
                          <span class="nav-icon">📖</span>
                          Lecturers
                      </a>
                  </li>
              @endif
          </ul>
      </nav>
  </aside>
