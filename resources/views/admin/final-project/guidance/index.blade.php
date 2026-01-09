@extends('admin.layouts.super-app')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3>Review Log Bimbingan</h3>
            <div class="filters">
                <select onchange="window.location.href='?status='+this.value" class="filter-select">
                    <option value="">Pending Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($logs->count() > 0)
            @foreach($logs as $log)
                <div class="review-card status-{{ $log->status }}">
                    <div class="review-header">
                        <div class="student-info">
                            <h4>{{ $log->finalProject->student->nama_lengkap }}</h4>
                            <p class="meta">
                                <span><i class="bi bi-card-text"></i> {{ $log->finalProject->student->nim }}</span>
                                <span><i class="bi bi-calendar3"></i> {{ $log->guidance_date->format('d M Y') }}</span>
                            </p>
                        </div>
                        @if($log->status === 'pending')
                            <div class="action-buttons">
                                <button type="button" class="btn-approve" onclick="showApproveModal({{ $log->id }})">
                                    <i class="bi bi-check-circle"></i> ACC
                                </button>
                                <button type="button" class="btn-reject" onclick="showRejectModal({{ $log->id }})">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </div>
                        @else
                            <span class="status-badge status-{{ $log->status }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        @endif
                    </div>

                    <div class="review-content">
                        <div class="content-section">
                            <h5><i class="bi bi-book"></i> Materi yang Dibimbing:</h5>
                            <p>{{ $log->materials_discussed }}</p>
                        </div>

                        @if($log->student_notes)
                            <div class="content-section">
                                <h5><i class="bi bi-pencil"></i> Catatan Mahasiswa:</h5>
                                <p>{{ $log->student_notes }}</p>
                            </div>
                        @endif

                        @if($log->file_path)
                            <div class="content-section">
                                <a href="{{ route('admin.final-project.guidance.download', $log->id) }}" class="file-download">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <span>Download File Lampiran</span>
                                </a>
                            </div>
                        @endif

                        @if($log->supervisor_feedback)
                            <div class="content-section feedback-section">
                                <h5><i class="bi bi-chat-left-text"></i> Feedback Anda:</h5>
                                <p>{{ $log->supervisor_feedback }}</p>
                                <small>Diberikan pada {{ $log->approved_at?->format('d M Y H:i') ?? '-' }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="pagination-wrapper">
                {{ $logs->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Tidak ada log bimbingan</h4>
                <p>Semua log bimbingan sudah direview</p>
            </div>
        @endif
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h4><i class="bi bi-check-circle"></i> ACC Log Bimbingan</h4>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>Feedback (Opsional)</label>
                    <textarea name="supervisor_feedback" class="form-control" rows="3" placeholder="Berikan feedback atau catatan untuk mahasiswa..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-approve-confirm">
                        <i class="bi bi-check-circle"></i> ACC Bimbingan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h4><i class="bi bi-x-circle"></i> Tolak Log Bimbingan</h4>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>Alasan Penolakan *</label>
                    <textarea name="supervisor_feedback" class="form-control" rows="4" required placeholder="Berikan alasan mengapa log bimbingan ditolak..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-reject-confirm">
                        <i class="bi bi-x-circle"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
<style>
    .content-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
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
        color: #333;
    }

    .filter-select {
        padding: 8px 15px;
        border: 2px solid #E0E0E0;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        background: white;
    }

    .alert-success {
        background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
        color: #2E7D32;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .review-card {
        background: #FAFAFA;
        border: 2px solid #E0E0E0;
        border-left: 5px solid #E0E0E0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .review-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .review-card.status-approved {
        border-left-color: #4CAF50;
        background: linear-gradient(135deg, #F1F8F4, #FFFFFF);
    }

    .review-card.status-pending {
        border-left-color: #FF9800;
        background: linear-gradient(135deg, #FFF8F0, #FFFFFF);
    }

    .review-card.status-rejected {
        border-left-color: #F44336;
        background: linear-gradient(135deg, #FFEBEE, #FFFFFF);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px dashed #E0E0E0;
    }

    .student-info h4 {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 8px;
        color: #333;
    }

    .meta {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #666;
        margin: 0;
    }

    .meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-approve, .btn-reject, .btn-cancel, .btn-approve-confirm, .btn-reject-confirm {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-approve, .btn-approve-confirm {
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
        color: white;
        box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
    }

    .btn-approve:hover, .btn-approve-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(76, 175, 80, 0.4);
    }

    .btn-reject, .btn-reject-confirm {
        background: linear-gradient(135deg, #F44336, #EF5350);
        color: white;
        box-shadow: 0 4px 10px rgba(244, 67, 54, 0.3);
    }

    .btn-reject:hover, .btn-reject-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(244, 67, 54, 0.4);
    }

    .btn-cancel {
        background: #E0E0E0;
        color: #666;
    }

    .btn-cancel:hover {
        background: #D0D0D0;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-badge.status-approved {
        background: #4CAF50;
        color: white;
    }

    .status-badge.status-rejected {
        background: #F44336;
        color: white;
    }

    .review-content {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .content-section {
        background: white;
        padding: 15px;
        border-radius: 10px;
    }

    .content-section h5 {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        margin: 0 0 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .content-section p {
        font-size: 14px;
        color: #333;
        margin: 0;
        line-height: 1.6;
    }

    .feedback-section {
        background: linear-gradient(135deg, #E3F2FD, #FFFFFF);
        border-left: 4px solid #2196F3;
    }

    .feedback-section small {
        display: block;
        margin-top: 8px;
        font-size: 12px;
        color: #999;
    }

    .file-download {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #2196F3, #42A5F5);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(33, 150, 243, 0.3);
    }

    .file-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(33, 150, 243, 0.4);
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

    .empty-state h4 {
        font-size: 20px;
        font-weight: 600;
        color: #666;
        margin: 0 0 10px;
    }

    .empty-state p {
        color: #999;
        margin: 0;
    }

    /* Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        animation: modalSlideUp 0.3s ease;
    }

    @keyframes modalSlideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        margin-bottom: 20px;
    }

    .modal-header h4 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #E0E0E0;
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #2196F3;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
</style>
@endpush

@push('scripts')
<script>
function showApproveModal(logId) {
    const modal = document.getElementById('approveModal');
    const form = document.getElementById('approveForm');
    form.action = `/admin/final-project/guidance/${logId}/approve`;
    modal.style.display = 'flex';
}

function showRejectModal(logId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/admin/final-project/guidance/${logId}/reject`;
    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('approveModal').style.display = 'none';
    document.getElementById('rejectModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');
    if (event.target === approveModal) {
        closeModal();
    }
    if (event.target === rejectModal) {
        closeModal();
    }
}
</script>
@endpush
