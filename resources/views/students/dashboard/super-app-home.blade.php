@extends('students.layouts.super-app')

@section('content')
    <!-- Upcoming Schedule Reminder -->
    @if(($upcomingSchedules ?? collect())->isNotEmpty())
        @foreach($upcomingSchedules as $schedule)
            <div class="schedule-reminder {{ $schedule['type'] === 'Sidang' ? 'reminder-danger' : 'reminder-purple' }}">
                <div class="reminder-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="reminder-content">
                    <div class="reminder-header">
                        <h4>{{ $schedule['type_label'] }} Mendatang</h4>
                        <span class="reminder-badge">{{ $schedule['type'] }}</span>
                    </div>
                    <div class="reminder-details">
                        <div class="reminder-time">
                            <i class="bi bi-clock"></i>
                            <strong>{{ $schedule['datetime']->translatedFormat('l, d F Y') }}</strong>
                            <span>pukul {{ $schedule['datetime']->translatedFormat('H:i') }} WIB</span>
                        </div>
                        @if(!empty($schedule['title']))
                            <div class="reminder-title">
                                <i class="bi bi-file-text"></i>
                                <span>{{ $schedule['title'] }}</span>
                            </div>
                        @endif
                        @if(!empty($schedule['approval_notes']))
                            <div class="reminder-notes">
                                <i class="bi bi-chat-left-text"></i>
                                <span><strong>Catatan Kaprodi:</strong> {{ $schedule['approval_notes'] }}</span>
                            </div>
                        @endif
                    </div>
                    <a href="{{ $schedule['url'] }}" class="reminder-link">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Quick Stats Card -->
    <div class="stats-card">
        <div class="stats-header">
            <h3>Status Akademik</h3>
            <span class="semester-badge">{{ $student->angkatan ?? 'N/A' }}</span>
        </div>
        <div class="stats-content">
            <div class="stat-item">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FF9800, #FFB347);">
                    <i class="bi bi-book"></i>
                </div>
                <div class="stat-info">
                    <h5>IPK</h5>
                    <p>{{ $student->ipk ?? '0.00' }}</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background: linear-gradient(135deg, #5B9BD5, #7DB8E8);">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div class="stat-info">
                    <h5>SKS</h5>
                    <p>{{ $student->sks ?? '0' }}</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FFC107, #FFD54F);">
                    <i class="bi bi-trophy"></i>
                </div>
                <div class="stat-info">
                    <h5>Prestasi</h5>
                    <p>{{ $student->achievements_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Menu Section -->
    <div class="menu-section">
        <div class="section-header">
            <h3>Layanan Akademik</h3>
            <a href="#" class="view-all">Lihat Semua</a>
        </div>
        
        <div class="menu-grid">
            @forelse($menus ?? [] as $menu)
                @php
                    $iconColors = [
                        'bi-person-video3' => 'linear-gradient(135deg, #FF9800, #FFB347)',
                        'bi-mortarboard' => 'linear-gradient(135deg, #FF5252, #FF8A80)',
                        'bi-building' => 'linear-gradient(135deg, #FFC107, #FFD54F)',
                        'bi-person-badge' => 'linear-gradient(135deg, #5B9BD5, #7DB8E8)',
                        'bi-book-half' => 'linear-gradient(135deg, #4CAF50, #81C784)',
                        'bi-stars' => 'linear-gradient(135deg, #9C27B0, #BA68C8)',
                    ];
                    $defaultColor = 'linear-gradient(135deg, #2196F3, #64B5F6)';
                    $iconColor = $iconColors[$menu->icon] ?? $defaultColor;
                    
                    $badgeClass = match($menu->badge_color) {
                        'active' => 'active',
                        'warning' => 'warning',
                        'info' => 'info',
                        'pending' => 'pending',
                        default => 'active'
                    };
                @endphp
                <a href="{{ $menu->menu_url }}" 
                   target="{{ $menu->target ?? '_self' }}"
                   class="menu-card">
                    <div class="menu-icon" style="background: {{ $iconColor }};">
                        @if($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @else
                            <i class="bi bi-circle"></i>
                        @endif
                    </div>
                    <h5>{{ $menu->name }}</h5>
                    @if($menu->description)
                        <p>{{ $menu->description }}</p>
                    @endif
                    @if($menu->badge_text)
                        <span class="status-badge {{ $badgeClass }}">{{ $menu->badge_text }}</span>
                    @else
                        <span class="status-badge active">Aktif</span>
                    @endif
                </a>
            @empty
                <!-- Fallback menu jika belum ada menu di database -->
                <a href="{{ route('student.counseling.show') }}" class="menu-card">
                    <div class="menu-icon" style="background: linear-gradient(135deg, #FF9800, #FFB347);">
                        <i class="bi bi-person-video3"></i>
                    </div>
                    <h5>Bimbingan PA</h5>
                    <p>Konsultasi dengan dosen pembimbing akademik</p>
                    <span class="status-badge active">Aktif</span>
                </a>
            @endforelse
        </div>
    </div>

    <!-- Announcement Section -->
    <div class="announcement-section">
        <div class="section-header">
            <h3>Pengumuman Terbaru</h3>
            <a href="{{ route('announcements.index') }}" class="view-all">Lihat Semua</a>
        </div>
        
        <div class="announcement-list">
            @forelse(($announcements ?? collect()) as $a)
                <a class="announcement-item" href="{{ route('announcements.show', $a->id) }}" style="text-decoration: none;">
                    <div class="announcement-icon">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div class="announcement-content">
                        <h5>{{ $a->title }}</h5>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($a->content ?? ''), 90) }}</p>
                        <span class="time">
                            <i class="bi bi-clock"></i>
                            {{ ($a->published_at ?? $a->updated_at ?? $a->created_at)?->diffForHumans() ?? '' }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="announcement-item" style="cursor: default;">
                    <div class="announcement-icon">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div class="announcement-content">
                        <h5>Belum ada pengumuman</h5>
                        <p>Pengumuman terbaru akan tampil di sini setelah dipublish.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('css')
<style>
    /* Schedule Reminder */
    .schedule-reminder {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--shadow);
        margin-bottom: 25px;
        display: flex;
        gap: 20px;
        border-left: 5px solid;
        animation: slideIn 0.5s ease-out;
        position: relative;
        overflow: hidden;
    }

    .schedule-reminder::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .reminder-purple {
        border-left-color: #6A1B9A;
        background: linear-gradient(135deg, rgba(156, 39, 176, 0.05), rgba(156, 39, 176, 0.02));
    }

    .reminder-danger {
        border-left-color: #C62828;
        background: linear-gradient(135deg, rgba(244, 67, 54, 0.05), rgba(244, 67, 54, 0.02));
    }

    .reminder-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .reminder-purple .reminder-icon {
        background: linear-gradient(135deg, #6A1B9A, #9C27B0);
    }

    .reminder-danger .reminder-icon {
        background: linear-gradient(135deg, #C62828, #E53935);
    }

    .reminder-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .reminder-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .reminder-header h4 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .reminder-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .reminder-purple .reminder-badge {
        background: rgba(156, 39, 176, 0.15);
        color: #6A1B9A;
        border: 1px solid rgba(156, 39, 176, 0.2);
    }

    .reminder-danger .reminder-badge {
        background: rgba(244, 67, 54, 0.15);
        color: #C62828;
        border: 1px solid rgba(244, 67, 54, 0.2);
    }

    .reminder-details {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .reminder-time,
    .reminder-title,
    .reminder-notes {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14px;
        color: #555;
        line-height: 1.5;
    }

    .reminder-time i,
    .reminder-title i,
    .reminder-notes i {
        color: var(--primary-orange);
        font-size: 16px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .reminder-time strong {
        color: var(--text-dark);
        font-weight: 700;
        margin-right: 8px;
    }

    .reminder-time span {
        color: #777;
    }

    .reminder-title span {
        font-weight: 600;
        color: #444;
    }

    .reminder-notes {
        padding: 10px;
        background: rgba(0, 0, 0, 0.03);
        border-radius: 10px;
        border-left: 3px solid var(--primary-orange);
    }

    .reminder-notes span {
        color: #666;
    }

    .reminder-notes strong {
        color: #444;
        font-weight: 700;
    }

    .reminder-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--primary-orange), #FFB347);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        transition: var(--transition-normal);
        align-self: flex-start;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .reminder-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
        color: white;
    }

    .reminder-link i {
        transition: transform 0.3s;
    }

    .reminder-link:hover i {
        transform: translateX(4px);
    }

    /* Stats Card */
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--shadow);
        margin-bottom: 25px;
        transition: var(--transition-normal);
    }

    .stats-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-5px);
    }

    .stats-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .stats-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }

    .semester-badge {
        background: linear-gradient(135deg, var(--primary-orange), #FFB347);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .stats-content {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 28px;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .stat-info h5 {
        font-size: 12px;
        color: var(--text-gray);
        margin: 0 0 5px;
    }

    .stat-info p {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    /* Menu Section */
    .menu-section {
        margin-bottom: 30px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-header h3 {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }

    .view-all {
        color: var(--primary-orange);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: var(--transition-normal);
    }

    .view-all:hover {
        color: #FF7043;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .menu-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition-normal);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
    }

    .menu-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-8px);
    }

    .menu-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 35px;
        color: white;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transition: var(--transition-normal);
    }

    .menu-card:hover .menu-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .menu-card h5 {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .menu-card p {
        font-size: 12px;
        color: var(--text-gray);
        margin-bottom: 12px;
        line-height: 1.4;
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-badge.active {
        background: #E8F5E9;
        color: #4CAF50;
    }

    .status-badge.warning {
        background: #FFF3E0;
        color: #FF9800;
    }

    .status-badge.pending {
        background: #E3F2FD;
        color: #2196F3;
    }

    .status-badge.info {
        background: #F3E5F5;
        color: #9C27B0;
    }

    /* Announcement Section */
    .announcement-section {
        margin-bottom: 30px;
    }

    .announcement-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .announcement-item {
        background: white;
        border-radius: 15px;
        padding: 15px;
        box-shadow: var(--shadow);
        display: flex;
        gap: 15px;
        transition: var(--transition-normal);
        cursor: pointer;
    }

    .announcement-item:hover {
        box-shadow: var(--shadow-hover);
        transform: translateX(5px);
    }

    .announcement-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary-orange), #FFB347);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .announcement-content {
        flex: 1;
    }

    .announcement-content h5 {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 5px;
    }

    .announcement-content p {
        font-size: 12px;
        color: var(--text-gray);
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .announcement-content .time {
        font-size: 11px;
        color: var(--text-gray);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .stats-content {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }
    }

    @media (min-width: 769px) {
        .menu-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
    }
</style>
@endpush

