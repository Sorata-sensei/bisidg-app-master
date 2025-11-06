@extends('students.layouts.super-app')

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .page-subtitle {
            color: var(--text-gray);
            font-size: 14px;
            margin: 0;
        }

        /* Profile Cards */
        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            overflow: hidden;
            transition: var(--transition-normal);
        }

        .profile-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .profile-card-header {
            background: linear-gradient(135deg, var(--primary-orange), #FFB347);
            padding: 15px 20px;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 16px;
        }

        .profile-card-header i {
            font-size: 20px;
        }

        .profile-card-body {
            padding: 25px;
        }

        /* Photo Display */
        .photo-display {
            text-align: center;
            margin-bottom: 20px;
        }

        .photo-wrapper {
            width: 200px;
            height: 200px;
            margin: 0 auto 20px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        }

        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: var(--primary-orange);
        }

        /* Signature Display */
        .signature-wrapper {
            width: 100%;
            height: 150px;
            margin: 0 auto 20px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: white;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Form Styling */
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: var(--transition-normal);
            background: #fafafa;
        }

        .form-control:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.15);
            background: white;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background: #f5f5f5;
            border-color: #e0e0e0;
            color: #999;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        /* Buttons */
        .btn-upload {
            background: linear-gradient(135deg, var(--primary-orange), #FFB347);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: white;
            transition: var(--transition-normal);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
        }

        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
            color: white;
        }

        .btn-save {
            background: linear-gradient(135deg, #4CAF50, #81C784);
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-weight: 600;
            color: white;
            transition: var(--transition-normal);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
            color: white;
        }

        /* File Input Custom */
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
            margin-bottom: 15px;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 20px;
            background: white;
            border: 2px dashed var(--primary-orange);
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition-normal);
            color: var(--primary-orange);
            font-weight: 500;
        }

        .file-input-label:hover {
            background: rgba(255, 152, 0, 0.05);
            border-color: #FF7043;
        }

        .file-input-label i {
            font-size: 20px;
        }

        /* Map Container */
        #map {
            width: 100%;
            height: 350px;
            border-radius: 15px;
            border: 2px solid #e0e0e0;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }

        /* Info Alert */
        .info-alert {
            background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
            border: none;
            border-radius: 15px;
            padding: 20px;
            color: #1976D2;
            display: flex;
            align-items: start;
            gap: 15px;
        }

        .info-alert i {
            font-size: 24px;
            margin-top: 2px;
        }

        .info-alert-content h6 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .info-alert-content p {
            margin: 0;
            font-size: 14px;
        }

        /* Student Info Badge */
        .student-info {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.1), rgba(255, 179, 71, 0.1));
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .student-info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .student-info-item:last-child {
            margin-bottom: 0;
        }

        .student-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-orange), #FFB347);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .student-info-text {
            flex: 1;
        }

        .student-info-label {
            font-size: 12px;
            color: var(--text-gray);
            margin-bottom: 2px;
        }

        .student-info-value {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
        }

        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 20px;
            display: flex;
            align-items: start;
            gap: 15px;
            min-width: 350px;
            max-width: 450px;
            z-index: 10000;
            opacity: 0;
            transform: translateX(450px);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast-notification.success {
            border-left: 5px solid #4CAF50;
        }

        .toast-notification.error {
            border-left: 5px solid #FF5252;
        }

        .toast-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .toast-notification.success .toast-icon {
            background: linear-gradient(135deg, #4CAF50, #81C784);
            color: white;
        }

        .toast-notification.error .toast-icon {
            background: linear-gradient(135deg, #FF5252, #FF8A80);
            color: white;
        }

        .toast-content {
            flex: 1;
        }

        .toast-content h6 {
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 5px 0;
            font-size: 16px;
        }

        .toast-content p {
            margin: 0;
            color: var(--text-gray);
            font-size: 14px;
            line-height: 1.4;
        }

        .toast-close {
            background: transparent;
            border: none;
            color: var(--text-gray);
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition-fast);
        }

        .toast-close:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-dark);
        }

        /* Toast Animation */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(450px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(450px);
            }
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.1), rgba(255, 179, 71, 0.1));
        }

        .table thead th {
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 2px solid var(--primary-orange);
            padding: 15px 12px;
            font-size: 14px;
        }

        .table tbody td {
            padding: 15px 12px;
            vertical-align: middle;
            font-size: 14px;
        }

        .table-hover tbody tr {
            transition: var(--transition-fast);
        }

        .table-hover tbody tr:hover {
            background: rgba(255, 152, 0, 0.05);
        }

        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 8px;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 22px;
            }

            .photo-wrapper {
                width: 150px;
                height: 150px;
            }

            .profile-card-body {
                padding: 20px;
            }

            .toast-notification {
                right: 10px;
                left: 10px;
                min-width: auto;
                max-width: none;
            }

            .table-responsive {
                font-size: 12px;
            }

            .table thead th,
            .table tbody td {
                padding: 10px 8px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Toast Notifications -->
    @if (session('success'))
        <div class="toast-notification success show" id="toastNotification">
            <div class="toast-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="toast-content">
                <h6>Berhasil!</h6>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" class="toast-close" onclick="closeToast()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="toast-notification error show" id="toastNotification">
            <div class="toast-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div class="toast-content">
                <h6>Gagal!</h6>
                <p>{{ session('error') }}</p>
            </div>
            <button type="button" class="toast-close" onclick="closeToast()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="toast-notification error show" id="toastNotification">
            <div class="toast-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="toast-content">
                <h6>Terjadi Kesalahan!</h6>
                <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="toast-close" onclick="closeToast()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <h2 class="page-title">
            <i class="bi bi-person-badge"></i>
            Profil Mahasiswa
        </h2>
        <p class="page-subtitle">Kelola informasi personal dan data akademik Anda</p>
    </div>

    <div class="row g-4">
        <!-- Left Column - Profile Photo & Signature -->
        <div class="col-lg-4">
            <!-- Profile Photo Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="bi bi-camera-fill"></i>
                    <span>Foto Profil</span>
                </div>
                <div class="profile-card-body">
                    <div class="photo-display">
                        <div class="photo-wrapper">
                            @if ($student->foto)
                                <img src="{{ asset('storage/' . $student->foto) }}" alt="Foto Profil">
                            @else
                                <div class="photo-placeholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($student->is_edited)
                        <form action="{{ route('student.personal.updateData') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="foto">
                            
                            <div class="file-input-wrapper">
                                <input type="file" name="foto" id="fotoInput" accept="image/*">
                                <label for="fotoInput" class="file-input-label">
                                    <i class="bi bi-cloud-upload"></i>
                                    <span>Pilih Foto Baru</span>
                                </label>
                            </div>

                            <button type="submit" class="btn-upload">
                                <i class="bi bi-save"></i>
                                Simpan Foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Signature Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="bi bi-pen-fill"></i>
                    <span>Tanda Tangan</span>
                </div>
                <div class="profile-card-body">
                    <div class="signature-wrapper">
                        @if ($student->ttd)
                            <img src="{{ asset('storage/' . $student->ttd) }}" alt="Tanda Tangan">
                        @else
                            <span style="color: #ccc; font-size: 14px;">Belum ada tanda tangan</span>
                        @endif
                    </div>

                    @if ($student->is_edited)
                        <form action="{{ route('student.personal.updateData') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="ttd">
                            
                            <div class="file-input-wrapper">
                                <input type="file" name="ttd" id="ttdInput" accept="image/*">
                                <label for="ttdInput" class="file-input-label">
                                    <i class="bi bi-cloud-upload"></i>
                                    <span>Pilih TTD Baru</span>
                                </label>
                            </div>

                            <button type="submit" class="btn-upload">
                                <i class="bi bi-save"></i>
                                Simpan TTD
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Student Info Badge -->
            <div class="student-info">
                <div class="student-info-item">
                    <div class="student-info-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="student-info-text">
                        <div class="student-info-label">Nama Lengkap</div>
                        <div class="student-info-value">{{ $student->nama_lengkap }}</div>
                    </div>
                </div>
                <div class="student-info-item">
                    <div class="student-info-icon">
                        <i class="bi bi-card-text"></i>
                    </div>
                    <div class="student-info-text">
                        <div class="student-info-label">NIM</div>
                        <div class="student-info-value">{{ $student->nim }}</div>
                    </div>
                </div>
                <div class="student-info-item">
                    <div class="student-info-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div class="student-info-text">
                        <div class="student-info-label">Program Studi</div>
                        <div class="student-info-value">{{ $student->program_studi ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Personal Data Form -->
        <div class="col-lg-8">
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="bi bi-file-person-fill"></i>
                    <span>Informasi Personal</span>
                </div>
                <div class="profile-card-body">
                    <form action="{{ route('student.personal.updateData') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="text">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-people me-1"></i>
                                        Nama Orangtua
                                    </label>
                                    <input type="text" name="nama_orangtua" class="form-control"
                                        value="{{ old('nama_orangtua', $student->nama_orangtua) }}" 
                                        @readonly(!$student->is_edited)
                                        placeholder="Masukkan nama orangtua">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-gender-ambiguous me-1"></i>
                                        Jenis Kelamin
                                    </label>
                                    <select name="jenis_kelamin" class="form-control" @disabled(!$student->is_edited)>
                                        <option value="L" @selected($student->jenis_kelamin == 'L')>Laki-laki</option>
                                        <option value="P" @selected($student->jenis_kelamin == 'P')>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-calendar me-1"></i>
                                        Tanggal Lahir
                                    </label>
                                    <input type="date" name="tanggal_lahir" class="form-control"
                                        value="{{ $student->tanggal_lahir }}" @readonly(!$student->is_edited)>
                                </div>
                            </div>

                            @if ($isDefaultPassword)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-key me-1"></i>
                                            Password Baru
                                        </label>
                                        <input type="password" name="password" class="form-control" 
                                            @readonly(!$student->is_edited)
                                            minlength="8" placeholder="Minimal 8 karakter">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-geo-alt me-1"></i>
                                Alamat Lengkap
                            </label>
                            <textarea name="alamat" rows="3" class="form-control" @readonly(!$student->is_edited)
                                placeholder="Masukkan alamat lengkap">{{ old('alamat', $student->alamat) }}</textarea>
                        </div>

                        {{-- Lokasi Maps --}}
                        @if ($student->is_edited)
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-map me-1"></i>
                                    Lokasi di Peta
                                </label>
                                <div id="map"></div>
                                <input type="hidden" name="alamat_lat" id="alamat_lat"
                                    value="{{ old('alamat_lat', $student->alamat_lat) }}">
                                <input type="hidden" name="alamat_lng" id="alamat_lng"
                                    value="{{ old('alamat_lng', $student->alamat_lng) }}">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Seret pin untuk menyesuaikan lokasi Anda
                                </small>
                            </div>
                        @else
                            @if ($student->alamat_lat && $student->alamat_lng)
                                <div class="form-group">
                                    <a href="https://www.google.com/maps?q={{ $student->alamat_lat }},{{ $student->alamat_lng }}"
                                        target="_blank" class="btn btn-outline-primary">
                                        <i class="bi bi-map me-2"></i>
                                        Lihat Lokasi di Google Maps
                                    </a>
                                </div>
                            @endif
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-phone me-1"></i>
                                        No. HP
                                    </label>
                                    <input type="text" name="no_telepon" class="form-control"
                                        value="{{ old('no_telepon', $student->no_telepon) }}" 
                                        @readonly(!$student->is_edited)
                                        placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-phone-fill me-1"></i>
                                        No. HP Orang Tua
                                    </label>
                                    <input type="text" name="no_telepon_orangtua" class="form-control"
                                        value="{{ old('no_telepon_orangtua', $student->no_telepon_orangtua) }}"
                                        @readonly(!$student->is_edited)
                                        placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-envelope me-1"></i>
                                Email
                            </label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $student->email) }}" 
                                @readonly(!$student->is_edited)
                                placeholder="email@example.com">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-trophy me-1"></i>
                                        IPK (Indeks Prestasi Kumulatif)
                                    </label>
                                    <input type="number" name="ipk" class="form-control"
                                        value="{{ old('ipk', $student->ipk) }}" 
                                        @readonly(!$student->is_edited)
                                        step="0.01" min="0" max="4"
                                        placeholder="contoh: 3.75">
                                    <small class="text-muted">Skala 0.00 - 4.00</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-book me-1"></i>
                                        SKS (Satuan Kredit Semester)
                                    </label>
                                    <input type="number" name="sks" class="form-control"
                                        value="{{ old('sks', $student->sks) }}"
                                        @readonly(!$student->is_edited)
                                        min="0" max="200"
                                        placeholder="contoh: 120">
                                    <small class="text-muted">Total SKS yang telah ditempuh</small>
                                </div>
                            </div>
                        </div>

                        @if ($student->is_edited)
                            <div class="text-end mt-4">
                                <button type="submit" class="btn-save">
                                    <i class="bi bi-check-circle"></i>
                                    Simpan Semua Data
                                </button>
                            </div>
                        @else
                            <div class="info-alert mt-4">
                                <i class="bi bi-lock-fill"></i>
                                <div class="info-alert-content">
                                    <h6>Data Terkunci</h6>
                                    <p>Data Anda sudah terkunci dan tidak dapat diubah. Jika ada perubahan yang diperlukan, 
                                       silakan hubungi <strong>dosen pembimbing akademik</strong> Anda.</p>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Achievements Card -->
            <div class="profile-card mt-4">
                <div class="profile-card-header">
                    <i class="bi bi-trophy-fill"></i>
                    <span>Prestasi & Penghargaan</span>
                </div>
                <div class="profile-card-body">
                    <!-- Add Achievement Form -->
                    @if ($student->is_edited)
                        <form action="{{ route('student.personal.achievement.store') }}" method="POST" 
                            enctype="multipart/form-data" class="mb-4" id="achievementForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Nama Event
                                        </label>
                                        <input type="text" name="event" class="form-control" 
                                            placeholder="contoh: Kompetisi Web Development Nasional" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-award me-1"></i>
                                            Prestasi yang Diraih
                                        </label>
                                        <input type="text" name="achievement" class="form-control" 
                                            placeholder="contoh: Juara 1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-globe me-1"></i>
                                            Tingkat
                                        </label>
                                        <select name="level" class="form-control" required>
                                            <option value="">Pilih Tingkat</option>
                                            <option value="Sekolah/Kampus">Sekolah/Kampus</option>
                                            <option value="Kota/Kabupaten">Kota/Kabupaten</option>
                                            <option value="Regional">Regional</option>
                                            <option value="Nasional">Nasional</option>
                                            <option value="Internasional">Internasional</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>
                                            Upload Piagam (Opsional)
                                        </label>
                                        <input type="file" name="certificate" class="form-control" 
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Format: PDF, JPG, PNG (Maks. 5MB)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn-upload">
                                    <i class="bi bi-plus-circle"></i>
                                    Tambah Prestasi
                                </button>
                            </div>
                        </form>

                        <hr style="border-top: 2px solid #e0e0e0; margin: 30px 0;">
                    @endif

                    <!-- Achievements Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="achievementsTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Event</th>
                                    <th style="width: 20%;">Prestasi</th>
                                    <th style="width: 15%;">Tingkat</th>
                                    <th style="width: 15%;">Piagam</th>
                                    @if ($student->is_edited)
                                        <th style="width: 20%;">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($student->achievements as $index => $achievement)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $achievement->event }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $achievement->achievement }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $achievement->level }}</span>
                                        </td>
                                        <td>
                                            @if ($achievement->certificate)
                                                <a href="{{ asset('storage/' . $achievement->certificate) }}" 
                                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        @if ($student->is_edited)
                                            <td>
                                                <form action="{{ route('student.personal.achievement.delete', $achievement->id) }}" 
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="mt-2">Belum ada prestasi yang ditambahkan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toast Notification Functions
        function closeToast() {
            const toast = document.getElementById('toastNotification');
            if (toast) {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }
        }

        // Auto hide toast after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toastNotification');
            if (toast) {
                setTimeout(() => {
                    closeToast();
                }, 5000);
            }
        });
    </script>

    @if ($student->is_edited)
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var lat = {{ old('alamat_lat', $student->alamat_lat ?? -6.2) }};
                var lng = {{ old('alamat_lng', $student->alamat_lng ?? 106.816666) }};
                var map = L.map('map').setView([lat, lng], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                var marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                
                marker.on('dragend', function(e) {
                    var pos = marker.getLatLng();
                    document.getElementById('alamat_lat').value = pos.lat;
                    document.getElementById('alamat_lng').value = pos.lng;
                });

                L.Control.geocoder({
                    defaultMarkGeocode: false
                })
                .on('markgeocode', function(e) {
                    var center = e.geocode.center;
                    map.setView(center, 16);
                    marker.setLatLng(center);
                    document.getElementById('alamat_lat').value = center.lat;
                    document.getElementById('alamat_lng').value = center.lng;
                }).addTo(map);
            });
        </script>
    @endif
@endpush
