@extends('admin.layouts.super-app')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3>Management Mahasiswa</h3>
            <a href="{{ route('admin.management.students.create') }}" class="btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Search -->
        <div class="search-box">
            <form method="GET" action="{{ route('admin.management.students.index') }}">
                <input type="text" name="search" class="search-input" 
                       placeholder="Cari nama, NIM, email, atau angkatan..." 
                       value="{{ $search }}">
                <button type="submit" class="search-btn">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Angkatan</th>
                            <th>Dosen PA</th>
                            <th>Status</th>
                            <th>Counseling</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                                <td>
                                    <strong>{{ $student->nama_lengkap }}</strong>
                                </td>
                                <td class="font-monospace">{{ $student->nim }}</td>
                                <td>
                                    <span class="badge-year">{{ $student->angkatan }}</span>
                                </td>
                                <td>
                                    {{ $student->dosenPA->name ?? '-' }}
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($student->status_mahasiswa) }}">
                                        {{ $student->status_mahasiswa }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-count">{{ $student->counselings_count }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.management.students.show', $student->id) }}" class="btn-view">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('admin.management.students.edit', $student->id) }}" class="btn-edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.management.students.destroy', $student->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?')"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $students->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Tidak ada data mahasiswa</p>
            </div>
        @endif
    </div>
@endsection

@push('css')
<style>
    .content-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: var(--shadow);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #F5F5F5;
    }

    .card-header h3 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-orange), #FFB347);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 112, 67, 0.4);
    }

    .alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-box {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .search-input {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid #E0E0E0;
        border-radius: 10px;
        font-size: 14px;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-orange);
    }

    .search-btn {
        padding: 12px 20px;
        background: var(--primary-orange);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .data-table th {
        padding: 12px;
        text-align: left;
        background: #F5F5F5;
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .data-table td {
        padding: 15px 12px;
        border-bottom: 1px solid #E0E0E0;
    }

    .data-table tr:hover {
        background: #F9F9F9;
    }

    .font-monospace {
        font-family: 'Courier New', monospace;
        color: #666;
    }

    .badge-year {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.status-aktif {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .status-badge.status-cuti {
        background: #FFF3E0;
        color: #F57C00;
    }

    .status-badge.status-lulus {
        background: #E3F2FD;
        color: #1976D2;
    }

    .badge-count {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-view {
        padding: 6px 12px;
        background: #2196F3;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-view:hover {
        background: #1976D2;
        transform: translateY(-2px);
    }

    .btn-edit {
        padding: 6px 12px;
        background: var(--primary-orange);
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-edit:hover {
        background: #FF7043;
        transform: translateY(-2px);
    }

    .btn-delete {
        padding: 6px 12px;
        background: #F44336;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-delete:hover {
        background: #E53935;
        transform: translateY(-2px);
    }

    .pagination-wrapper {
        margin-top: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 80px;
        color: #E0E0E0;
        margin-bottom: 20px;
    }

    .empty-state p {
        color: #999;
        font-size: 16px;
    }
</style>
@endpush

