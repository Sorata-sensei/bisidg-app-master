@extends('students.layouts.super-app')

@section('content')
    <div class="stats-card">
        <div class="stats-header">
            <h3>Status Pendaftaran Sidang TA</h3>
        </div>
        <p style="margin-top: 10px; color: #666; font-size: 14px;">
            Berikut status pendaftaran sidang Tugas Akhir Anda.
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

    <div class="form-card">
        <h4>Informasi Sidang</h4>

        <div class="info-row">
            <div class="label">Status</div>
            <div class="value">
                <span class="status-badge {{ $defense->status === 'approved' ? 'active' : ($defense->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($defense->status) }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="label">Tanggal Daftar</div>
            <div class="value">{{ $defense->registered_at?->translatedFormat('d M Y H:i') ?? '-' }}</div>
        </div>

        <div class="info-row">
            <div class="label">Jadwal Sidang</div>
            <div class="value">
                {{ $defense->scheduled_at?->translatedFormat('d M Y H:i') ?? 'Belum dijadwalkan oleh Kaprodi' }}
            </div>
        </div>

        @if($defense->approval_notes)
            <div class="info-row">
                <div class="label">Catatan</div>
                <div class="value">{{ $defense->approval_notes }}</div>
            </div>
        @endif
    </div>

    <div class="form-actions">
        <a href="{{ route('student.final-project.index') }}" class="btn-secondary">Kembali</a>
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

    .info-row:last-child {
        border-bottom: none;
    }

    .label {
        color: #666;
        font-weight: 700;
        font-size: 13px;
    }

    .value {
        color: #333;
        font-size: 14px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-badge.active {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .status-badge.warning {
        background: #FFF3E0;
        color: #E65100;
    }

    .status-badge.danger {
        background: #FFEBEE;
        color: #C62828;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }

    .btn-secondary {
        background: #E0E0E0;
        color: #666;
        padding: 12px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .info-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

