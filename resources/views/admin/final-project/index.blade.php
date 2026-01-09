@extends('admin.layouts.super-app')

@section('content')
    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #2196F3, #64B5F6);">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <h5>Total Mahasiswa</h5>
                <h3>{{ $stats['total'] }}</h3>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #9C27B0, #BA68C8);">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="stat-info">
                <h5>Proposal</h5>
                <h3>{{ $stats['proposal'] }}</h3>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FF9800, #FFB347);">
                <i class="bi bi-search"></i>
            </div>
            <div class="stat-info">
                <h5>Penelitian</h5>
                <h3>{{ $stats['research'] }}</h3>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #F44336, #FF8A80);">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-info">
                <h5>Sidang</h5>
                <h3>{{ $stats['defense'] }}</h3>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4CAF50, #81C784);">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-info">
                <h5>Selesai</h5>
                <h3>{{ $stats['completed'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Pending Items -->
    <div class="pending-section">
        <h4><i class="bi bi-exclamation-circle"></i> Item Pending Approval</h4>
        <div class="pending-grid">
            <a href="{{ route('admin.final-project.titles.index') }}" class="pending-card">
                <div class="pending-count">{{ $pendingItems['titles'] ?? 0 }}</div>
                <p>Pengajuan Judul</p>
            </a>
            <a href="{{ route('admin.final-project.proposals.index') }}" class="pending-card">
                <div class="pending-count">{{ $pendingItems['proposals'] }}</div>
                <p>Pendaftaran Sempro</p>
            </a>
            <a href="{{ route('admin.final-project.defenses.index') }}" class="pending-card">
                <div class="pending-count">{{ $pendingItems['defenses'] }}</div>
                <p>Pendaftaran Sidang</p>
            </a>
            <a href="{{ route('admin.final-project.guidance.index') }}" class="pending-card">
                <div class="pending-count">{{ $pendingItems['guidance'] }}</div>
                <p>Log Bimbingan</p>
            </a>
            <a href="{{ route('admin.final-project.documents.index') }}" class="pending-card">
                <div class="pending-count">{{ $pendingItems['documents'] }}</div>
                <p>Dokumen</p>
            </a>
        </div>
    </div>

    <!-- Management Links -->
    <div class="content-card" style="margin-top: 20px;">
        <div class="card-header">
            <h3>Pengelolaan</h3>
        </div>
        <div class="management-links">
            <a href="{{ route('admin.final-project.supervisors.index') }}" class="management-link">
                <i class="bi bi-people"></i>
                <span>Pengelolaan Pembimbing</span>
            </a>
        </div>
    </div>
    <br>

    <!-- Students List -->
    <div class="content-card">
        <div class="card-header">
            <h3>Mahasiswa Bimbingan</h3>
            <div class="filters">
                <select onchange="window.location.href='?status='+this.value" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                    <option value="research" {{ request('status') == 'research' ? 'selected' : '' }}>Penelitian</option>
                    <option value="defense" {{ request('status') == 'defense' ? 'selected' : '' }}>Sidang</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
        </div>

        @if($finalProjects->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($finalProjects as $fp)
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <strong>{{ $fp->student->nama_lengkap }}</strong>
                                        <small>{{ $fp->student->nim }}</small>
                                    </div>
                                </td>
                                <td>{{ $fp->title ?? '-' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $fp->status }}">
                                        {{ ucfirst($fp->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress-mini">
                                        <div class="progress-fill" style="width: {{ $fp->progress_percentage }}%"></div>
                                    </div>
                                    <small>{{ $fp->progress_percentage }}%</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.final-project.show', $fp->id) }}" class="btn-view">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $finalProjects->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Belum ada mahasiswa bimbingan</p>
            </div>
        @endif
    </div>
@endsection

@push('css')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }

    .stat-info h5 {
        font-size: 13px;
        color: #666;
        margin: 0 0 5px;
    }

    .stat-info h3 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        color: #333;
    }

    .pending-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: var(--shadow);
        margin-bottom: 30px;
    }

    .pending-section h4 {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 20px;
        color: #FF9800;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pending-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .pending-card {
        background: #FFF3E0;
        border: 2px solid #FFB347;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
    }

    .pending-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(255,152,0,0.3);
    }

    .pending-count {
        font-size: 36px;
        font-weight: 700;
        color: #F57C00;
        margin-bottom: 8px;
    }

    .pending-card p {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

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
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #F5F5F5;
    }

    .card-header h3 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .filter-select {
        padding: 8px 15px;
        border: 2px solid #E0E0E0;
        border-radius: 8px;
        font-size: 14px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: #F5F5F5;
    }

    .data-table th {
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #666;
    }

    .data-table td {
        padding: 15px 12px;
        border-top: 1px solid #F0F0F0;
    }

    .student-info strong {
        display: block;
        font-size: 14px;
        color: #333;
        margin-bottom: 3px;
    }

    .student-info small {
        font-size: 12px;
        color: #999;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.status-proposal { background: #E1BEE7; color: #6A1B9A; }
    .status-badge.status-research { background: #BBDEFB; color: #1565C0; }
    .status-badge.status-defense { background: #FFCCBC; color: #D84315; }
    .status-badge.status-completed { background: #C8E6C9; color: #2E7D32; }

    .progress-mini {
        width: 100%;
        max-width: 150px;
        height: 8px;
        background: #E0E0E0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, var(--primary-orange), #FFB347);
    }

    .btn-view {
        background: #E3F2FD;
        color: #1976D2;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view:hover {
        background: #BBDEFB;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 60px;
        margin-bottom: 15px;
    }

    .management-links {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .management-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 20px;
        background: #F5F5F5;
        border-radius: 10px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .management-link:hover {
        background: #FFF3E0;
        border-color: var(--primary-orange);
        transform: translateY(-2px);
    }

    .management-link i {
        font-size: 20px;
        color: var(--primary-orange);
    }

    .management-link span {
        font-weight: 500;
    }
</style>
@endpush
