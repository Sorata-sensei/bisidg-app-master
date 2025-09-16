@extends('students.template.index')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Select2 Styling */
        .select2-container .select2-selection--multiple {
            min-height: 120px;
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            background: #fff;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd;
            border: none;
            color: #fff;
            padding: 4px 8px;
            margin-top: 4px;
            border-radius: 4px;
            font-size: 13px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            margin-right: 4px;
        }

        /* Card Styling */
        .form-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            padding: 20px;
            background: #fff;
        }

        .form-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-header h5 {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Foto 3x4 */
        .pas-frame {
            overflow: hidden;
            background: #fff;
            display: inline-block;
        }

        .pas-foto {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .pas-frame--3x4 {
            width: 120px;
            aspect-ratio: 3/4;
        }

        @media print {
            .pas-frame--3x4 {
                width: 2.79cm;
                aspect-ratio: 3/4;
            }
        }

        /* Field Styling */
        .field-row {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .field-label {
            flex: 0 0 150px;
            font-weight: 600;
            color: #212529;
        }

        .field-value {
            flex: 1;
            padding: .375rem .75rem;
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            color: #212529;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 576px) {
            .field-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .field-label {
                flex: none;
                margin-bottom: .25rem;
            }

            .field-value {
                width: 100%;
                white-space: normal;
            }

            .pas-frame--3x4 {
                width: 80px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="form-card mx-auto col-12 col-md-10 col-lg-12">

            <!-- Header -->
            <div class="form-header">
                <h5>KARTU KONSULTASI DENGAN PEMBIMBING AKADEMIK</h5>
                <p><strong>Universitas Sugeng Hartono</strong></p>
            </div>

            <!-- Data Mahasiswa -->
            <div class="row g-3 mb-3">
                <!-- Info Mahasiswa -->
                <div class="col-12 col-md-9 order-2 order-md-1">
                    <div class="field-row">
                        <label class="field-label">Nama Mahasiswa</label>
                        <p class="field-value">{{ $student->nama_lengkap }}</p>
                    </div>
                    <div class="field-row">
                        <label class="field-label">NIM</label>
                        <p class="field-value">{{ $student->nim }}</p>
                    </div>
                    <div class="field-row">
                        <label class="field-label">Nama Orang Tua</label>
                        <p class="field-value">{{ $student->nama_orangtua }}</p>
                    </div>
                    <div class="field-row">
                        <label class="field-label">Alamat</label>
                        <p class="field-value">{{ $student->alamat }}</p>
                    </div>
                    <div class="field-row">
                        <label class="field-label">No. HP</label>
                        <p class="field-value">{{ $student->no_telepon }}</p>
                    </div>
                    <div class="field-row">
                        <label class="field-label">Dosen PA</label>
                        <p class="field-value">{{ $student->dosenPA->name ?? '-' }}</p>
                    </div>
                </div>

                <!-- Foto -->
                <div class="col-12 col-md-3 order-1 order-md-2 text-center">
                    <div class="pas-frame pas-frame--3x4 mx-auto mb-3 mb-md-0">
                        <img src="{{ asset('storage/' . $student->foto) }}" class="pas-foto" alt="Pas foto">
                    </div>
                </div>
            </div>

            <!-- Riwayat Konsultasi -->
            <div class="mt-4">
                <h6 class="mb-3"><b>Riwayat Konsultasi Terbaru</b> </h6>

                @if ($history->isNotEmpty())
                    @php $row = $history->first(); @endphp
                    <div class="border-3 rounded p-3 bg-light small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary"># Terbaru</span>
                            <small class="text-muted"><b>{{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('l, d F Y') }}
                                </b></small>
                        </div>

                        <p class="mb-1"><strong>Semester:</strong> {{ $row['semester'] }}</p>
                        <p class="mb-1"><strong>SKS yang diambil:</strong> {{ $row['sks'] }}</p>
                        <p class="mb-1"><strong>IP Semester lalu:</strong> {{ $row['ip'] }}</p>

                        <p class="mb-1"><strong>Komentar PA:</strong><br>
                            <span class="text-muted">{{ $row['komentar'] ?? '-' }}</span>
                        </p>

                        <p class="mb-1"><strong>Mata Kuliah Tidak Lulus:</strong><br>
                            @if ($row->failed_courses_objects->count())
                                @foreach ($row->failed_courses_objects as $fc)
                                    <span class="badge bg-danger text-white mb-1">
                                        {{ $fc->code_prefix }}{{ $fc->code_number }} - {{ $fc->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">Tidak Ada</span>
                            @endif
                        </p>

                        {{-- <div class="d-flex justify-content-end mt-3">
                            <div class="text-center">
                                <small class="d-block text-muted">Mahasiswa</small>
                                <img src="{{ asset('storage/' . $student->ttd) }}" alt="TTD Mahasiswa"
                                    style="max-height: 30px;">
                            </div>
                            <div class="text-center">
                                <small class="d-block text-muted">Dosen</small>
                                <img src="{{ asset('storage/' . $student->dosenPA->ttd) }}" alt="TTD Dosen"
                                    style="max-height: 30px;">
                            </div>
                        </div> --}}
                    </div>
                @else
                    <div class="alert alert-warning">Belum ada riwayat konsultasi</div>
                @endif
            </div>


            <!-- Form Input Baru -->
            @if ($student->is_counseling == 1)
                <h6 class="mb-3"><b>Isi Kartu Konsultasi</b> </h6>
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('student.counseling.store', $student->id) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <label class="form-label">Semester</label>
                                    <input type="number" name="semester" class="form-control" required>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label">SKS Yang diambil</label>
                                    <input type="number" name="sks" class="form-control" required>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label">IP Semester lalu</label>
                                    <input type="text" name="ip" class="form-control">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label">Tanggal Bimbingan</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Komentar Pembimbing Akademik</label>
                                    <input type="text" name="komentar" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Mata Kuliah Tidak Lulus</label>
                                    <select name="failed_courses[]" id="failedCoursesSelect" class="form-select" multiple>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">
                                                {{ $course->code_prefix }}{{ $course->code_number }} -
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Bisa pilih lebih dari 1, jika tidak ada abaikan saja</small>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <b>Layanan bimbingan akademik belum dibuka atau sudah ditutup.</b>
                </div>
            @endif

            {{-- 
            <div class="mt-4">
                <small>
                    <strong>NB:</strong><br>
                    1. Pertemuan konsultasi minimal 4 kali per semester.<br>
                    2. Kartu bimbingan ini dipegang oleh Dosen PA dan Mahasiswa.
                </small>
            </div> --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#failedCoursesSelect').select2({
                placeholder: "Pilih mata kuliah gagal",
                width: '100%'
            });
        });
    </script>
@endpush
