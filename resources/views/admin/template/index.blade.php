{{-- @include('admin.template.header')

@include('admin.message.index')

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @include('admin.template.sidenav')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>



                @include('admin.template.nav')


            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{ $menu }}</h1>

                </div>

                @yield('content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Anwar Fauzi {{ now()->year }}</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->





<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<!-- Logout Modal-->
@include('admin.template.modout')
@include('admin.template.footer')
@stack('scripts') --}}

@include('admin.template.header')

@include('admin.message.index')

<div class="admin-container">

    @include('admin.template.sidenav')
    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <div>
                <h2>Selamat {{ $greeting }}</h2>
            </div>
            <div class="user-profile">
                <div class="profile-img">
                    {{ collect(explode(' ', auth()->user()->name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->join('') }}
                </div>

                <div class="dropdown">
                    <a class="btn  dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>

                    <ul class="dropdown-menu">
                        <li><a href="{{ route('user.admin.index') }}" class="dropdown-item">Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('auth.logout') }}">Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        @yield('content')
    </main>

    @include('admin.template.mobile')

</div>

@include('admin.template.footer')
@stack('scripts')
