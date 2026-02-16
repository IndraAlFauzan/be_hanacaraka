@extends('admin.layout.app')

@section('title', 'Kelola Users')
@section('page-title', 'Kelola Users')
@section('page-subtitle', 'Daftar semua pemain terdaftar')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-people-fill"></i>
            <span>Daftar Pemain</span>
            <span class="badge badge-soft-primary ms-2">{{ $users->total() ?? $users->count() }} total</span>
        </div>
        <form method="GET" class="d-flex gap-2" style="width: 320px;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" 
                       name="search" 
                       class="form-control border-start-0 ps-0" 
                       placeholder="Cari nama atau email..." 
                       value="{{ request('search') }}">
                @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
                @else
                <button type="submit" class="btn btn-primary">Cari</button>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Pemain</th>
                        <th>Level</th>
                        <th>Total XP</th>
                        <th style="width: 100px;">Streak</th>
                        <th style="width: 120px;">Progress</th>
                        <th style="width: 120px;">Terdaftar</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" class="avatar avatar-sm rounded-circle" style="object-fit: cover;">
                                @else
                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-soft-info">
                                <i class="bi bi-star-fill me-1"></i>Level {{ $user->current_level }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-primary">
                                <i class="bi bi-lightning-fill text-warning"></i> {{ number_format($user->total_xp) }}
                            </span>
                        </td>
                        <td>
                            @if($user->streak_count > 0)
                                <span class="badge badge-soft-warning">
                                    <i class="bi bi-fire"></i> {{ $user->streak_count }} hari
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; width: 70px;">
                                    @php $progress = (($user->completed_stages ?? 0) / 135) * 100; @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ $user->completed_stages ?? 0 }}/135</small>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>{{ $user->created_at->format('d M Y') }}
                            </small>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" 
                               class="btn btn-sm btn-icon btn-outline-primary" 
                               data-bs-toggle="tooltip" 
                               title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h5>
                                    @if(request('search'))
                                        Tidak ditemukan
                                    @else
                                        Belum ada pemain
                                    @endif
                                </h5>
                                <p>
                                    @if(request('search'))
                                        Tidak ada user dengan kata kunci "{{ request('search') }}"
                                    @else
                                        Belum ada user yang terdaftar di aplikasi.
                                    @endif
                                </p>
                                @if(request('search'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Lihat Semua
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
