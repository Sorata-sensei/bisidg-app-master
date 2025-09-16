@include('students.template.header')



<div class="admin-container">
    @include('students.message.index')
    @include('students.template.sidenav')
    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <div>
                <h2>Selamat {{ $greeting }} {{ session('student_nama') }}</h2>
            </div>
            <div class="user-profile">
                <div class="profile-img" style=" object-fit: cover; object-position: center;">
                    @if (session('path_pic') !== 0)
                        <img src="{{ asset('storage/' . session('path_pic')) }}" alt="Photo Student"
                            class="img-fluid rounded-circle">
                    @else
                        {{ collect(explode(' ', session('student_nama')))->map(fn($word) => strtoupper(substr($word, 0, 1)))->join('') }}
                    @endif

                </div>

                <div class="dropdown">
                    <a class="btn  dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ collect(explode(' ', session('student_nama')))->map(fn($word) => strtoupper(substr($word, 0, 1)))->join('') }}
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('auth.logout') }}">Logout</a></li>
                    </ul>
                </div>
            </div>

        </header>

        @yield('content')
    </main>


    @include('students.template.mobile')

</div>

@include('students.template.footer')
@stack('scripts')
