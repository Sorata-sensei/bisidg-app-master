@extends('students.template.index')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.css">
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
            /* kuning */
            margin-bottom: 16px;
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
                        <form id="studentForm" action="{{ route('student.personal.updateData', $student->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Foto --}}
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Profil</label>
                                @if ($student->foto)
                                    <small class="d-block mt-2">Foto saat ini:</small>
                                    <img src="{{ asset('storage/' . $student->foto) }}" alt="Foto Mahasiswa"
                                        class="img-thumbnail mt-1" style="max-width: 150px;">
                                @else
                                    <input type="file" name="foto" id="foto" class="form-control form-control-lg"
                                        accept="image/*">
                                @endif
                            </div>

                            {{-- Tanda Tangan --}}
                            <div class="mb-3">
                                <label for="ttd" class="form-label">Tanda Tangan</label>
                                @if ($student->ttd)
                                    <small class="d-block mt-2">TTD saat ini:</small>
                                    <img src="{{ asset('storage/' . $student->ttd) }}" alt="Tanda Tangan"
                                        class="img-thumbnail mt-1" style="max-width: 150px;">
                                @else
                                    <input type="file" name="ttd" id="ttd" class="form-control form-control-lg"
                                        accept="image/*">
                                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            <span class="fw-bold">Perhatian:</span>
                                            <span class="text-dark">Unggah tanda tangan digital dengan latar belakang
                                                transparan.
                                                Jika tanda tangan masih memiliki latar belakang, silakan bersihkan terlebih
                                                dahulu menggunakan
                                                <a href="https://www.photoroom.com/tools/background-remover" target="_blank"
                                                    class="fw-semibold text-decoration-underline">alat penghapus latar
                                                    belakang</a>.</span>
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
                                    value="{{ old('nama_orangtua', $student->nama_orangtua) }}"
                                    {{ $student->nama_orangtua ? 'readonly disabled' : '' }}>
                            </div>

                            {{-- NIM --}}
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" name="nim" id="nim" class="form-control form-control-lg"
                                    value="{{ old('nim', $student->nim) }}" required readonly disabled>
                            </div>

                            {{-- Angkatan --}}
                            <div class="mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input type="number" name="angkatan" id="angkatan" class="form-control form-control-lg"
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
                                    {{ $student->jenis_kelamin ? 'disabled' : '' }}>
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
                                    value="{{ old('tanggal_lahir', $student->tanggal_lahir) }}"
                                    {{ $student->tanggal_lahir ? 'readonly disabled' : '' }}>
                            </div>

                            {{-- Alamat --}}
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control form-control-lg"
                                    {{ $student->alamat ? 'readonly disabled' : '' }}>{{ old('alamat', $student->alamat) }}</textarea>
                            </div>

                            {{-- No. Telepon --}}
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. HP</label>
                                <input type="text" name="no_telepon" id="no_telepon"
                                    class="form-control form-control-lg"
                                    value="{{ old('no_telepon', $student->no_telepon) }}"
                                    {{ $student->no_telepon ? 'readonly disabled' : '' }}>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg"
                                    value="{{ old('email', $student->email) }}"
                                    {{ $student->email ? 'readonly disabled' : '' }}>
                            </div>

                            {{-- Submit --}}
                            <div class="d-grid">
                                @php
                                    $allFilled =
                                        $student->nama_orangtua &&
                                        $student->tanggal_lahir &&
                                        $student->alamat &&
                                        $student->no_telepon &&
                                        $student->email &&
                                        $student->foto &&
                                        $student->ttd &&
                                        $student->jenis_kelamin;
                                @endphp

                                @if ($allFilled)
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
            <div class="modal-content">
                <div class="modal-body">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h5 class="mt-3">Perubahan Data Terkunci</h5>
                    <p>Semua data sudah terisi. Penggantian data harus melalui <b>Dosen Pembimbing</b>.</p>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.js"></script>
@endpush
