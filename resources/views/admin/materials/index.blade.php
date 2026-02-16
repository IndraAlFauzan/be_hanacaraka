@extends('admin.layout.app')

@section('title', 'Kelola Materi')
@section('page-title', 'Kelola Materi')
@section('page-subtitle', 'Daftar semua materi pembelajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-journal-richtext"></i>
            <span>Daftar Materi</span>
            <span class="badge badge-soft-primary ms-2">{{ $materials->total() ?? $materials->count() }} total</span>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <select class="form-select form-select-sm" style="width: 220px;" onchange="window.location.href = this.value;">
                <option value="{{ route('admin.materials.index') }}" {{ !request('stage_id') ? 'selected' : '' }}>📁 Semua Stage</option>
                @foreach($stages as $stage)
                    <option value="{{ route('admin.materials.index', ['stage_id' => $stage->id]) }}" 
                            {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                        {{ Str::limit($stage->title, 25) }} (L{{ $stage->level->level_number }})
                    </option>
                @endforeach
            </select>
            <a href="{{ route('admin.materials.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Materi</span>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">Urutan</th>
                        <th style="width: 70px;">Preview</th>
                        <th>Judul Materi</th>
                        <th>Stage</th>
                        <th style="width: 120px;">Tipe Konten</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                    <tr>
                        <td>
                            <div class="avatar avatar-sm bg-secondary text-white">
                                {{ $material->order_index }}
                            </div>
                        </td>
                        <td>
                            @if($material->image_url)
                                <img src="{{ $material->image_url }}" 
                                     class="rounded" 
                                     style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #f0f0f0;">
                            @else
                                <div class="avatar bg-light text-muted">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $material->title }}</div>
                            <small class="text-muted">{{ Str::limit($material->content_text, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge badge-soft-info">
                                <i class="bi bi-collection me-1"></i>{{ $material->stage->title }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                @if($material->content_markdown)
                                    <span class="badge badge-soft-primary">
                                        <i class="bi bi-markdown me-1"></i>MD
                                    </span>
                                @endif
                                @if($material->content_text)
                                    <span class="badge badge-soft-secondary">
                                        <i class="bi bi-text-paragraph me-1"></i>Text
                                    </span>
                                @endif
                                @if($material->image_url)
                                    <span class="badge badge-soft-success">
                                        <i class="bi bi-image me-1"></i>Img
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.materials.edit', $material->id) }}" 
                                   class="btn btn-sm btn-icon btn-outline-primary" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-icon btn-outline-danger" 
                                        data-bs-toggle="tooltip" 
                                        title="Hapus"
                                        onclick="confirmDelete('/admin/materials/{{ $material->id }}', 'Materi &quot;{{ $material->title }}&quot; akan dihapus permanen.')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-journal-richtext"></i>
                                </div>
                                <h5>Belum ada materi</h5>
                                <p>Tambahkan materi pembelajaran untuk stage.</p>
                                <a href="{{ route('admin.materials.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Tambah Materi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($materials->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
            {{ $materials->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
