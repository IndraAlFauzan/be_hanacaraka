@extends('admin.layout.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan statistik sistem')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Users -->
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <h3 class="mb-1">{{ $stats['total_users'] ?? 0 }}</h3>
            <p class="text-muted mb-0">Total Pemain</p>
        </div>
    </div>
    
    <!-- Active Today -->
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="icon">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <h3 class="mb-1">{{ $stats['active_users_today'] ?? 0 }}</h3>
            <p class="text-muted mb-0">Aktif Hari Ini</p>
        </div>
    </div>
    
    <!-- Total Stages -->
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="icon">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <h3 class="mb-1">{{ $stats['total_stages'] ?? 0 }}</h3>
            <p class="text-muted mb-0">Total Stage</p>
        </div>
    </div>
    
    <!-- Completion Rate -->
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="icon">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <h3 class="mb-1">{{ number_format($stats['avg_completion_rate'] ?? 0, 1) }}%</h3>
            <p class="text-muted mb-0">Avg Completion</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Top Users -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy-fill text-warning me-2"></i>
                Top 5 Pemain
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Nama</th>
                                <th>XP</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['top_5_users'] ?? [] as $index => $user)
                            <tr>
                                <td>
                                    @if($index == 0)
                                        <span class="badge bg-warning text-dark">🥇 #1</span>
                                    @elseif($index == 1)
                                        <span class="badge bg-secondary">🥈 #2</span>
                                    @elseif($index == 2)
                                        <span class="badge bg-danger">🥉 #3</span>
                                    @else
                                        <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td><strong>{{ number_format($user->total_xp) }}</strong></td>
                                <td><span class="badge bg-info">Level {{ $user->current_level }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Latest Registrations -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-plus-fill text-success me-2"></i>
                Pendaftaran Terbaru
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['latest_registrations'] ?? [] as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td><small class="text-muted">{{ $user->email }}</small></td>
                                <td><small>{{ $user->created_at->diffForHumans() }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada pendaftaran</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <!-- Quick Stats -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                Statistik Detail
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <h4 class="text-primary mb-1">{{ $stats['total_completed_stages'] ?? 0 }}</h4>
                        <small class="text-muted">Total Stage Completed</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h4 class="text-success mb-1">8</h4>
                        <small class="text-muted">Total Levels</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h4 class="text-info mb-1">135</h4>
                        <small class="text-muted">Total Stages</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h4 class="text-warning mb-1">18</h4>
                        <small class="text-muted">Total Badges</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
