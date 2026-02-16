@extends('admin.layout.app')

@section('title', 'Kelola Levels')
@section('page-title', 'Kelola Levels')
@section('page-subtitle', 'Daftar semua level pembelajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-stack"></i>
            <span>Daftar Levels</span>
            <span class="badge badge-soft-primary ms-2">{{ $levels->total() ?? $levels->count() }} total</span>
        </div>
        <a href="{{ route('admin.levels.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Level</span>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">Level</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th style="width: 130px;">XP Required</th>
                        <th style="width: 100px;">Stages</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($levels as $level)
                    <tr>
                        <td>
                            <div class="avatar avatar-sm bg-primary text-white">
                                {{ $level->level_number }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $level->title }}</div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 0.85rem;">{{ Str::limit($level->description, 50) ?: '-' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-soft-info">
                                <i class="bi bi-lightning-fill me-1"></i>{{ number_format($level->xp_required) }} XP
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-soft-secondary">{{ $level->stages_count ?? 0 }} stages</span>
                        </td>
                        <td>
                            @if($level->is_active)
                                <span class="badge badge-soft-success">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge badge-soft-secondary">
                                    <i class="bi bi-pause-circle me-1"></i>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.levels.edit', $level->id) }}" 
                                   class="btn btn-sm btn-icon btn-outline-primary" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit Level">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-icon btn-outline-danger" 
                                        data-bs-toggle="tooltip" 
                                        title="Hapus Level"
                                        onclick="confirmDelete('/admin/levels/{{ $level->id }}', 'Level &quot;{{ $level->title }}&quot; dan semua stage terkait akan dihapus permanen.')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-stack"></i>
                                </div>
                                <h5>Belum ada level</h5>
                                <p>Mulai dengan membuat level pertama untuk pembelajaran.</p>
                                <a href="{{ route('admin.levels.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Tambah Level Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($levels->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
            {{ $levels->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
