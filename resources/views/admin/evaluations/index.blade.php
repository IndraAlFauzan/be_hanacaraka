@extends('admin.layout.app')

@section('title', 'Kelola Evaluasi')
@section('page-title', 'Kelola Evaluasi')
@section('page-subtitle', 'Daftar semua evaluasi menggambar aksara')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span><i class="bi bi-pencil-square me-2"></i>Daftar Evaluasi</span>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value;">
                        <option value="{{ route('admin.evaluations.index') }}" {{ !request('stage_id') ? 'selected' : '' }}>Semua Stage</option>
                        @foreach($stages as $stage)
                            <option value="{{ route('admin.evaluations.index', ['stage_id' => $stage->id]) }}" 
                                    {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                                {{ $stage->title }} (Level {{ $stage->level->level_number }})
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('admin.evaluations.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Evaluasi
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
                        <th>ID</th>
                        <th>Stage</th>
                        <th>Karakter Target</th>
                        <th>Gambar Referensi</th>
                        <th>Min. Similarity</th>
                        <th>Submissions</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $evaluation)
                    <tr>
                        <td><small class="text-muted">#{{ $evaluation->id }}</small></td>
                        <td>
                            <span class="badge bg-info">
                                {{ $evaluation->stage->title }}
                            </span>
                        </td>
                        <td>
                            <span class="fs-4">{{ $evaluation->character_target }}</span>
                        </td>
                        <td>
                            @if($evaluation->reference_image_url)
                                <img src="{{ $evaluation->reference_image_url }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">≥ {{ $evaluation->min_similarity_score }}%</span>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $evaluation->submissions_count }} submisi</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.evaluations.edit', $evaluation->id) }}" 
                                   class="btn btn-outline-primary" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        onclick="deleteEvaluation({{ $evaluation->id }})"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada evaluasi yang dibuat</p>
                            <a href="{{ route('admin.evaluations.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Evaluasi Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($evaluations->hasPages())
        <div class="mt-3">
            {{ $evaluations->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteEvaluation(id) {
    if (confirm('Yakin ingin menghapus evaluasi ini? Semua submission user akan ikut terhapus!')) {
        const form = document.getElementById('delete-form');
        form.action = '/admin/evaluations/' + id;
        form.submit();
    }
}
</script>
@endsection
