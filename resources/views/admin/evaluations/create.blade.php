@extends('admin.layout.app')

@section('title', 'Tambah Evaluasi Baru')
@section('page-title', 'Tambah Evaluasi Baru')
@section('page-subtitle', 'Buat evaluasi menggambar aksara Jawa')

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
    .form-hint i {
        font-size: 0.9rem;
    }
    .character-preview {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px dashed #d1d5db;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .character-preview.has-content {
        border-style: solid;
        border-color: #9b59b6;
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
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
    .image-preview-zone {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border: 2px dashed #d1d5db;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .image-preview-zone.has-image {
        border-style: solid;
        border-color: #9b59b6;
        background: #fff;
    }
    .image-preview-zone img {
        max-width: 200px;
        max-height: 150px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .image-preview-zone .placeholder-icon {
        font-size: 3rem;
        color: #d1d5db;
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
    .info-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .info-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    .info-card-header h6 {
        margin: 0;
        font-weight: 700;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-card-header h6 i {
        color: #9b59b6;
    }
    .info-card-body {
        padding: 24px;
    }
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .info-item:first-child {
        padding-top: 0;
    }
    .info-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .info-icon.purple {
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        color: #7c3aed;
    }
    .info-icon.blue {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #3b82f6;
    }
    .info-icon.green {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        color: #10b981;
    }
    .info-content h6 {
        margin: 0 0 6px 0;
        font-weight: 600;
        color: #374151;
    }
    .info-content p {
        margin: 0;
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.5;
    }
    .tips-card {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: none;
        border-radius: 16px;
        padding: 20px;
    }
    .tips-card h6 {
        color: #92400e;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }
    .tips-card ul {
        margin: 0;
        padding-left: 20px;
    }
    .tips-card li {
        font-size: 0.875rem;
        color: #92400e;
        margin-bottom: 6px;
    }
    .tips-card li:last-child {
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="form-card-header">
                <h5><i class="bi bi-pencil-square me-2"></i>Buat Evaluasi Baru</h5>
                <p>Evaluasi menggambar aksara Jawa untuk menguji kemampuan siswa</p>
            </div>
            <div class="form-card-body">
                <form action="{{ route('admin.evaluations.store') }}" method="POST">
                    @csrf
                    
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
                            <option value="">-- Pilih Stage --</option>
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id', request('stage_id')) == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->title }} (Level {{ $stage->level->level_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('stage_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">
                            <i class="bi bi-info-circle"></i>
                            Stage tempat evaluasi ini akan muncul
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <div class="mb-4">
                        <label class="modern-label">
                            <i class="bi bi-card-heading"></i>
                            Judul
                        </label>
                        <input type="text" 
                               class="form-control modern-input @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               maxlength="255"
                               placeholder="Contoh: Menggambar Aksara Ha">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">
                            <i class="bi bi-info-circle"></i>
                            Judul challenge yang akan ditampilkan ke user (opsional)
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <label class="modern-label">
                            <i class="bi bi-text-paragraph"></i>
                            Deskripsi
                        </label>
                        <textarea class="form-control modern-input @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  maxlength="1000"
                                  placeholder="Contoh: Gambar aksara Ha dengan benar sesuai dengan referensi yang diberikan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">
                            <i class="bi bi-info-circle"></i>
                            Deskripsi atau instruksi untuk user (opsional, maksimal 1000 karakter)
                        </div>
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
                                       value="{{ old('character_target') }}"
                                       maxlength="10"
                                       placeholder="ꦲ atau ha"
                                       required>
                                @error('character_target')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">
                                    <i class="bi bi-pencil"></i>
                                    Karakter aksara Jawa yang harus digambar oleh user
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="character-preview" id="characterPreview">
                                    <span class="aksara-display" id="aksaraDisplay">ꦲ</span>
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
                        <input type="url" 
                               class="form-control modern-input @error('reference_image_url') is-invalid @enderror" 
                               id="reference_image_url" 
                               name="reference_image_url" 
                               value="{{ old('reference_image_url') }}"
                               placeholder="https://example.com/aksara-ha.png"
                               required>
                        @error('reference_image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div class="image-preview-zone mt-3" id="imagePreviewZone">
                            <i class="bi bi-image placeholder-icon"></i>
                            <p class="text-muted mb-0 mt-2">Preview gambar referensi</p>
                            <small class="text-muted">Masukkan URL gambar untuk melihat preview</small>
                        </div>
                    </div>
                    
                    <!-- Similarity Score -->
                    <div class="mb-4">
                        <label class="modern-label">
                            <i class="bi bi-bullseye"></i>
                            Minimum Similarity Score <span class="text-danger">*</span>
                        </label>
                        
                        <div class="score-slider-container">
                            <div class="score-display"><span id="scoreValue">70</span>%</div>
                            <div class="score-label mb-3">Batas minimum kesamaan untuk lulus</div>
                            <input type="range" 
                                   class="score-range" 
                                   id="score_slider"
                                   min="0"
                                   max="100"
                                   step="1"
                                   value="{{ old('min_similarity_score', 70) }}">
                            <input type="hidden" 
                                   id="min_similarity_score" 
                                   name="min_similarity_score" 
                                   value="{{ old('min_similarity_score', 70) }}">
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
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="bi bi-check-circle me-2"></i>Simpan Evaluasi
                        </button>
                        <a href="{{ route('admin.evaluations.index') }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Info Card -->
        <div class="card info-card mb-4">
            <div class="info-card-header">
                <h6><i class="bi bi-lightbulb"></i>Panduan Evaluasi</h6>
            </div>
            <div class="info-card-body">
                <div class="info-item">
                    <div class="info-icon purple">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div class="info-content">
                        <h6>Evaluasi Menggambar</h6>
                        <p>Menguji kemampuan siswa dalam menulis aksara Jawa dengan benar melalui gambar</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon blue">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <div class="info-content">
                        <h6>Computer Vision</h6>
                        <p>Sistem membandingkan gambar user dengan referensi menggunakan algoritma AI</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon green">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="info-content">
                        <h6>Similarity Score</h6>
                        <p>Skor kesamaan dihitung otomatis berdasarkan bentuk dan struktur aksara</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tips Card -->
        <div class="tips-card">
            <h6><i class="bi bi-stars"></i>Tips & Rekomendasi</h6>
            <ul>
                <li>Gunakan gambar referensi beresolusi tinggi (min. 200x200px)</li>
                <li>Background putih lebih mudah diproses oleh AI</li>
                <li>Set similarity score 60-70% untuk pemula</li>
                <li>Set 80-90% untuk level mahir</li>
                <li>Pastikan garis aksara jelas dan tebal</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character Preview
    const characterInput = document.getElementById('character_target');
    const aksaraDisplay = document.getElementById('aksaraDisplay');
    const characterPreview = document.getElementById('characterPreview');
    
    characterInput.addEventListener('input', function() {
        const value = this.value.trim();
        if (value) {
            aksaraDisplay.textContent = value;
            characterPreview.classList.add('has-content');
        } else {
            aksaraDisplay.textContent = 'ꦲ';
            characterPreview.classList.remove('has-content');
        }
    });
    
    // Initial check
    if (characterInput.value.trim()) {
        aksaraDisplay.textContent = characterInput.value.trim();
        characterPreview.classList.add('has-content');
    }
    
    // Image Preview
    const imageUrlInput = document.getElementById('reference_image_url');
    const imagePreviewZone = document.getElementById('imagePreviewZone');
    let debounceTimer;
    
    imageUrlInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const url = this.value.trim();
            if (url) {
                const img = new Image();
                img.onload = function() {
                    imagePreviewZone.innerHTML = '<img src="' + url + '" alt="Preview">';
                    imagePreviewZone.classList.add('has-image');
                };
                img.onerror = function() {
                    imagePreviewZone.innerHTML = '<i class="bi bi-exclamation-triangle placeholder-icon text-warning"></i><p class="text-warning mb-0 mt-2">Gambar tidak dapat dimuat</p><small class="text-muted">Periksa kembali URL gambar</small>';
                    imagePreviewZone.classList.remove('has-image');
                };
                img.src = url;
            } else {
                imagePreviewZone.innerHTML = '<i class="bi bi-image placeholder-icon"></i><p class="text-muted mb-0 mt-2">Preview gambar referensi</p><small class="text-muted">Masukkan URL gambar untuk melihat preview</small>';
                imagePreviewZone.classList.remove('has-image');
            }
        }, 500);
    });
    
    // Initial image check
    if (imageUrlInput.value.trim()) {
        imageUrlInput.dispatchEvent(new Event('input'));
    }
    
    // Score Slider
    const scoreSlider = document.getElementById('score_slider');
    const scoreValue = document.getElementById('scoreValue');
    const scoreHidden = document.getElementById('min_similarity_score');
    
    scoreSlider.addEventListener('input', function() {
        scoreValue.textContent = this.value;
        scoreHidden.value = this.value;
    });
});
</script>
@endpush
