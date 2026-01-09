@extends('admin.layouts.super-app')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3>Management Pengumuman</h3>
            <a href="{{ route('admin.announcements.create') }}" class="btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Pengumuman
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="filter-box">
            <form method="GET" action="{{ route('admin.announcements.index') }}" class="filter-form">
                <input type="text" name="search" class="search-input"
                       placeholder="Cari judul atau isi pengumuman..."
                       value="{{ $search }}">
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <button type="submit" class="search-btn">
                    <i class="bi bi-search"></i> Cari
                </button>
            </form>
        </div>

        @if($announcements->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Judul</th>
                            <th class="col-status">Status</th>
                            <th class="col-published">Dipublish</th>
                            <th class="col-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($announcements as $a)
                            <tr>
                                <td>{{ $loop->iteration + ($announcements->currentPage() - 1) * $announcements->perPage() }}</td>
                                <td>
                                    <strong>{{ $a->title }}</strong>
                                    @if(!empty($a->content))
                                        <br>
                                        <small style="color:#888;">
                                            {{ \Illuminate\Support\Str::limit($a->content, 120) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($a->is_published)
                                        <span class="status-badge active">Published</span>
                                    @else
                                        <span class="status-badge inactive">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="color:#666;">
                                        {{ $a->published_at ? $a->published_at->translatedFormat('d M Y H:i') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.announcements.edit', $a->id) }}" class="btn-edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.announcements.toggle-publish', $a->id) }}"
                                              method="POST"
                                              style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-secondary">
                                                <i class="bi {{ $a->is_published ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                {{ $a->is_published ? 'Draft' : 'Publish' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.announcements.destroy', $a->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')"
                                              style="display:inline;">
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
                {{ $announcements->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Tidak ada pengumuman</p>
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

    .btn-secondary {
        background: #EEF2FF;
        color: #3B82F6;
        border: none;
        padding: 8px 12px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-secondary:hover {
        filter: brightness(0.98);
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

    .filter-box {
        margin-bottom: 20px;
        background: #FAFAFA;
        border: 2px solid #F0F0F0;
        border-radius: 14px;
        padding: 16px;
    }

    .filter-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 220px;
        padding: 12px 15px;
        border: 2px solid #E0E0E0;
        border-radius: 10px;
        font-size: 14px;
        background: white;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 4px rgba(255, 152, 0, 0.12);
    }

    .filter-select {
        padding: 12px 15px;
        border: 2px solid #E0E0E0;
        border-radius: 10px;
        font-size: 14px;
        min-width: 170px;
        background: white;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 4px rgba(255, 152, 0, 0.12);
    }

    .search-btn {
        padding: 12px 20px;
        background: var(--primary-orange);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(255, 152, 0, 0.35);
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 10px;
        overflow: hidden;
        border-radius: 14px;
        border: 1px solid #F0F0F0;
        background: white;
    }

    .data-table thead {
        background: linear-gradient(135deg, #FFF3E0, #FFFBF0);
    }

    .data-table th {
        padding: 14px 12px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #6B7280;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        border-bottom: 1px solid #F0F0F0;
        white-space: nowrap;
    }

    .data-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #F3F4F6;
        vertical-align: top;
        color: #333;
        font-size: 14px;
    }

    .data-table tbody tr:hover {
        background: #FFFBF0;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .col-no { width: 70px; white-space: nowrap; }
    .col-status { width: 140px; white-space: nowrap; }
    .col-published { width: 210px; white-space: nowrap; }
    .col-actions { width: 320px; }

    /* Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }

    .status-badge.active {
        background: rgba(76, 175, 80, 0.12);
        color: #2E7D32;
        border: 1px solid rgba(76, 175, 80, 0.25);
    }

    .status-badge.inactive {
        background: rgba(148, 163, 184, 0.18);
        color: #475569;
        border: 1px solid rgba(148, 163, 184, 0.35);
    }

    /* Buttons */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .btn-edit,
    .btn-secondary,
    .btn-delete {
        border: none;
        border-radius: 12px;
        padding: 9px 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        white-space: nowrap;
    }

    .btn-edit {
        background: rgba(59, 130, 246, 0.12);
        color: #1D4ED8;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(59, 130, 246, 0.25);
    }

    .btn-secondary {
        background: rgba(99, 102, 241, 0.12);
        color: #4338CA;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.22);
    }

    .btn-delete {
        background: rgba(244, 67, 54, 0.12);
        color: #C62828;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(244, 67, 54, 0.22);
    }

    .empty-state {
        text-align: center;
        padding: 70px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        color: #E0E0E0;
        margin-bottom: 14px;
        display: inline-block;
    }

    .pagination-wrapper {
        margin-top: 18px;
    }

    @media (max-width: 768px) {
        .content-card { padding: 18px; }
        .filter-form { gap: 8px; }
        .filter-box { padding: 12px; }
        .col-actions { width: 260px; }
        .action-buttons { gap: 8px; }
        .btn-edit, .btn-secondary, .btn-delete { width: 100%; justify-content: center; }
    }
</style>
@endpush


