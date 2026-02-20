@extends('admin.layout.app')

@section('title', 'Detail Evaluasi')
@section('page-title', 'Detail Evaluasi')
@section('page-subtitle', 'Informasi lengkap evaluasi menggambar aksara')

@push('styles')
<style>
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .detail-card-header {
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        color: white;
        padding: 24px 28px;
        border: none;
    }
    .detail-card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .detail-card-body {
        padding: 28px;
    }
    .detail-item {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    .detail-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .detail-label {
        font-weight: 600;
        color: #6b7280;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-value {
        font-size: 1.1rem;
        color: #1f2937;
        font-weight: 500;
    }
    .detail-value.empty {
        color: #9ca3af;
        font-style: italic;
    }
    .character-display {
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        border: 3px solid #9b59b6;
        border-radius: 20px;
        padding: 32px;
        text-align: center;
    }
    .character-display .aksara {
        font-size: 5rem;
        font-weight: 700;
        color: #7c3aed;
        line-height: 1;
        font-family: 'Noto Sans Javanese', sans-serif;
    }
    .reference-image-display {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 3px solid #9b59b6;
        border-radius: 20px;
        padding: 24px;
        text-align: center;
    }
    .reference-image-display img {
        max-width: 300px;
        max-height: 300px;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
    }
    .stat-badge i {
        font-size: 1.2rem;
    }
    .submissions-table {
        margin-top: 20px;
    }
    .submissions-table td, .submissions-table th {
        padding: 14px;
        vertical-align: middle;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    .description-text {
        background: #f9fafb;
        border-left: 4px solid #9b59b6;
        padding: 16px 20px;
        border-radius: 8px;
        font-size: 1rem;
        line-height: 1.7;
        color: #374151;
    }
</style>
@endpush

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.evaluations.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
    </a>
    <a href="{{ route('admin.evaluations.edit', $evaluation->id) }}" class="btn btn-primary">
        <i class="bi bi-pencil me-2"></i>Edit Evaluasi
    </a>
</div>

<!-- Main Info Card -->
<div class="detail-card">
    <div class="detail-card-header">
        <h5>
            <i class="bi bi-info-circle-fill"></i>
            Informasi Evaluasi
        </h5>
    </div>
    <div class="detail-card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="bi bi-hash text-primary"></i>
                        ID Evaluasi
                    </div>
                    <div class="detail-value">#{{ $evaluation->id }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <i class="bi bi-bookmark text-primary"></i>
                        Judul Evaluasi
                    </div>
                    <div class="detail-value {{ $evaluation->title ? '' : 'empty' }}">
                        {{ $evaluation->title ?? 'Belum ada judul' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <i class="bi bi-collection text-primary"></i>
                        Stage
                    </div>
                    <div class="detail-value">
                        <span class="badge badge-soft-info" style="font-size: 1rem; padding: 8px 16px;">
                            {{ $evaluation->stage->title ?? 'N/A' }} 
                            (Level {{ $evaluation->stage->level->level_number ?? 'N/A' }})
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <i class="bi bi-graph-up text-success"></i>
                        Minimum Similarity Score
                    </div>
                    <div class="detail-value">
                        <span class="stat-badge bg-success text-white">
                            <i class="bi bi-check-circle-fill"></i>
                            ≥{{ $evaluation->min_similarity_score }}%
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <i class="bi bi-send text-primary"></i>
                        Total Submissions
                    </div>
                    <div class="detail-value">
                        <span class="stat-badge bg-primary text-white">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            {{ $evaluation->submissions_count ?? 0 }} submisi
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <i class="bi bi-calendar-plus text-muted"></i>
                        Dibuat Pada
                    </div>
                    <div class="detail-value">{{ $evaluation->created_at?->format('d M Y, H:i') ?? 'N/A' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <i class="bi bi-calendar-check text-muted"></i>
                        Terakhir Diupdate
                    </div>
                    <div class="detail-value">{{ $evaluation->updated_at?->format('d M Y, H:i') ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Character Display -->
                <div class="character-display mb-4">
                    <div class="detail-label justify-content-center mb-3">
                        <i class="bi bi-fonts text-primary"></i>
                        Karakter Target
                    </div>
                    <div class="aksara">{{ $evaluation->character_target }}</div>
                    <small class="text-muted mt-2 d-block">Aksara Jawa</small>
                </div>

                <!-- Reference Image -->
                @if($evaluation->reference_image_url)
                <div class="reference-image-display">
                    <div class="detail-label justify-content-center mb-3">
                        <i class="bi bi-image text-primary"></i>
                        Gambar Referensi
                    </div>
                    <img src="{{ $evaluation->reference_image_url }}" alt="Reference Image">
                    <div class="mt-3">
                        <a href="{{ $evaluation->reference_image_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Buka di Tab Baru
                        </a>
                    </div>
                </div>
                @else
                <div class="reference-image-display">
                    <div class="detail-label justify-content-center mb-3">
                        <i class="bi bi-image text-primary"></i>
                        Gambar Referensi
                    </div>
                    <div class="text-muted">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                        <p class="mt-2">Tidak ada gambar referensi</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Description Section -->
        @if($evaluation->description)
        <div class="mt-4">
            <div class="detail-label">
                <i class="bi bi-file-text text-primary"></i>
                Deskripsi
            </div>
            <div class="description-text">
                {{ $evaluation->description }}
            </div>
        </div>
        @else
        <div class="mt-4">
            <div class="detail-label">
                <i class="bi bi-file-text text-primary"></i>
                Deskripsi
            </div>
            <div class="description-text text-muted" style="font-style: italic;">
                Belum ada deskripsi
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Recent Submissions Card -->
@if($evaluation->submissions && $evaluation->submissions->isNotEmpty())
<div class="detail-card">
    <div class="detail-card-header">
        <h5>
            <i class="bi bi-clock-history"></i>
            Submissions Terbaru
        </h5>
    </div>
    <div class="detail-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 submissions-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Gambar</th>
                        <th>Similarity Score</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluation->submissions->take(10) as $submission)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar bg-primary text-white">
                                    {{ strtoupper(substr($submission->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $submission->user->name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $submission->user->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($submission->user_drawing_url)
                                <a href="{{ $submission->user_drawing_url }}" target="_blank">
                                    <img src="{{ $submission->user_drawing_url }}" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #e9ecef;">
                                </a>
                            @else
                                <div class="text-muted">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="height: 8px; width: 80px;">
                                    <div class="progress-bar {{ $submission->similarity_score >= $evaluation->min_similarity_score ? 'bg-success' : 'bg-warning' }}" 
                                         style="width: {{ $submission->similarity_score }}%"></div>
                                </div>
                                <span class="fw-semibold">{{ number_format($submission->similarity_score, 1) }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($submission->similarity_score >= $evaluation->min_similarity_score)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Lulus
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-x-circle me-1"></i>Tidak Lulus
                                </span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $submission->created_at?->diffForHumans() ?? 'N/A' }}</small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($evaluation->submissions->count() > 10)
        <div class="p-3 text-center border-top">
            <small class="text-muted">Menampilkan 10 dari {{ $evaluation->submissions->count() }} submissions</small>
        </div>
        @endif
    </div>
</div>
@else
<div class="detail-card">
    <div class="detail-card-header">
        <h5>
            <i class="bi bi-clock-history"></i>
            Submissions Terbaru
        </h5>
    </div>
    <div class="detail-card-body text-center py-5">
        <i class="bi bi-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
        <p class="text-muted mt-3">Belum ada submission dari user</p>
    </div>
</div>
@endif

<!-- Action Buttons -->
<div class="mt-4 d-flex gap-2">
    <a href="{{ route('admin.evaluations.edit', $evaluation->id) }}" class="btn btn-primary">
        <i class="bi bi-pencil me-2"></i>Edit Evaluasi
    </a>
    <button type="button" 
            class="btn btn-danger" 
            onclick="confirmDelete('/admin/evaluations/{{ $evaluation->id }}', 'Evaluasi dan semua submission user akan dihapus.')">
        <i class="bi bi-trash3 me-2"></i>Hapus Evaluasi
    </button>
    <a href="{{ route('admin.evaluations.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>
@endsection
