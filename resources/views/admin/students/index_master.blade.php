@extends('admin.template.index')

@push('css')
    <style>
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-primary {
            background-color: #4361ee;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
        }

        .btn-danger {
            background-color: #e63946;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c1121f;
        }

        .text-primary {
            color: #4361ee !important;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
            border-radius: 12px;
        }

        .badge-success {
            background-color: #4cc9f0;
            color: #0a0a0a;
        }

        .table thead th {
            background-color: #4361ee;
            color: white;
            font-weight: 600;
            text-align: center;
        }

        .table tbody td {
            vertical-align: middle;
            text-align: center;
            font-size: 0.9rem;
        }

        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: #e3f2fd !important;
        }

        .filter-container {
            background: #f1f3f5;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }

        .filter-container h6 {
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
        }

        .trix-content {
            min-height: 70px;
            font-size: 0.9rem;
        }

        .trix-editor {
            min-height: 80px !important;
        }

        /* Animasi pulse untuk ikon warning */
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Supaya modal animasi fadeIn dan fadeOut */
        .modal.animate__animated .modal-content {
            animation-duration: 0.4s;
        }

        .modal.fade .modal-dialog {
            transform: translate(0, -20px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Anda sedang melihat daftar mahasiswa yang dibimbing oleh <b>{{ $dosen->name }}</b>,
            khusus untuk angkatan <b>{{ $batch }}</b>.
        </div>


        <div class="d-flex justify-content-between align-items-center mb-4">

            <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-lg shadow">
                <i class="fas fa-plus-circle me-2"></i> Add New Student
            </a>
        </div>
        @if (request()->is('admin/counseling/get-students/*'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Anda sedang melihat data mahasiswa berdasarkan angkatan {{ $batch }}. Klik tombol "Add New Student"
                untuk menambahkan
                mahasiswa baru.
            </div>
            <div class="filter-container mb-3">
                <form method="GET" action="{{ route('admin.counseling.getStudentsByBatch', $batch) }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Cari nama, NIM, atau angkatan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
            </div>
        @else
            <div class="filter-container mb-3">
                <form method="GET" action="{{ route('admin.students.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Cari nama, NIM, atau angkatan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
            </div>
        @endif


        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Lengkap</th>
                                <th>NIM</th>
                                <th>Angkatan</th>
                                <th>Jurusan</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Notes</th>
                                <th>Counseling</th>
                                <th>Aksi</th>
                                @if (request()->is('admin/counseling/get-students/*'))
                                    <th>Counseling</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                                    <td><strong>{{ Str::limit($student->nama_lengkap, 40) }}</strong></td>
                                    <td class="text-muted font-monospace">{{ $student->nim }}</td>
                                    <td><span class="badge badge-success">{{ $student->angkatan }}</span></td>
                                    <td>{{ $student->program_studi }}</td>
                                    <td>{{ $student->email ?? 'belum ada' }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $student->jenis_kelamin === 'L' ? 'bg-primary text-white' : 'bg-danger  text-white' }}">
                                            {{ $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($student->notes)
                                            <div class="trix-content" style="white-space: pre-wrap;">
                                                {!! $student->notes !!}
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada catatan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if ($student->is_counseling == 0)
                                                <a href="{{ route('admin.counseling.openclose', $student->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus-circle me-1"></i> Open
                                                </a>
                                                <a href="{{ route('admin.students.showCardByLecture', $student->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-alt me-1"></i> Check
                                                </a>
                                            @else
                                                <a href="{{ route('admin.counseling.openclose', $student->id) }}"
                                                    class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-minus-circle me-1"></i> Closed
                                                </a>
                                                <a href="{{ route('admin.students.showCardByLecture', $student->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-alt me-1"></i> Check
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $student->id }}"
                                            data-name="{{ $student->nama_lengkap }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                        <p class="mb-0">Tidak ada data mahasiswa.</p>
                                        <small class="text-muted">Silakan tambahkan data baru.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $students->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content animate__animated">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-exclamation-circle text-danger mb-3"
                            style="font-size: 4rem; animation: pulse 1.2s infinite;"></i>

                        <p class="mb-1">Apakah Anda yakin ingin menghapus <br>
                            <strong id="studentName"></strong>?
                        </p>
                        <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const studentId = button.getAttribute('data-id');
            const studentName = button.getAttribute('data-name');

            // Set nama mahasiswa di modal
            document.getElementById('studentName').textContent = studentName;

            // Set action form
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('admin/students') }}/" + studentId;

            // Tambahkan animasi masuk
            const modalContent = deleteModal.querySelector('.modal-content');
            modalContent.classList.remove('animate__fadeOutDown');
            modalContent.classList.add('animate__fadeInUp');
        });

        deleteModal.addEventListener('hide.bs.modal', function() {
            const modalContent = deleteModal.querySelector('.modal-content');
            modalContent.classList.remove('animate__fadeInUp');
            modalContent.classList.add('animate__fadeOutDown');
        });
    </script>
@endpush
