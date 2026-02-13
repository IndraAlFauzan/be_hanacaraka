@extends('admin.layout.app')

@section('title', 'Kelola Badges')
@section('page-title', 'Kelola Badges')
@section('page-subtitle', 'Daftar semua badge penghargaan')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span><i class="bi bi-trophy me-2"></i>Daftar Badges</span>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.badges.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Badge
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($badges as $badge)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 border">
                    <div class="card-body text-center">
                        <div class="fs-1 mb-3">{{ $badge->icon_url }}</div>
                        <h5 class="card-title">{{ $badge->name }}</h5>
                        <p class="card-text text-muted small">{{ $badge->description }}</p>
                        
                        <hr>
                        
                        <div class="mb-2">
                            <small class="text-muted">Kriteria:</small><br>
                            <strong>{{ $badge->criteria_type }}</strong>
                            @if($badge->criteria_value)
                                <span class="badge bg-info">{{ $badge->criteria_value }}</span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Bonus XP:</small><br>
                            <span class="badge bg-warning text-dark">+{{ $badge->xp_reward }} XP</span>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">User yang memiliki:</small><br>
                            <span class="badge bg-success">{{ $badge->users_count }} user</span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.badges.edit', $badge->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="deleteBadge({{ $badge->id }})">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-trophy fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted">Belum ada badge yang dibuat</p>
                    <a href="{{ route('admin.badges.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Badge Pertama
                    </a>
                </div>
            </div>
            @endforelse
        </div>
        
        @if($badges->hasPages())
        <div class="mt-3">
            {{ $badges->links() }}
        </div>
        @endif
    </div>
</div>

<form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteBadge(id) {
    if (confirm('Yakin ingin menghapus badge ini? Badge yang sudah diraih user akan ikut terhapus!')) {
        const form = document.getElementById('delete-form');
        form.action = '/admin/badges/' + id;
        form.submit();
    }
}
</script>
@endsection
