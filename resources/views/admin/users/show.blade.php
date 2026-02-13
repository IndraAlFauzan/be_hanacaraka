@extends('admin.layout.app')

@section('title', 'Detail User - ' . $user->name)
@section('page-title', 'Detail User')
@section('page-subtitle', 'Informasi lengkap tentang ' . $user->name)

@section('content')
<div class="row">
    <!-- User Profile Card -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="avatar-lg bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 48px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} mb-3">
                    {{ ucfirst($user->role) }}
                </span>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <div class="fs-4 fw-bold text-primary">{{ number_format($user->total_xp) }}</div>
                        <small class="text-muted">Total XP</small>
                    </div>
                    <div class="col-6">
                        <div class="fs-4 fw-bold text-info">{{ $user->current_level }}</div>
                        <small class="text-muted">Level</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats Card -->
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Statistik
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Streak</small>
                        <strong>{{ $user->streak_count }} hari</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small>Aktivitas Terakhir</small>
                        <strong>{{ $user->last_activity_date ? $user->last_activity_date->format('d M Y') : '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small>Terdaftar</small>
                        <strong>{{ $user->created_at->format('d M Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Progress & Achievements -->
    <div class="col-md-8">
        <!-- Progress Card -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>Progress Belajar
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <div class="fs-3 fw-bold text-success">{{ $completedStages }}</div>
                                <small class="text-muted">Stages Selesai</small>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($completedStages/135)*100 }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format(($completedStages/135)*100, 1) }}%</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <div class="fs-3 fw-bold text-warning">{{ $completedQuizzes }}</div>
                                <small class="text-muted">Kuis Selesai</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <div class="fs-3 fw-bold text-info">{{ $completedEvaluations }}</div>
                                <small class="text-muted">Evaluasi Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Badges Card -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-trophy me-2"></i>Badge yang Diraih ({{ $userBadges->count() }})
            </div>
            <div class="card-body">
                @if($userBadges->count() > 0)
                    <div class="row">
                        @foreach($userBadges as $userBadge)
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border">
                                <div class="card-body text-center p-2">
                                    <div class="fs-2 mb-2">{{ $userBadge->badge->icon_url }}</div>
                                    <h6 class="mb-0 small">{{ $userBadge->badge->name }}</h6>
                                    <small class="text-muted d-block">{{ $userBadge->earned_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted py-4">
                        <i class="bi bi-trophy fs-1 d-block mb-2"></i>
                        Belum meraih badge apapun
                    </p>
                @endif
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Aktivitas Terakhir
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Stage</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>XP</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProgress as $progress)
                            <tr>
                                <td>
                                    <strong>{{ $progress->stage->title }}</strong><br>
                                    <small class="text-muted">Level {{ $progress->stage->level->level_number }}</small>
                                </td>
                                <td>
                                    @if($progress->is_completed)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">Dalam Progress</span>
                                    @endif
                                </td>
                                <td>
                                    @if($progress->quiz_score !== null)
                                        <span class="badge bg-info">{{ $progress->quiz_score }}%</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><strong>+{{ $progress->xp_earned }}</strong></td>
                                <td><small>{{ $progress->updated_at->diffForHumans() }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Belum ada aktivitas
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
