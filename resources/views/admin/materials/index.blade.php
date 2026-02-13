@extends('admin.layout.app')

@section('title', 'Kelola Materi')
@section('page-title', 'Kelola Materi')
@section('page-subtitle', 'Daftar semua materi pembelajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span><i class="bi bi-book me-2"></i>Daftar Materi</span>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value;">
                        <option value="{{ route('admin.materials.index') }}" {{ !request('stage_id') ? 'selected' : '' }}>Semua Stage</option>
                        @foreach($stages as $stage)
                            <option value="{{ route('admin.materials.index', ['stage_id' => $stage->id]) }}" 
                                    {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                                {{ $stage->title }} (Level {{ $stage->level->level_number }})
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('admin.materials.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Materi
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
                        <th>Order</th>
                        <th>Judul</th>
                        <th>Stage</th>
                        <th>Tipe</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                    <tr>
                        <td><strong>{{ $material->order_index }}</strong></td>
                        <td>
                            <strong>{{ $material->title }}</strong><br>
                            <small class="text-muted">{{ Str::limit($material->content_text, 60) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $material->stage->title }}
                            </span>
                        </td>
                        <td>
                            @if($material->content_markdown)
                                <span class="badge bg-primary">Markdown</span>
                            @endif
                            @if($material->content_text)
                                <span class="badge bg-secondary">Text</span>
                            @endif
                        </td>
                        <td>
                            @if($material->image_url)
                                <img src="{{ $material->image_url }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.materials.edit', $material->id) }}" 
                                   class="btn btn-outline-primary" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        onclick="deleteMaterial({{ $material->id }})"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada materi yang dibuat</p>
                            <a href="{{ route('admin.materials.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Materi Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($materials->hasPages())
        <div class="mt-3">
            {{ $materials->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteMaterial(id) {
    if (confirm('Yakin ingin menghapus materi ini?')) {
        const form = document.getElementById('delete-form');
        form.action = '/admin/materials/' + id;
        form.submit();
    }
}
</script>
@endsection
