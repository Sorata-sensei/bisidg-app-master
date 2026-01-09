@extends('students.layouts.super-app')

@section('content')
    <div class="stats-card">
        <div class="stats-header">
            <h3>Status Pendaftaran Seminar Proposal</h3>
        </div>
        <p style="margin-top: 10px; color: #666; font-size: 14px;">
            Berikut status pendaftaran seminar proposal Tugas Akhir Anda.
        </p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger">
            <i class="bi bi-x-circle"></i> {{ session('error') }}
        </div>
    @endif

    @php
        $docs = $proposal->finalProject?->documents
            ? $proposal->finalProject->documents->where('document_type', 'proposal')->values()
            : collect();
    @endphp

    <div class="form-card">
        <h4>Informasi Sempro</h4>

        <div class="info-row">
            <div class="label">Status</div>
            <div class="value">
                <span class="status-badge {{ $proposal->status === 'approved' ? 'active' : ($proposal->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($proposal->status) }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="label">Tanggal Daftar</div>
            <div class="value">{{ $proposal->registered_at?->translatedFormat('d M Y H:i') ?? '-' }}</div>
        </div>

        <div class="info-row">
            <div class="label">Jadwal Sempro</div>
            <div class="value">
                {{ $proposal->scheduled_at?->translatedFormat('d M Y H:i') ?? 'Belum dijadwalkan oleh Kaprodi' }}
                @if($proposal->scheduled_at)
                    <div class="muted">Jadwal ini juga akan muncul di menu Calendar.</div>
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="label">Disetujui Oleh</div>
            <div class="value">{{ $proposal->approver?->name ?? '-' }}</div>
        </div>

        @if($proposal->approval_notes)
            <div class="info-row">
                <div class="label">Catatan</div>
                <div class="value">{{ $proposal->approval_notes }}</div>
            </div>
        @endif
    </div>

    <div class="form-card">
        <h4>Dokumen Sempro</h4>
        @if($docs->count() > 0)
            <div class="doc-list">
                @foreach($docs as $d)
                    <div class="doc-item">
                        <div class="doc-left">
                            <div class="doc-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div class="doc-meta">
                                <div class="doc-title">{{ $d->title }}</div>
                                <div class="doc-sub">
                                    Upload: {{ $d->uploaded_at?->translatedFormat('d M Y H:i') ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <a class="doc-link" href="{{ asset('storage/' . ltrim($d->file_path, '/')) }}" target="_blank" rel="noopener">
                            Lihat
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="muted">Belum ada dokumen yang tersimpan.</div>
        @endif
    </div>

    <div class="form-actions">
        <a href="{{ route('student.final-project.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('calendar.index') }}" class="btn-primary-soft">Buka Calendar</a>
    </div>
@endsection

@push('css')
<style>
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }

    .stats-header h3 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 15px 20px;
        border-radius: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-danger {
        background: #FFEBEE;
        color: #C62828;
        padding: 15px 20px;
        border-radius: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }

    .form-card h4 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 16px;
        color: var(--primary-orange);
    }

    .info-row {
        display: grid;
        grid-template-columns: 160px 1fr;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #F0F0F0;
    }

    .info-row:last-child { border-bottom: none; }

    .label {
        color: #666;
        font-weight: 700;
        font-size: 13px;
    }

    .value {
        color: #333;
        font-size: 14px;
    }

    .muted {
        color: #777;
        font-size: 12px;
        font-weight: 600;
        margin-top: 6px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }
    .status-badge.active { background: #E8F5E9; color: #2E7D32; }
    .status-badge.warning { background: #FFF3E0; color: #E65100; }
    .status-badge.danger { background: #FFEBEE; color: #C62828; }

    .doc-list { display: flex; flex-direction: column; gap: 12px; }
    .doc-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.06);
        background: #fff;
    }
    .doc-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .doc-icon {
        width: 42px; height: 42px; border-radius: 14px;
        background: rgba(255,152,0,0.14);
        display: flex; align-items: center; justify-content: center;
        color: var(--primary-orange);
        font-size: 18px;
        flex: 0 0 auto;
    }
    .doc-meta { min-width: 0; }
    .doc-title { font-weight: 900; color: #333; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 520px; }
    .doc-sub { color: #777; font-size: 12px; font-weight: 600; margin-top: 4px; }
    .doc-link { color: var(--primary-orange); font-weight: 900; text-decoration: none; }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .btn-secondary {
        background: #E0E0E0;
        color: #666;
        padding: 12px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
    }

    .btn-primary-soft {
        background: linear-gradient(135deg, var(--primary-orange), #FFB347);
        color: white;
        padding: 12px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 900;
        box-shadow: 0 10px 26px rgba(255,152,0,0.22);
    }

    @media (max-width: 768px) {
        .info-row { grid-template-columns: 1fr; }
        .doc-title { max-width: 220px; }
    }
</style>
@endpush

