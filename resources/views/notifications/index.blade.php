@php
    $layout = 'landing.layout';
    if (auth()->check()) {
        $layout = 'admin.layouts.super-app';
    } elseif (session()->has('student_id')) {
        $layout = 'students.layouts.super-app';
    }
@endphp

@extends($layout)

@section('content')
    <div class="content-card">
        <div class="card-header">
            <div>
                <h3>Notification</h3>
                <p class="subtext">Notifikasi sistem untuk pengajuan & approval.</p>
            </div>
            <div class="actions">
                <span class="count">{{ $unreadCount }} belum dibaca</span>
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button class="btn-action" type="submit">
                        Tandai semua dibaca
                    </button>
                </form>
            </div>
        </div>

        @if($notifications->count() > 0)
            <div class="list">
                @foreach($notifications as $n)
                    <div class="item {{ $n->read_at ? '' : 'unread' }}">
                        <div class="meta">
                            <div class="title">
                                @if(!$n->read_at)
                                    <span class="dot"></span>
                                @endif
                                {{ $n->title }}
                            </div>
                            <div class="time">
                                {{ optional($n->created_at)->translatedFormat('d M Y H:i') }}
                                @if($n->type)
                                    <span class="chip">{{ $n->type }}</span>
                                @endif
                            </div>
                        </div>
                        @if($n->body)
                            <div class="body">{{ $n->body }}</div>
                        @endif
                        <div class="row-actions">
                            @if($n->url)
                                <a class="btn-link" href="{{ $n->url }}">Buka</a>
                            @endif
                            @if(!$n->read_at)
                                <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                    @csrf
                                    <button class="btn-read" type="submit">Sudah dibaca</button>
                                </form>
                            @else
                                <span class="read-label">Sudah dibaca</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Belum ada notifikasi.</p>
            </div>
        @endif
    </div>
@endsection

@push('css')
<style>
    .card-header {
        display:flex; align-items:center; justify-content:space-between; gap: 14px;
        margin-bottom: 16px; padding-bottom: 14px;
        border-bottom: 2px solid rgba(0,0,0,0.06);
    }
    .subtext { margin: 6px 0 0; color:#777; font-size: 13px; font-weight: 500; }
    .actions { display:flex; align-items:center; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
    .count { background: rgba(33,150,243,0.12); color:#1565C0; border:1px solid rgba(33,150,243,0.2); padding:6px 10px; border-radius: 999px; font-weight: 800; font-size: 12px; }
    .btn-action { background: white; border: 1px solid rgba(255, 152, 0, 0.25); padding: 10px 12px; border-radius: 12px; font-weight: 800; color: var(--primary-orange); box-shadow: var(--shadow); }
    .btn-action:hover { filter: brightness(0.98); }

    .list { display:flex; flex-direction: column; gap: 12px; }
    .item { background: white; border-radius: 18px; padding: 16px; box-shadow: var(--shadow); border:1px solid rgba(0,0,0,0.06); }
    .item.unread { border-color: rgba(255, 152, 0, 0.25); }
    .meta { display:flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
    .title { font-weight: 900; color:#333; display:flex; align-items:center; gap: 10px; }
    .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--primary-orange); box-shadow: 0 0 0 4px rgba(255,152,0,0.18); }
    .time { color:#777; font-size: 12px; font-weight: 700; display:flex; align-items:center; gap: 8px; flex-wrap: wrap; justify-content: flex-end; text-align: right; }
    .chip { background: rgba(156, 39, 176, 0.12); color: #6A1B9A; border: 1px solid rgba(156, 39, 176, 0.18); padding: 3px 8px; border-radius: 999px; font-weight: 900; font-size: 11px; }
    .body { margin-top: 10px; color:#444; font-size: 13px; line-height: 1.6; white-space: pre-wrap; }
    .row-actions { margin-top: 12px; display:flex; align-items:center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
    .btn-link { color: var(--primary-orange); font-weight: 900; text-decoration: none; }
    .btn-read { background: linear-gradient(135deg, var(--primary-orange), #FFB347); border: none; color: white; padding: 10px 14px; border-radius: 12px; font-weight: 900; box-shadow: 0 8px 22px rgba(255,152,0,0.25); }
    .btn-read:hover { filter: brightness(1.02); }
    .read-label { color: #2E7D32; font-weight: 900; font-size: 12px; background: #E8F5E9; padding: 6px 10px; border-radius: 999px; }
    .pagination-wrapper { margin-top: 14px; }
    .empty-state { text-align:center; padding: 50px 10px; color:#999; }
    .empty-state i { font-size: 56px; color:#E0E0E0; margin-bottom: 12px; }
    @media(max-width: 768px) {
        .meta { flex-direction: column; align-items: flex-start; }
        .time { justify-content: flex-start; text-align: left; }
    }
</style>
@endpush

