@extends('admin.layout.app')

@section('title', 'Kelola Stages')
@section('page-title', 'Kelola Stages')
@section('page-subtitle', 'Daftar semua stage pembelajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span><i class="bi bi-list-task me-2"></i>Daftar Stages</span>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value;">
                        <option value="{{ route('admin.stages.index') }}" {{ !request('level_id') ? 'selected' : '' }}>Semua Level</option>
                        @foreach($levels as $level)
                            <option value="{{ route('admin.stages.index', ['level_id' => $level->id]) }}" 
                                    {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                Level {{ $level->level_number }}: {{ $level->title }}
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('admin.stages.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Stage
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Judul</th>
                        <th>Level</th>
                        <th>XP</th>
                        <th>Status</th>
                        <th>Materi</th>
                        <th>Kuis</th>
                        <th>Evaluasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stages as $stage)
                    <tr>
                        <td><strong>{{ $stage->stage_number }}</strong></td>
                        <td>
                            <strong>{{ $stage->title }}</strong><br>
                            <small class="text-muted">{{ Str::limit($stage->description, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                Level {{ $stage->level->level_number }}: {{ $stage->level->title }}
                            </span>
                        </td>
                        <td><strong>{{ $stage->xp_reward }}</strong> XP</td>
                        <td>
                            <span class="badge bg-{{ $stage->is_active ? 'success' : 'secondary' }}">
                                {{ $stage->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            @if($stage->materials_count > 0)
                                <span class="badge bg-primary">{{ $stage->materials_count }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($stage->quizzes_count > 0)
                                <span class="badge bg-warning text-dark">{{ $stage->quizzes_count }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($stage->evaluations_count > 0)
                                <span class="badge bg-success">{{ $stage->evaluations_count }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.stages.edit', $stage->id) }}" 
                                   class="btn btn-outline-primary" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        onclick="deleteStage({{ $stage->id }})"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada stage yang dibuat</p>
                            <a href="{{ route('admin.stages.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Stage Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($stages->hasPages())
        <div class="mt-3">
            {{ $stages->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteStage(id) {
    if (confirm('Yakin ingin menghapus stage ini? Semua materi, kuis, dan evaluasi terkait akan ikut terhapus!')) {
        const form = document.getElementById('delete-form');
        form.action = '/admin/stages/' + id;
        form.submit();
    }
}
</script>
@endsection
