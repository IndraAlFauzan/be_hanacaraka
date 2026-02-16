@extends('admin.layout.app')

@section('title', 'Kelola Badges')
@section('page-title', 'Kelola Badges')
@section('page-subtitle', 'Daftar semua badge penghargaan')

@push('styles')
<style>
    .badge-card {
        border: 2px solid #e9ecef;
        border-radius: 16px;
        transition: all 0.3s ease;
        height: 100%;
    }
    .badge-card:hover {
        border-color: #667eea;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .badge-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 16px;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
    }
    .criteria-badge {
        background: #f0f0f0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.75rem;
        display: inline-block;
    }
    .xp-badge {
        background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        color: white;
        border-radius: 20px;
        padding: 4px 12px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .users-count {
        background: rgba(17, 153, 142, 0.1);
        color: #11998e;
        border-radius: 20px;
        padding: 4px 12px;
        font-weight: 600;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-trophy-fill text-warning"></i>
            <span>Daftar Badges</span>
            <span class="badge badge-soft-warning ms-2">{{ $badges->total() }} total</span>
        </div>
        <a href="{{ route('admin.badges.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i><span>Tambah Badge</span>
        </a>
    </div>
    <div class="card-body">
        @if($badges->count() > 0)
        <div class="row g-4">
            @foreach($badges as $badge)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="badge-card p-4">
                    <div class="badge-icon">{{ $badge->icon_url }}</div>
                    <h5 class="text-center fw-bold mb-2">{{ $badge->name }}</h5>
                    <p class="text-center text-muted small mb-3">{{ Str::limit($badge->description, 60) }}</p>
                    
                    <div class="text-center mb-3">
                        <div class="criteria-badge mb-2">
                            <i class="bi bi-target me-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $badge->criteria_type)) }}
                            @if($badge->criteria_value)
                                : <strong>{{ number_format($badge->criteria_value) }}</strong>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="xp-badge">+{{ $badge->xp_reward }} XP</span>
                        <span class="users-count">
                            <i class="bi bi-people-fill me-1"></i>{{ $badge->users_count }}
                        </span>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.badges.edit', $badge->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm"
                                onclick="confirmDelete('{{ route('admin.badges.destroy', $badge->id) }}', 'Badge ini dan semua data user yang memilikinya akan dihapus permanen.')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($badges->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $badges->links() }}
        </div>
        @endif
        @else
        <div class="empty-state py-5">
            <div class="empty-state-icon">
                <i class="bi bi-trophy"></i>
            </div>
            <h5>Belum ada badge</h5>
            <p class="text-muted">Buat badge pertama untuk memberikan penghargaan kepada pemain</p>
            <a href="{{ route('admin.badges.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Badge Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
