@extends('students.template.index')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <style>
        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .btn-primary {
            background-color: #4361ee;
            border: none;
            border-radius: 6px;
            padding: 12px 24px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .modal-content {
            border-radius: 16px;
            text-align: center;
            padding: 20px;
        }

        .modal-body i {
            font-size: 64px;
            color: #facc15;
            margin-bottom: 16px;
        }

        /* Map styling */
        #map {
            height: 400px;
            border-radius: 12px;
            border: 1px solid #ccc;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i> Edit Personal Information - {{ $student->nama_lengkap }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="studentForm" action="{{ route('student.personal.updateData') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @php
                                $readonly = $student->is_edited == 0 ? 'readonly disabled' : '';
                                $disabled = $student->is_edited == 0 ? 'disabled' : '';
                            @endphp

                            {{-- Foto --}}
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Profil</label>
                                @if ($student->foto)
                                    <small class="d-block mt-2">Foto saat ini:</small>
                                    <img src="{{ asset('storage/' . $student->foto) }}" alt="Foto Mahasiswa"
                                        class="img-thumbnail mt-1" style="max-width: 150px;">
                                    @if ($student->is_edited == 1)
                                        <input type="file" name="foto" id="foto"
                                            class="form-control form-control-lg mt-2" accept="image/*">
                                    @endif
                                @else
                                    <input type="file" name="foto" id="foto" class="form-control form-control-lg"
                                        accept="image/*" {{ $disabled }}>
                                @endif
                            </div>

                            {{-- Tanda Tangan --}}
                            <div class="mb-3">
                                <label for="ttd" class="form-label">Tanda Tangan</label>
                                @if ($student->ttd)
                                    <small class="d-block mt-2">TTD saat ini:</small>
                                    <img src="{{ asset('storage/' . $student->ttd) }}" alt="Tanda Tangan"
                                        class="img-thumbnail mt-1" style="max-width: 150px;">
                                    @if ($student->is_edited == 1)
                                        <input type="file" name="ttd" id="ttd"
                                            class="form-control form-control-lg mt-2" accept="image/*">
                                    @endif
                                @else
                                    <input type="file" name="ttd" id="ttd" class="form-control form-control-lg"
                                        accept="image/*" {{ $disabled }}>
                                @endif

                                @if ($student->is_edited == 1 && !$student->ttd)
                                    <div class="alert alert-warning d-flex align-items-center mt-3" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            <span class="fw-bold">Perhatian:</span>
                                            <span class="text-dark">Unggah tanda tangan digital dengan latar belakang
                                                transparan. Jika tanda tangan masih memiliki latar belakang, silakan
                                                bersihkan
                                                terlebih dahulu menggunakan
                                                <a href="https://www.photoroom.com/tools/background-remover" target="_blank"
                                                    class="fw-semibold text-decoration-underline">alat penghapus latar
                                                    belakang</a>.
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Nama Lengkap --}}
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap"
                                    class="form-control form-control-lg"
                                    value="{{ old('nama_lengkap', $student->nama_lengkap) }}" readonly disabled>
                            </div>

                            {{-- Nama Orangtua --}}
                            <div class="mb-3">
                                <label for="nama_orangtua" class="form-label">Nama Orangtua</label>
                                <input type="text" name="nama_orangtua" id="nama_orangtua"
                                    class="form-control form-control-lg"
                                    value="{{ old('nama_orangtua', $student->nama_orangtua) }}" {{ $readonly }}>
                            </div>

                            {{-- NIM --}}
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" name="nim" id="nim" class="form-control form-control-lg"
                                    value="{{ old('nim', $student->nim) }}" required readonly disabled>
                            </div>

                            {{-- Password --}}
                            @if ($isDefaultPassword)
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        Change Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="password" id="password"
                                        class="form-control form-control-lg"
                                        {{ $student->is_edited == 0 ? 'readonly disabled' : 'required minlength=8' }}>

                                    <small class="form-text text-muted">
                                        Password minimal 8 karakter, gunakan kombinasi huruf dan angka agar lebih aman.
                                    </small>
                                </div>
                            @endif

                            {{-- Angkatan --}}
                            <div class="mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input type="number" name="angkatan" id="angkatan"
                                    class="form-control form-control-lg"
                                    value="{{ old('angkatan', $student->angkatan) }}" readonly disabled>
                            </div>

                            {{-- Program Studi --}}
                            <div class="mb-3">
                                <label for="program_studi" class="form-label">Program Studi</label>
                                <input type="text" name="program_studi" id="program_studi"
                                    class="form-control form-control-lg"
                                    value="{{ old('program_studi', $student->program_studi) }}" readonly disabled>
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control form-control-lg"
                                    {{ $student->is_edited == 0 ? 'disabled' : '' }}>
                                    <option value="L"
                                        {{ old('jenis_kelamin', $student->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P"
                                        {{ old('jenis_kelamin', $student->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                    class="form-control form-control-lg"
                                    value="{{ old('tanggal_lahir', $student->tanggal_lahir) }}" {{ $readonly }}>
                            </div>

                            {{-- Alamat --}}
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control form-control-lg" {{ $readonly }}>{{ old('alamat', $student->alamat) }}</textarea>
                            </div>

                            {{-- Lokasi Berdasarkan Maps --}}
                            <div class="card mb-4">
                                <div class="card-header bg-light fw-semibold">
                                    <i class="fas fa-map-marker-alt me-2 text-danger"></i> Lokasi Berdasarkan Maps
                                </div>
                                <div class="card-body">
                                    @if ($student->is_edited == 0)
                                        @if ($student->alamat_lat && $student->alamat_lng)
                                            <p class="mb-2 text-muted">
                                                Lokasi rumah Anda tersimpan. Klik tombol di bawah untuk membuka di Google
                                                Maps:
                                            </p>

                                            <a href="https://www.google.com/maps?q={{ $student->alamat_lat }},{{ $student->alamat_lng }}"
                                                target="_blank" class="btn btn-outline-primary">
                                                <i class="fas fa-map-marked-alt me-2"></i> Lihat di Google Maps
                                            </a>
                                        @else
                                            <p class="text-muted">Belum ada titik lokasi yang disimpan.</p>
                                        @endif
                                    @else
                                        <div id="map"></div>
                                        <input type="hidden" name="alamat_lat" id="alamat_lat"
                                            value="{{ old('alamat_lat', $student->alamat_lat) }}">
                                        <input type="hidden" name="alamat_lng" id="alamat_lng"
                                            value="{{ old('alamat_lng', $student->alamat_lng) }}">
                                        <small class="text-muted d-block mt-2">
                                            Geser marker atau gunakan kolom pencarian untuk menentukan lokasi alamat Anda.
                                        </small>
                                    @endif
                                </div>
                            </div>

                            {{-- No. Telepon --}}
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. HP</label>
                                <input type="text" name="no_telepon" id="no_telepon"
                                    class="form-control form-control-lg"
                                    value="{{ old('no_telepon', $student->no_telepon) }}" {{ $readonly }}>
                            </div>

                            {{-- No. Telepon Orang Tua --}}
                            <div class="mb-3">
                                <label for="no_telepon_orangtua" class="form-label">No. HP Orang Tua</label>
                                <input type="text" name="no_telepon_orangtua" id="no_telepon_orangtua"
                                    class="form-control form-control-lg"
                                    value="{{ old('no_telepon_orangtua', $student->no_telepon_orangtua) }}"
                                    {{ $readonly }}>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg"
                                    value="{{ old('email', $student->email) }}" {{ $readonly }}>
                            </div>

                            {{-- Submit --}}
                            <div class="d-grid">
                                @if ($student->is_edited == 0)
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#lockedModal">
                                        <i class="fas fa-lock me-2"></i> Update Information
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i> Update Information
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="lockedModal" tabindex="-1" aria-labelledby="lockedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-gradient bg-warning text-dark rounded-top-4">
                    <h5 class="modal-title fw-bold" id="lockedModalLabel">
                        <i class="fas fa-lock me-2"></i> Akses Dibatasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Perubahan Data Terkunci</h5>
                    <p class="text-muted mt-2">
                        Semua data sudah <span class="fw-semibold text-dark">terisi lengkap</span>.
                        Jika ada kesalahan atau butuh perubahan, silakan hubungi <b>Dosen Pembimbing</b>.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-warning px-4 fw-semibold rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-check-circle me-1"></i> Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($student->is_edited == 1)
        <script src="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.js"></script>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var lat = {{ old('alamat_lat', $student->alamat_lat ?? -6.2) }};
                var lng = {{ old('alamat_lng', $student->alamat_lng ?? 106.816666) }};

                var map = L.map('map').setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                var marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                marker.on('dragend', function(e) {
                    var position = marker.getLatLng();
                    document.getElementById('alamat_lat').value = position.lat;
                    document.getElementById('alamat_lng').value = position.lng;
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
                    })
                    .addTo(map);
            });
        </script>
    @endif
@endpush
