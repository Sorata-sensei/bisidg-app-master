  <!-- Desktop Sidebar -->
  <aside class="sidebar" id="sidebar">
      <div class="logo">
          <h1>BISDIG</h1>
      </div>
      <nav>
          <ul class="nav-menu">
              <li class="nav-item">
                  <a href="{{ route('student.personal.index') }}"
                      class="nav-link  {{ request()->is('student/personal') ? 'active' : '' }}">
                      <span class="nav-icon">📊</span>
                      Dashboard
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('student.personal.editDataIndex', session('student_id')) }}"
                      class="nav-link {{ request()->is('student/personal/' . session('student_id') . '/edit') ? 'active' : '' }}{{ request()->is('admin/students/create') ? 'active' : '' }}">
                      <span class="nav-icon">👥</span>
                      Personal Information
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('student.counseling.show', session('student_id')) }}"
                      class="nav-link {{ request()->is('admin/counseling') ? 'active' : '' }}">
                      <span class="nav-icon">📚</span>
                      Counseling
                  </a>
              </li>
          </ul>
      </nav>

  </aside>
