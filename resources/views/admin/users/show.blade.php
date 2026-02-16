@extends('admin.layout.app')

@section('title', 'Detail User - ' . $user->name)
@section('page-title', 'Detail User')
@section('page-subtitle', 'Informasi lengkap pemain')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .profile-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -50%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        border: 4px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
    }
    .stat-pill {
        background: rgba(255,255,255,0.2);
        border-radius: 50px;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        backdrop-filter: blur(10px);
    }
    .stat-pill i {
        font-size: 1rem;
    }
    .progress-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .progress-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .progress-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .progress-label {
        color: #6c757d;
        font-size: 0.85rem;
    }
    .badge-item {
        background: #fff;
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .badge-item:hover {
        border-color: #667eea;
        transform: translateY(-3px);
    }
    .badge-icon {
        font-size: 2.5rem;
        margin-bottom: 8px;
    }
    .badge-name {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1a1a2e;
    }
    .badge-date {
        font-size: 0.7rem;
        color: #9ca3af;
    }
    .activity-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .activity-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .activity-icon.completed {
        background: rgba(17, 153, 142, 0.1);
        color: #11998e;
    }
    .activity-icon.progress {
        background: rgba(247, 151, 30, 0.1);
        color: #f7971e;
    }
    /* Level Progress Styles */
    .level-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .level-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .level-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .level-header.locked {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }
    .level-header .level-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .level-number-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }
    .level-title {
        font-weight: 600;
        margin: 0;
    }
    .level-stats {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 0.85rem;
    }
    .level-body {
        padding: 16px 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    .level-body.show {
        max-height: 2000px;
    }
    .stage-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        background: #f8f9fa;
        margin-bottom: 8px;
    }
    .stage-item:last-child {
        margin-bottom: 0;
    }
    .stage-item.completed {
        background: rgba(17, 153, 142, 0.1);
        border-left: 3px solid #11998e;
    }
    .stage-item.unlocked {
        background: rgba(102, 126, 234, 0.1);
        border-left: 3px solid #667eea;
    }
    .stage-item.locked {
        background: #f0f0f0;
        opacity: 0.6;
    }
    .stage-status-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    .stage-status-icon.completed {
        background: #11998e;
        color: white;
    }
    .stage-status-icon.unlocked {
        background: #667eea;
        color: white;
    }
    .stage-status-icon.locked {
        background: #adb5bd;
        color: white;
    }
    .stage-info {
        flex-grow: 1;
    }
    .stage-title {
        font-weight: 500;
        font-size: 0.9rem;
        margin: 0;
    }
    .stage-meta {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .stage-xp {
        font-weight: 600;
        color: #f7971e;
    }
    .transition-rotate {
        transition: transform 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar User
    </a>
</div>

<!-- Profile Header -->
<div class="profile-header mb-4">
    <div class="d-flex align-items-center gap-4 flex-wrap position-relative" style="z-index: 1;">
        <div class="profile-avatar">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <div class="flex-grow-1">
            <h2 class="mb-1 fw-bold">{{ $user->name }}</h2>
            <p class="mb-3 opacity-75">{{ $user->email }}</p>
            <div class="d-flex gap-2 flex-wrap">
                <span class="stat-pill">
                    <i class="bi bi-star-fill text-warning"></i>
                    Level {{ $user->current_level }}
                </span>
                <span class="stat-pill">
                    <i class="bi bi-lightning-fill text-warning"></i>
                    {{ number_format($user->total_xp) }} XP
                </span>
                @if($user->streak_count > 0)
                <span class="stat-pill">
                    <i class="bi bi-fire text-danger"></i>
                    {{ $user->streak_count }} hari streak
                </span>
                @endif
                <span class="stat-pill">
                    <i class="bi bi-calendar3"></i>
                    Bergabung {{ $user->created_at->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Progress Stats -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="progress-card">
                    <div class="progress-value text-success">{{ $completedStages }}</div>
                    <div class="progress-label">Stages Selesai</div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $completionPercentage }}%"></div>
                    </div>
                    <small class="text-muted">{{ $completionPercentage }}% dari {{ $totalStages }}</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="progress-card">
                    <div class="progress-value text-warning">{{ $completedQuizzes }}</div>
                    <div class="progress-label">Kuis Selesai</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="progress-card">
                    <div class="progress-value text-info">{{ $completedEvaluations }}</div>
                    <div class="progress-label">Evaluasi Selesai</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="progress-card">
                    <div class="progress-value text-primary">{{ $userBadges->count() }}</div>
                    <div class="progress-label">Badge Diraih</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Level Progress -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-layers-fill text-primary"></i>
                    <span>Progress Per Level</span>
                    <span class="badge badge-soft-primary ms-auto">{{ count($levelsProgress) }} Levels</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-3">
                    @foreach($levelsProgress as $level)
                    <div class="level-card mb-3">
                        <div class="level-header {{ !$level['is_unlocked'] ? 'locked' : '' }}" 
                             onclick="toggleLevel({{ $level['level_id'] }})" 
                             data-level-id="{{ $level['level_id'] }}">
                            <div class="level-info">
                                <div class="level-number-badge">{{ $level['level_number'] }}</div>
                                <div>
                                    <p class="level-title">{{ $level['title'] }}</p>
                                    <small class="opacity-75">{{ $level['completed_stages'] }}/{{ $level['total_stages'] }} stages</small>
                                </div>
                            </div>
                            <div class="level-stats">
                                @if($level['is_unlocked'])
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress" style="width: 100px; height: 6px;">
                                            <div class="progress-bar bg-white" style="width: {{ $level['completion_percentage'] }}%"></div>
                                        </div>
                                        <span>{{ $level['completion_percentage'] }}%</span>
                                    </div>
                                @else
                                    <span class="badge bg-dark bg-opacity-25">
                                        <i class="bi bi-lock-fill me-1"></i>{{ $level['xp_required'] }} XP needed
                                    </span>
                                @endif
                                <i class="bi bi-chevron-down transition-rotate" id="chevron-{{ $level['level_id'] }}"></i>
                            </div>
                        </div>
                        <div class="level-body" id="level-body-{{ $level['level_id'] }}">
                            @foreach($level['stages'] as $stage)
                            <div class="stage-item {{ $stage['status'] }}">
                                <div class="stage-status-icon {{ $stage['status'] }}">
                                    @if($stage['status'] === 'completed')
                                        <i class="bi bi-check"></i>
                                    @elseif($stage['status'] === 'unlocked')
                                        <i class="bi bi-play-fill"></i>
                                    @else
                                        <i class="bi bi-lock-fill"></i>
                                    @endif
                                </div>
                                <div class="stage-info">
                                    <p class="stage-title">{{ $stage['stage_number'] }}. {{ $stage['title'] }}</p>
                                    <div class="stage-meta">
                                        <span class="badge badge-soft-secondary me-1">{{ $stage['evaluation_type'] }}</span>
                                        @if($stage['status'] === 'completed' && $stage['completed_at'])
                                            <span class="text-success">
                                                <i class="bi bi-check-circle-fill"></i>
                                                {{ \Carbon\Carbon::parse($stage['completed_at'])->format('d M Y H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="stage-xp">
                                    +{{ $stage['xp_reward'] }} XP
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Badges Section -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-award-fill text-warning"></i>
                    <span>Badge yang Diraih</span>
                    <span class="badge badge-soft-warning ms-auto">{{ $userBadges->count() }} badge</span>
                </div>
            </div>
            <div class="card-body">
                @if($userBadges->count() > 0)
                    <div class="row g-3">
                        @foreach($userBadges as $badge)
                        <div class="col-4 col-md-3">
                            <div class="badge-item">
                                <div class="badge-icon">{{ $badge->icon_url }}</div>
                                <div class="badge-name">{{ $badge->name }}</div>
                                <div class="badge-date">{{ $badge->pivot->earned_at ? \Carbon\Carbon::parse($badge->pivot->earned_at)->format('d M') : '-' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-4">
                        <div class="empty-state-icon" style="width: 60px; height: 60px;">
                            <i class="bi bi-trophy" style="font-size: 1.5rem;"></i>
                        </div>
                        <p class="text-muted mb-0">Belum meraih badge apapun</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span>Aktivitas Terakhir</span>
                </div>
            </div>
            <div class="card-body">
                @if($recentProgress->count() > 0)
                    @foreach($recentProgress as $progress)
                    <div class="activity-item">
                        <div class="activity-icon {{ $progress->status === 'completed' ? 'completed' : 'progress' }}">
                            <i class="bi bi-{{ $progress->status === 'completed' ? 'check-circle-fill' : 'hourglass-split' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $progress->stage->title }}</div>
                            <small class="text-muted">Level {{ $progress->stage->level->level_number }}</small>
                        </div>
                        <div class="text-end">
                            @if($progress->status === 'completed')
                                <span class="badge badge-soft-success">Selesai</span>
                            @else
                                <span class="badge badge-soft-warning">Progress</span>
                            @endif
                            <small class="text-muted d-block">{{ $progress->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state py-4">
                        <div class="empty-state-icon" style="width: 60px; height: 60px;">
                            <i class="bi bi-activity" style="font-size: 1.5rem;"></i>
                        </div>
                        <p class="text-muted mb-0">Belum ada aktivitas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleLevel(levelId) {
    const body = document.getElementById('level-body-' + levelId);
    const chevron = document.getElementById('chevron-' + levelId);
    
    if (body.classList.contains('show')) {
        body.classList.remove('show');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        body.classList.add('show');
        chevron.style.transform = 'rotate(180deg)';
    }
}

// Auto-expand first level with progress
document.addEventListener('DOMContentLoaded', function() {
    const firstLevel = document.querySelector('.level-body');
    const firstChevron = document.querySelector('.transition-rotate');
    if (firstLevel) {
        firstLevel.classList.add('show');
        if (firstChevron) {
            firstChevron.style.transform = 'rotate(180deg)';
        }
    }
});
</script>
@endpush
