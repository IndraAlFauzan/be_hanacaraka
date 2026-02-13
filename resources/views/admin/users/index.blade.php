@extends('admin.layout.app')

@section('title', 'Kelola Users')
@section('page-title', 'Kelola Users')
@section('page-subtitle', 'Daftar semua pemain')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span><i class="bi bi-people me-2"></i>Daftar Pemain</span>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari nama atau email..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Total XP</th>
                        <th>Streak</th>
                        <th>Stages</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td><small class="text-muted">#{{ $user->id }}</small></td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                                @else
                                    <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td><small class="text-muted">{{ $user->email }}</small></td>
                        <td><span class="badge bg-info">Level {{ $user->current_level }}</span></td>
                        <td><strong>{{ number_format($user->total_xp) }}</strong> XP</td>
                        <td>
                            @if($user->streak_count > 0)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-fire me-1"></i>{{ $user->streak_count }} hari
                                </span>
                            @else
                                <span class="badge bg-secondary">0</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $user->completed_stages ?? 0 }}/135</span>
                        </td>
                        <td><small>{{ $user->created_at->format('d M Y') }}</small></td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">
                                @if(request('search'))
                                    Tidak ditemukan user dengan kata kunci "{{ request('search') }}"
                                @else
                                    Belum ada user yang terdaftar
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="mt-3">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
