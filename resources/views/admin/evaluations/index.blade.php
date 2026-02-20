@extends('admin.layout.app')

@section('title', 'Kelola Evaluasi')
@section('page-title', 'Kelola Evaluasi')
@section('page-subtitle', 'Daftar semua evaluasi menggambar aksara')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-brush-fill"></i>
            <span>Daftar Evaluasi</span>
            <span class="badge badge-soft-primary ms-2">{{ $evaluations->total() ?? $evaluations->count() }} total</span>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <select class="form-select form-select-sm" style="width: 220px;" onchange="window.location.href = this.value;">
                <option value="{{ route('admin.evaluations.index') }}" {{ !request('stage_id') ? 'selected' : '' }}>📁 Semua Stage</option>
                @foreach($stages as $stage)
                    <option value="{{ route('admin.evaluations.index', ['stage_id' => $stage->id]) }}" 
                            {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                        {{ Str::limit($stage->title, 25) }} (L{{ $stage->level->level_number }})
                    </option>
                @endforeach
            </select>
            <a href="{{ route('admin.evaluations.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Evaluasi</span>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th style="width: 100px;">Referensi</th>
                        <th style="width: 100px;">Karakter</th>
                        <th style="width: 200px;">Judul</th>
                        <th style="width: 250px;">Deskripsi</th>
                        <th>Stage</th>
                        <th style="width: 140px;">Min. Similarity</th>
                        <th style="width: 120px;">Submissions</th>
                        <th style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $evaluation)
                    <tr>
                        <td>
                            <span class="text-muted fw-medium">#{{ $evaluation->id }}</span>
                        </td>
                        <td>
                            @if($evaluation->reference_image_url)
                                <img src="{{ $evaluation->reference_image_url }}" 
                                     class="rounded" 
                                     style="width: 56px; height: 56px; object-fit: contain; background: #f8f9fc; padding: 4px; border: 2px solid #e9ecef;">
                            @else
                                <div class="avatar bg-light text-muted">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="fs-2" style="font-family: 'Noto Sans Javanese', sans-serif;">{{ $evaluation->character_target }}</span>
                            </div>
                        </td>
                        <td>
                            @if($evaluation->title)
                                <div class="fw-semibold text-truncate" style="max-width: 200px;" title="{{ $evaluation->title }}">
                                    {{ $evaluation->title }}
                                </div>
                            @else
                                <span class="text-muted fst-italic">-</span>
                            @endif
                        </td>
                        <td>
                            @if($evaluation->description)
                                <div class="text-muted text-truncate" style="max-width: 250px;" title="{{ $evaluation->description }}">
                                    {{ $evaluation->description }}
                                </div>
                            @else
                                <span class="text-muted fst-italic">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-soft-info">
                                <i class="bi bi-collection me-1"></i>{{ $evaluation->stage->title }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px; width: 70px;">
                                    <div class="progress-bar bg-success" style="width: {{ $evaluation->min_similarity_score }}%"></div>
                                </div>
                                <span class="fw-semibold text-success">≥{{ $evaluation->min_similarity_score }}%</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-soft-primary">
                                <i class="bi bi-send me-1"></i>{{ $evaluation->submissions_count ?? 0 }} submisi
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.evaluations.show', $evaluation->id) }}" 
                                   class="btn btn-sm btn-icon btn-outline-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.evaluations.edit', $evaluation->id) }}" 
                                   class="btn btn-sm btn-icon btn-outline-primary" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-icon btn-outline-danger" 
                                        data-bs-toggle="tooltip" 
                                        title="Hapus"
                                        onclick="confirmDelete('/admin/evaluations/{{ $evaluation->id }}', 'Evaluasi dan semua submission user akan dihapus.')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-brush"></i>
                                </div>
                                <h5>Belum ada evaluasi</h5>
                                <p>Tambahkan evaluasi menggambar aksara untuk stage.</p>
                                <a href="{{ route('admin.evaluations.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Tambah Evaluasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($evaluations->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
            {{ $evaluations->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
