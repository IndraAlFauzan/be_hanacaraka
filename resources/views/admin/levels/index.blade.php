@extends('admin.layout.app')

@section('title', 'Kelola Levels')
@section('page-title', 'Kelola Levels')
@section('page-subtitle', 'Daftar semua level pembelajaran')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-layers me-2"></i>Daftar Levels</span>
        <a href="{{ route('admin.levels.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Level
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 80px;">Level #</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th style="width: 120px;">XP Required</th>
                        <th style="width: 120px;">Total Stages</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($levels as $level)
                    <tr>
                        <td>
                            <span class="badge bg-primary fs-6">{{ $level->level_number }}</span>
                        </td>
                        <td>
                            <strong>{{ $level->title }}</strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($level->description, 60) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ number_format($level->xp_required) }} XP</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $level->stages_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($level->is_active)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.levels.edit', $level->id) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" 
                                        onclick="deleteLevel({{ $level->id }}, '{{ $level->title }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada level. Silakan tambahkan level baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($levels->hasPages())
        <div class="mt-3">
            {{ $levels->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function deleteLevel(id, title) {
    if (confirm(`Apakah Anda yakin ingin menghapus level "${title}"?\n\nSemua stage dan konten terkait akan ikut terhapus!`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/levels/${id}`;
        form.submit();
    }
}
</script>
@endpush
