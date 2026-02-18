@extends('admin.layout.app')

@section('title', 'Tambah Badge Baru')
@section('page-title', 'Tambah Badge Baru')
@section('page-subtitle', 'Buat badge penghargaan untuk user')

@push('styles')
<style>
    .form-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        color: white;
        border-radius: 16px 16px 0 0 !important;
        padding: 20px 24px;
    }
    .info-card {
        border-radius: 16px;
        border: 1px solid #e9ecef;
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
    }
    .info-card .card-header {
        background: transparent;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
    }
    .form-label {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 8px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #f7971e;
        box-shadow: 0 0 0 3px rgba(247, 151, 30, 0.15);
    }
    .form-text {
        color: #6c757d;
        font-size: 0.8rem;
        margin-top: 6px;
    }
    .btn-submit {
        background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        border: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 10px;
        color: #fff;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(247, 151, 30, 0.4);
        color: #fff;
    }
    .emoji-picker {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-top: 8px;
    }
    .emoji-picker span {
        font-size: 1.5rem;
        cursor: pointer;
        padding: 4px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .emoji-picker span:hover {
        background: #e9ecef;
        transform: scale(1.2);
    }
    .preview-box {
        background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        border: 2px solid #ffd200;
    }
    .preview-icon {
        font-size: 3rem;
        margin-bottom: 12px;
    }
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .info-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.85rem;
    }
    .info-list li:last-child {
        border-bottom: none;
    }
    .info-list strong {
        color: #f7971e;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.badges.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Badge
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill"></i>
                    <span class="fw-semibold">Form Tambah Badge</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.badges.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Nama Badge <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               maxlength="50"
                               placeholder="contoh: Master Aksara"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="icon" class="form-label">
                            Gambar Icon Badge <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                               class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" 
                               name="icon"
                               accept="image/*"
                               onchange="previewIcon(this)"
                               required>
                        <div class="form-text">Format: JPG, PNG, GIF, SVG, WEBP. Maksimal 2MB</div>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="iconPreview" class="mt-3" style="display: none;">
                            <img id="iconPreviewImg" src="" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 12px; border: 2px solid #e9ecef;">
                        </div>
                    </div>
                    
                    <div class="mb-4 mt-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Deskripsi singkat tentang badge ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="criteria_type" class="form-label">
                                Tipe Kriteria <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('criteria_type') is-invalid @enderror" 
                                    id="criteria_type" 
                                    name="criteria_type" 
                                    required>
                                <option value="">Pilih Tipe...</option>
                                <option value="xp_milestone" {{ old('criteria_type') == 'xp_milestone' ? 'selected' : '' }}>XP Milestone (Total XP)</option>
                                <option value="streak" {{ old('criteria_type') == 'streak' ? 'selected' : '' }}>Streak (Hari Berturut-turut)</option>
                                <option value="level_complete" {{ old('criteria_type') == 'level_complete' ? 'selected' : '' }}>Level Complete</option>
                                <option value="custom" {{ old('criteria_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                            @error('criteria_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="criteria_value" class="form-label">Nilai Kriteria</label>
                            <input type="number" 
                                   class="form-control @error('criteria_value') is-invalid @enderror" 
                                   id="criteria_value" 
                                   name="criteria_value" 
                                   value="{{ old('criteria_value') }}"
                                   min="0"
                                   placeholder="contoh: 1000">
                            @error('criteria_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Jumlah yang harus dicapai</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="xp_reward" class="form-label">
                            XP Reward <span class="text-danger">*</span>
                        </label>
                        <div class="input-group" style="max-width: 200px;">
                            <span class="input-group-text">+</span>
                            <input type="number" 
                                   class="form-control @error('xp_reward') is-invalid @enderror" 
                                   id="xp_reward" 
                                   name="xp_reward" 
                                   value="{{ old('xp_reward', 100) }}"
                                   min="0"
                                   required>
                            <span class="input-group-text">XP</span>
                        </div>
                        @error('xp_reward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Bonus XP saat mendapat badge</div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-lg me-2"></i>Simpan Badge
                        </button>
                        <a href="{{ route('admin.badges.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card info-card mb-3">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-eye text-primary"></i>
                    <span class="fw-semibold">Preview Badge</span>
                </div>
            </div>
            <div class="card-body">
                <div class="preview-box">
                    <div class="preview-icon" id="preview-icon">
                        <img src="" alt="Badge Icon" style="max-width: 100px; max-height: 100px; display: none;" id="preview-icon-img">
                        <div id="preview-icon-placeholder" style="font-size: 3rem; color: #ccc;">📷</div>
                    </div>
                    <h5 class="fw-bold mb-1" id="preview-name">Nama Badge</h5>
                    <p class="text-muted small mb-0" id="preview-desc">Deskripsi badge</p>
                </div>
            </div>
        </div>
        
        <div class="card info-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-lightbulb text-warning"></i>
                    <span class="fw-semibold">Tipe Kriteria</span>
                </div>
            </div>
            <div class="card-body">
                <ul class="info-list">
                    <li><strong>xp_milestone:</strong> Total XP dikumpulkan</li>
                    <li><strong>streak:</strong> Hari berturut belajar</li>
                    <li><strong>level_complete:</strong> Level yang diselesaikan</li>
                    <li><strong>custom:</strong> Kriteria khusus lainnya</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewIcon(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Preview dalam form
                document.getElementById('iconPreview').style.display = 'block';
                document.getElementById('iconPreviewImg').src = e.target.result;
                
                // Preview dalam box
                document.getElementById('preview-icon-img').src = e.target.result;
                document.getElementById('preview-icon-img').style.display = 'block';
                document.getElementById('preview-icon-placeholder').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Live preview for text fields
    document.getElementById('name').addEventListener('input', function() {
        document.getElementById('preview-name').textContent = this.value || 'Nama Badge';
    });
    document.getElementById('description').addEventListener('input', function() {
        document.getElementById('preview-desc').textContent = this.value || 'Deskripsi badge';
    });
</script>
@endpush
@endsection
