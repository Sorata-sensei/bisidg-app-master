@extends('students.template.index')

@push('css')
    <style>
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

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Pas Foto Frame */
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

        /* Field Info */
        .field-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            /* margin-bottom: .75rem; */
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

        /* Tabel biar bisa scroll di layar kecil */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            min-width: 800px;
        }

        /* Field info agar stack rapi di mobile */
        @media (max-width: 768px) {
            .col-7 .field-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .col-7 .field-label {
                margin-bottom: .25rem;
                width: 100%;
                max-width: 100%;
            }

            .col-7 .field-value {
                width: 100%;
                white-space: normal;
                word-wrap: break-word;
            }
        }

        /* Foto biar ga overflow */
        .pas-frame {
            max-width: 100%;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="form-card mx-auto col-12 col-md-10 col-lg-12">
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

            <!-- Riwayat + Input Baru -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Semester</th>
                            <th>SKS</th>
                            <th>IP</th>
                            <th>Tanggal</th>
                            <th>Komentar</th>
                            <th>Tanda Tangan Mahasiswa</th>
                            <th>Tanda Tangan Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row['semester'] }}</td>
                                <td>{{ $row['sks'] }}</td>
                                <td>{{ $row['ip'] }}</td>
                                <td>{{ $row['tanggal'] }}</td>
                                <td>{{ $row['komentar'] }}</td>
                                <td>

                                    <img src="{{ asset('storage/' . $student->ttd) }}" alt="TTD Mahasiswa"
                                        style="max-height: 60px;">

                                </td>
                                <td>

                                    <img src="{{ asset('storage/' . $student->dosenPA->ttd) }}" alt="TTD Dosen"
                                        style="max-height: 60px;">

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada riwayat konsultasi</td>
                            </tr>
                        @endforelse
                        <tr>
                            @if ($student->is_counseling == 1)
                                <form action="{{ route('student.counseling.store', $student->id) }}" method="POST">
                                    @csrf
                                    <td>{{ count($history) + 1 }}</td>
                                    <td><input type="number" name="semester" class="form-control" required></td>
                                    <td><input type="number" name="sks" class="form-control" required></td>
                                    <td><input type="text" name="ip" class="form-control"></td>
                                    <td><input type="date" name="tanggal" class="form-control" required></td>
                                    <td><input type="text" name="komentar" class="form-control"></td>
                                    <td colspan="2">
                                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                    </td>
                                </form>
                            @else
                                <div class="alert alert-info">

                                    <p><i class="fas fa-info-circle me-2"></i>
                                        <b>
                                            Layanan bimbingan akademik belum dibuka atau sudah ditutup.
                                        </b>
                                    </p>
                                </div>
                            @endif


                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <small>
                    <strong>NB:</strong><br>
                    1. Pertemuan konsultasi minimal 4 kali per semester.<br>
                    2. Kartu bimbingan ini dipegang oleh Dosen PA dan Mahasiswa.
                </small>
            </div>
        </div>
    </div>
@endsection
