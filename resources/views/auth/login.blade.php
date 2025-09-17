@extends('auth.template.index')
@section('content')
    <div class="login-container">
        <div class="login-card">
            <!-- Loading Overlay -->
            <div class="form-overlay" id="loadingOverlay">
                <div class="spinner"></div>
            </div>

            <h2 class="login-title">
                <i class="fas fa-graduation-cap me-2"></i>
                Login Portal
            </h2>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="loginTabs" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link active w-100" id="dosen-tab" data-bs-toggle="tab" data-bs-target="#dosen"
                        type="button" role="tab" aria-controls="dosen" aria-selected="true">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Dosen
                    </button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100" id="mahasiswa-tab" data-bs-toggle="tab" data-bs-target="#mahasiswa"
                        type="button" role="tab" aria-controls="mahasiswa" aria-selected="false">
                        <i class="fas fa-user-graduate me-2"></i>
                        Mahasiswa
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="loginTabsContent">
                <!-- Dosen tab -->
                <div class="tab-pane fade show active" id="dosen" role="tabpanel" aria-labelledby="dosen-tab">
                    <form method="POST" action="{{ route('auth.login.dosen') }}"id="dosenForm">
                        @csrf
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Email address" required>
                            <label for="email">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <i class="fas fa-eye input-icon" id="togglePassword" style="cursor: pointer;"></i>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <span>
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Sign In as Dosen
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Mahasiswa tab -->
                <div class="tab-pane fade" id="mahasiswa" role="tabpanel" aria-labelledby="mahasiswa-tab">
                    <form method="POST" action="{{ route('auth.login.mahasiswa') }}" id="mahasiswaForm">
                        @csrf
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM"
                                required>
                            <label for="nim">
                                <i class="fas fa-id-card me-2"></i>Nomor Induk Mahasiswa
                            </label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <i class="fas fa-eye input-icon" id="togglePassword" style="cursor: pointer;"></i>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <span>
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Masuk sebagai Mahasiswa
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Error Alert (Hidden by default) -->
            <div class="alert alert-danger" style="display: none;" id="errorAlert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span id="errorMessage">Login failed. Please try again.</span>
            </div>
        </div>
    </div>
@endsection
