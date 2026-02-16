@extends('admin.layout.app')

@section('title', 'Edit Evaluasi')
@section('page-title', 'Edit Evaluasi')
@section('page-subtitle', 'Perbarui evaluasi menggambar aksara')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .form-card-header {
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        color: white;
        padding: 28px 32px;
        border: none;
    }
    .form-card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.35rem;
    }
    .form-card-header p {
        margin: 8px 0 0 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }
    .form-card-body {
        padding: 32px;
    }
    .modern-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modern-label i {
        color: #9b59b6;
        font-size: 1.1rem;
    }
    .modern-input {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .modern-input:focus {
        border-color: #9b59b6;
        box-shadow: 0 0 0 4px rgba(155, 89, 182, 0.1);
    }
    .modern-select {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-position: right 18px center;
    }
    .modern-select:focus {
        border-color: #9b59b6;
        box-shadow: 0 0 0 4px rgba(155, 89, 182, 0.1);
    }
    .form-hint {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .character-preview {
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        border: 2px solid #9b59b6;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .character-preview .aksara-display {
        font-size: 3.5rem;
        font-weight: 700;
        color: #7c3aed;
        line-height: 1;
    }
    .character-preview small {
        color: #6b7280;
        margin-top: 8px;
    }
    .current-image-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #9b59b6;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
    }
    .current-image-card img {
        max-width: 200px;
        max-height: 150px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .current-image-card .label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 12px;
        font-weight: 600;
        color: #7c3aed;
    }
    .score-slider-container {
        background: linear-gradient(135deg, #faf5ff 0%, #f5f3ff 100%);
        border-radius: 16px;
        padding: 24px;
        margin-top: 8px;
    }
    .score-display {
        font-size: 2.5rem;
        font-weight: 700;
        color: #7c3aed;
        text-align: center;
    }
    .score-label {
        text-align: center;
        color: #6b7280;
        font-size: 0.9rem;
    }
    .score-range {
        width: 100%;
        height: 8px;
        border-radius: 4px;
        background: #e5e7eb;
        -webkit-appearance: none;
        appearance: none;
        cursor: pointer;
    }
    .score-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(155, 89, 182, 0.4);
    }
    .score-hints {
        display: flex;
        justify-content: space-between;
        margin-top: 12px;
        font-size: 0.8rem;
        color: #9ca3af;
    }
    .btn-submit {
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        border: none;
        padding: 14px 32px;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(155, 89, 182, 0.35);
    }
    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        border: none;
        padding: 14px 32px;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background: #e5e7eb;
        color: #1f2937;
    }
    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        padding: 14px 32px;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.35);
    }
    .stats-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .stats-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    .stats-card-header h6 {
        margin: 0;
        font-weight: 700;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .stats-card-header h6 i {
        color: #9b59b6;
    }
    .stats-card-body {
        padding: 24px;
    }
    .stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .stat-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .stat-item:first-child {
        padding-top: 0;
    }
    .stat-item .label {
        color: #6b7280;
        font-size: 0.9rem;
    }
    .stat-item .value {
        font-weight: 700;
        font-size: 1.1rem;
    }
    .stat-item .value.success { color: #10b981; }
    .stat-item .value.danger { color: #ef4444; }
    .stat-item .value.primary { color: #7c3aed; }
    .submissions-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .submissions-card-header {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 20px 24px;
        border-bottom: 1px solid #bbf7d0;
    }
    .submissions-card-header h6 {
        margin: 0;
        font-weight: 700;
        color: #166534;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .submissions-card-body {
        padding: 0;
    }
    .submission-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }
    .submission-item:hover {
        background: #f9fafb;
    }
    .submission-item:last-child {
        border-bottom: none;
    }
    .submission-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7c3aed;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .submission-drawing {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }
    .submission-info {
        flex: 1;
    }
    .submission-info .name {
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }
    .submission-info .date {
        font-size: 0.8rem;
        color: #9ca3af;
    }
    .submission-score {
        text-align: right;
    }
    .submission-score .score-value {
        font-weight: 700;
        font-size: 1.1rem;
    }
    .submission-score .score-value.passed { color: #10b981; }
    .submission-score .score-value.failed { color: #ef4444; }
    .submission-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .submission-status.passed {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        color: #059669;
    }
    .submission-status.failed {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        color: #dc2626;
    }
    .submission-status.pending {
        background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%);
        color: #ca8a04;
    }
    .empty-submissions {
        padding: 48px 24px;
        text-align: center;
    }
    .empty-submissions i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 16px;
    }
    .empty-submissions p {
        color: #6b7280;
        margin: 0;
    }
    .quick-links {
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        padding: 20px;
    }
    .quick-links h6 {
        color: #1e40af;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }
    .quick-link-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: white;
        border-radius: 10px;
        text-decoration: none;
        color: #374151;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .quick-link-item:last-child {
        margin-bottom: 0;
    }
    .quick-link-item:hover {
        transform: translateX(4px);
        color: #1e40af;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .quick-link-item i {
        color: #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Form Card -->
        <div class="card form-card mb-4">
            <div class="form-card-header">
                <h5><i class="bi bi-pencil-square me-2"></i>Edit Evaluasi</h5>
                <p>Perbarui evaluasi menggambar aksara "{{ $evaluation->character_target }}"</p>
            </div>
            <div class="form-card-body">
                <form action="{{ route('admin.evaluations.update', $evaluation->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Stage Selection -->
                    <div class="mb-4">
                        <label class="modern-label">
                            <i class="bi bi-diagram-3"></i>
                            Pilih Stage <span class="text-danger">*</span>
                        </label>
                        <select class="form-select modern-select @error('stage_id') is-invalid @enderror" 
                                id="stage_id" 
                                name="stage_id" 
                                required>
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id', $evaluation->stage_id) == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->title }} (Level {{ $stage->level->level_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('stage_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Character Target -->
                    <div class="mb-4">
                        <label class="modern-label">
                            <i class="bi bi-fonts"></i>
                            Karakter Target Aksara Jawa <span class="text-danger">*</span>
                        </label>
                        <div class="row">
                            <div class="col-md-7">
                                <input type="text" 
                                       class="form-control modern-input @error('character_target') is-invalid @enderror" 
                                       id="character_target" 
                                       name="character_target" 
                                       value="{{ old('character_target', $evaluation->character_target) }}"
                                       maxlength="10"
                                       required>
                                @error('character_target')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">
                                    <i class="bi bi-pencil"></i>
                                    Karakter aksara Jawa yang harus digambar
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="character-preview" id="characterPreview">
                                    <span class="aksara-display" id="aksaraDisplay">{{ $evaluation->character_target }}</span>
                                    <small>Preview karakter</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reference Image URL -->
                    <div class="mb-4">
                        <label class="modern-label">
                            <i class="bi bi-image"></i>
                            URL Gambar Referensi <span class="text-danger">*</span>
                        </label>
                        
                        @if($evaluation->reference_image_url)
                            <div class="current-image-card mb-3">
                                <div class="label">
                                    <i class="bi bi-image-fill"></i>
                                    Gambar Referensi Saat Ini
                                </div>
                                <img src="{{ $evaluation->reference_image_url }}" alt="Reference">
                            </div>
                        @endif
                        
                        <input type="url" 
                               class="form-control modern-input @error('reference_image_url') is-invalid @enderror" 
                               id="reference_image_url" 
                               name="reference_image_url" 
                               value="{{ old('reference_image_url', $evaluation->reference_image_url) }}"
                               required>
                        @error('reference_image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">
                            <i class="bi bi-link-45deg"></i>
                            Ubah URL untuk mengganti gambar referensi
                        </div>
                    </div>
                    
                    <!-- Similarity Score -->
                    <div class="mb-4">
                        <label class="modern-label">
                            <i class="bi bi-bullseye"></i>
                            Minimum Similarity Score <span class="text-danger">*</span>
                        </label>
                        
                        <div class="score-slider-container">
                            <div class="score-display"><span id="scoreValue">{{ intval($evaluation->min_similarity_score) }}</span>%</div>
                            <div class="score-label mb-3">Batas minimum kesamaan untuk lulus</div>
                            <input type="range" 
                                   class="score-range" 
                                   id="score_slider"
                                   min="0"
                                   max="100"
                                   step="1"
                                   value="{{ old('min_similarity_score', $evaluation->min_similarity_score) }}">
                            <input type="hidden" 
                                   id="min_similarity_score" 
                                   name="min_similarity_score" 
                                   value="{{ old('min_similarity_score', $evaluation->min_similarity_score) }}">
                            <div class="score-hints">
                                <span>0% (Sangat Mudah)</span>
                                <span>50%</span>
                                <span>100% (Sempurna)</span>
                            </div>
                        </div>
                        @error('min_similarity_score')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="bi bi-check-circle me-2"></i>Update Evaluasi
                        </button>
                        <a href="{{ route('admin.evaluations.index') }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="button" class="btn btn-danger btn-delete ms-auto" 
                                onclick="confirmDelete('{{ route('admin.evaluations.destroy', $evaluation->id) }}', 'Yakin ingin menghapus evaluasi ini? Semua submission terkait juga akan dihapus.')">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Submissions Card -->
        <div class="card submissions-card">
            <div class="submissions-card-header">
                <h6>
                    <i class="bi bi-images"></i>
                    Submission User
                    <span class="badge bg-success ms-2">{{ $evaluation->submissions->count() }}</span>
                </h6>
            </div>
            <div class="submissions-card-body">
                @if($evaluation->submissions->count() > 0)
                    @foreach($evaluation->submissions->take(10) as $submission)
                        <div class="submission-item">
                            <div class="submission-avatar">
                                {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                            </div>
                            @if($submission->drawing_image_url)
                                <img src="{{ $submission->drawing_image_url }}" alt="Drawing" class="submission-drawing">
                            @endif
                            <div class="submission-info">
                                <div class="name">{{ $submission->user->name }}</div>
                                <div class="date">{{ $submission->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="submission-score">
                                <div class="score-value {{ $submission->status === 'passed' ? 'passed' : 'failed' }}">
                                    {{ number_format($submission->similarity_score, 1) }}%
                                </div>
                                <span class="submission-status {{ $submission->status }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                    @if($evaluation->submissions->count() > 10)
                        <div class="text-center py-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            Menampilkan 10 dari {{ $evaluation->submissions->count() }} submission
                        </div>
                    @endif
                @else
                    <div class="empty-submissions">
                        <i class="bi bi-inbox"></i>
                        <p>Belum ada submission untuk evaluasi ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Stats Card -->
        <div class="card stats-card mb-4">
            <div class="stats-card-header">
                <h6><i class="bi bi-bar-chart-fill"></i>Statistik Evaluasi</h6>
            </div>
            <div class="stats-card-body">
                <div class="stat-item">
                    <span class="label">Stage</span>
                    <span class="value primary">{{ $evaluation->stage->title }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Level</span>
                    <span class="value">Level {{ $evaluation->stage->level->level_number }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Total Submissions</span>
                    <span class="value primary">{{ $evaluation->submissions->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Lulus</span>
                    <span class="value success">{{ $evaluation->submissions->where('status', 'passed')->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Gagal</span>
                    <span class="value danger">{{ $evaluation->submissions->where('status', 'failed')->count() }}</span>
                </div>
                @php
                    $passRate = $evaluation->submissions->count() > 0 
                        ? ($evaluation->submissions->where('status', 'passed')->count() / $evaluation->submissions->count()) * 100 
                        : 0;
                @endphp
                <div class="stat-item">
                    <span class="label">Tingkat Kelulusan</span>
                    <span class="value {{ $passRate >= 60 ? 'success' : 'danger' }}">{{ number_format($passRate, 1) }}%</span>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="quick-links">
            <h6><i class="bi bi-lightning-charge"></i>Navigasi Cepat</h6>
            <a href="{{ route('admin.stages.edit', $evaluation->stage_id) }}" class="quick-link-item">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Stage</span>
            </a>
            <a href="{{ route('admin.levels.edit', $evaluation->stage->level_id) }}" class="quick-link-item">
                <i class="bi bi-layers"></i>
                <span>Lihat Level</span>
            </a>
            <a href="{{ route('admin.evaluations.index') }}" class="quick-link-item">
                <i class="bi bi-grid"></i>
                <span>Semua Evaluasi</span>
            </a>
            <a href="{{ route('admin.evaluations.create', ['stage_id' => $evaluation->stage_id]) }}" class="quick-link-item">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Evaluasi Baru</span>
            </a>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character Preview
    const characterInput = document.getElementById('character_target');
    const aksaraDisplay = document.getElementById('aksaraDisplay');
    
    characterInput.addEventListener('input', function() {
        const value = this.value.trim();
        aksaraDisplay.textContent = value || '{{ $evaluation->character_target }}';
    });
    
    // Score Slider
    const scoreSlider = document.getElementById('score_slider');
    const scoreValue = document.getElementById('scoreValue');
    const scoreHidden = document.getElementById('min_similarity_score');
    
    scoreSlider.addEventListener('input', function() {
        scoreValue.textContent = this.value;
        scoreHidden.value = this.value;
    });
});

// Confirm Delete Function
function confirmDelete(url, message) {
    if (confirm(message)) {
        const form = document.getElementById('delete-form');
        form.action = url;
        form.submit();
    }
}
</script>
@endpush
