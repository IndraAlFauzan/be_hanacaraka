@extends('admin.layout.app')

@section('title', 'Dashboard')
@section('page-title', 'Selamat Datang, Admin! 👋')
@section('page-subtitle', 'Ringkasan statistik dan aktivitas sistem Hanacaraka')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -60%;
        left: -10%;
        width: 250px;
        height: 250px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .welcome-card h2 {
        font-size: 1.8rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }
    .welcome-card p {
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    .stat-box {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .stat-box .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-box .stat-icon.primary {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }
    .stat-box .stat-icon.success {
        background: rgba(17, 153, 142, 0.1);
        color: #11998e;
    }
    .stat-box .stat-icon.info {
        background: rgba(0, 123, 255, 0.1);
        color: #007bff;
    }
    .stat-box .stat-icon.warning {
        background: rgba(247, 151, 30, 0.1);
        color: #f7971e;
    }
    .stat-box .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1;
    }
    .stat-box .stat-label {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 4px;
    }
    .stat-box .stat-trend {
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .stat-box .stat-trend.up {
        color: #11998e;
    }
    .stat-box .stat-trend.down {
        color: #e74c3c;
    }
    .leaderboard-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .leaderboard-item:last-child {
        border-bottom: none;
    }
    .rank-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .rank-badge.gold {
        background: linear-gradient(135deg, #ffd700 0%, #ffb347 100%);
        color: #fff;
    }
    .rank-badge.silver {
        background: linear-gradient(135deg, #c0c0c0 0%, #a8a8a8 100%);
        color: #fff;
    }
    .rank-badge.bronze {
        background: linear-gradient(135deg, #cd7f32 0%, #b87333 100%);
        color: #fff;
    }
    .rank-badge.normal {
        background: #f0f0f0;
        color: #6c757d;
    }
    .user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .registration-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .registration-item:last-child {
        border-bottom: none;
    }
    .quick-stat {
        text-align: center;
        padding: 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        border: 1px solid #e9ecef;
    }
    .quick-stat .value {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .quick-stat .label {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<!-- Welcome Card -->
<div class="welcome-card mb-4 d-none d-lg-block">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h2>Dashboard Admin Hanacaraka</h2>
            <p class="mb-0">Monitor aktivitas pembelajaran, kelola konten, dan pantau perkembangan pengguna dari satu tempat.</p>
        </div>
        <div class="col-lg-4 text-end position-relative" style="z-index: 1;">
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='150' height='100' viewBox='0 0 150 100'%3E%3Ctext x='50%25' y='50%25' font-size='60' text-anchor='middle' dominant-baseline='middle' fill='rgba(255,255,255,0.3)'%3Eꦲꦤꦕꦫꦏ%3C/text%3E%3C/svg%3E" alt="Aksara" style="opacity: 0.6;">
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-box">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span class="stat-trend up">
                    <i class="bi bi-arrow-up"></i> +12%
                </span>
            </div>
            <div class="stat-value">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="stat-label">Total Pemain</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-box">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon success">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <span class="badge badge-soft-success">Live</span>
            </div>
            <div class="stat-value">{{ $stats['active_users_today'] ?? 0 }}</div>
            <div class="stat-label">Aktif Hari Ini</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-box">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon info">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_stages'] ?? 0 }}</div>
            <div class="stat-label">Total Stage</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-box">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon warning">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['avg_completion_rate'] ?? 0, 1) }}%</div>
            <div class="stat-label">Rata-rata Completion</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Leaderboard -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill text-warning"></i>
                    <span>Leaderboard Top 5</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @forelse($stats['top_5_users'] ?? [] as $index => $user)
                <div class="leaderboard-item">
                    <div class="rank-badge {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'normal')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="user-avatar" style="background: {{ ['#667eea', '#11998e', '#f7971e', '#e74c3c', '#9b59b6'][$index % 5] }}; color: #fff;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $user->name }}</div>
                        <small class="text-muted">Level {{ $user->current_level }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary">{{ number_format($user->total_xp) }}</div>
                        <small class="text-muted">XP</small>
                    </div>
                </div>
                @empty
                <div class="empty-state py-4">
                    <div class="empty-state-icon" style="width: 60px; height: 60px;">
                        <i class="bi bi-trophy" style="font-size: 1.5rem;"></i>
                    </div>
                    <p class="text-muted mb-0">Belum ada data pemain</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Latest Registrations -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus-fill text-success"></i>
                    <span>Pendaftaran Terbaru</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @forelse($stats['latest_registrations'] ?? [] as $user)
                <div class="registration-item">
                    <div class="user-avatar" style="background: #e9ecef; color: #6c757d;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $user->name }}</div>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge badge-soft-success">Baru</span>
                        <small class="text-muted d-block">{{ $user->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                <div class="empty-state py-4">
                    <div class="empty-state-icon" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-plus" style="font-size: 1.5rem;"></i>
                    </div>
                    <p class="text-muted mb-0">Belum ada pendaftaran</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-fill text-primary"></i>
                    <span>Ringkasan Konten</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="quick-stat">
                            <div class="value text-primary">{{ $stats['total_completed_stages'] ?? 0 }}</div>
                            <div class="label">Stage Diselesaikan</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="quick-stat">
                            <div class="value text-success">{{ $stats['total_levels'] ?? 8 }}</div>
                            <div class="label">Total Level</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="quick-stat">
                            <div class="value text-info">{{ $stats['total_stages'] ?? 135 }}</div>
                            <div class="label">Total Stage</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="quick-stat">
                            <div class="value text-warning">{{ $stats['total_badges'] ?? 18 }}</div>
                            <div class="label">Total Badge</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-lightning-fill text-warning"></i>
                    <span>Aksi Cepat</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.levels.create') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-plus-circle d-block mb-2" style="font-size: 1.5rem;"></i>
                            Tambah Level
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.stages.create') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-plus-circle d-block mb-2" style="font-size: 1.5rem;"></i>
                            Tambah Stage
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-plus-circle d-block mb-2" style="font-size: 1.5rem;"></i>
                            Tambah Kuis
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.badges.create') }}" class="btn btn-outline-warning w-100 py-3">
                            <i class="bi bi-plus-circle d-block mb-2" style="font-size: 1.5rem;"></i>
                            Tambah Badge
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
