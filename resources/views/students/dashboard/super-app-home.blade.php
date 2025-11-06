@extends('students.layouts.super-app')

@section('content')
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
            <!-- Bimbingan PA -->
            <a href="{{ route('student.counseling.show') }}" class="menu-card">
                <div class="menu-icon" style="background: linear-gradient(135deg, #FF9800, #FFB347);">
                    <i class="bi bi-person-video3"></i>
                </div>
                <h5>Bimbingan PA</h5>
                <p>Konsultasi dengan dosen pembimbing akademik</p>
                <span class="status-badge active">Aktif</span>
            </a>
            
            <!-- Magang -->
            <div class="menu-card">
                <div class="menu-icon" style="background: linear-gradient(135deg, #FFC107, #FFD54F);">
                    <i class="bi bi-building"></i>
                </div>
                <h5>Magang</h5>
                <p>Program magang dan praktik kerja</p>
                <span class="status-badge active">Aktif</span>
            </div>
            
            <!-- TA -->
            <div class="menu-card">
                <div class="menu-icon" style="background: linear-gradient(135deg, #FF5252, #FF8A80);">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <h5>Tugas Akhir</h5>
                <p>Platform Tugas Akhir</p>
                <span class="status-badge active">Aktif</span>
            </div>

            <!-- Siakad Online -->
            <a target="_blank" href="https://siakad.sugenghartono.ac.id/" class="menu-card">
                <div class="menu-icon" style="background: linear-gradient(135deg, #5B9BD5, #7DB8E8);">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h5>Siakad Online</h5>
                <p>Kelola informasi Nilai Anda</p>
                <span class="status-badge active">Aktif</span>
            </a>
            
            <!-- Perpustakaan -->
            <a target="_blank" href="https://library.sugenghartono.ac.id/" class="menu-card">
                <div class="menu-icon" style="background: linear-gradient(135deg, #4CAF50, #81C784);">
                    <i class="bi bi-book-half"></i>
                </div>
                <h5>Perpustakaan</h5>
                <p>Akses katalog dan peminjaman buku</p>
                <span class="status-badge active">Aktif</span>
            </a>
            
            <!-- Future Features -->
            <div class="menu-card">
                <div class="menu-icon" style="background: linear-gradient(135deg, #9C27B0, #BA68C8);">
                    <i class="bi bi-stars"></i>
                </div>
                <h5>Future Features</h5>
                <p>Fitur baru yang akan datang</p>
                <span class="status-badge info">Coming Soon</span>
            </div>
        </div>
    </div>

    <!-- Announcement Section -->
    <div class="announcement-section">
        <div class="section-header">
            <h3>Pengumuman Terbaru</h3>
            <a href="#" class="view-all">Lihat Semua</a>
        </div>
        
        <div class="announcement-list">
            <div class="announcement-item">
                <div class="announcement-icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div class="announcement-content">
                    <h5>Pendaftaran Wisuda Periode 2025</h5>
                    <p>Pendaftaran wisuda dibuka hingga 15 Oktober 2025</p>
                    <span class="time"><i class="bi bi-clock"></i> 2 jam yang lalu</span>
                </div>
            </div>
            
            <div class="announcement-item">
                <div class="announcement-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="announcement-content">
                    <h5>Jadwal UTS Semester Genap</h5>
                    <p>Ujian tengah semester dimulai 20 Oktober 2025</p>
                    <span class="time"><i class="bi bi-clock"></i> 5 jam yang lalu</span>
                </div>
            </div>
            
            <div class="announcement-item">
                <div class="announcement-icon">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                <div class="announcement-content">
                    <h5>Kompetisi Inovasi Mahasiswa</h5>
                    <p>Daftarkan tim Anda untuk kompetisi tahunan</p>
                    <span class="time"><i class="bi bi-clock"></i> 1 hari yang lalu</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
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

