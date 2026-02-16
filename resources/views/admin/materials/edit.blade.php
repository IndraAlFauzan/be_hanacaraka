@extends('admin.layout.app')

@section('title', 'Edit Materi - ' . $material->title)
@section('page-title', 'Edit Materi')
@section('page-subtitle', 'Perbarui materi pembelajaran')

@push('styles')
<style>
    .form-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        border-radius: 16px 16px 0 0 !important;
        padding: 20px 24px;
    }
    .info-card {
        border-radius: 16px;
        border: 1px solid #e9ecef;
        background: linear-gradient(135deg, #f0f9ff 0%, #fff 100%);
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
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
    }
    .btn-submit {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        border: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 10px;
        color: #fff;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        color: #fff;
    }
    .upload-zone {
        border: 2px dashed #d0d7de;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        background: #f8fafc;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .upload-zone:hover {
        border-color: #3498db;
        background: #f0f9ff;
    }
    .current-image {
        border-radius: 12px;
        padding: 16px;
        background: #f8fafc;
        border: 1px solid #e9ecef;
    }
    .current-image img {
        border-radius: 10px;
        max-height: 200px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .preview-container {
        margin-top: 16px;
        padding: 12px;
        background: #ecfdf5;
        border-radius: 10px;
        border: 1px solid #10b981;
        display: none;
    }
    .preview-container.show {
        display: block;
    }
    .preview-container img {
        border-radius: 8px;
        max-height: 200px;
    }
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .stat-item:last-child {
        border-bottom: none;
    }
    .stat-item .label {
        color: #6c757d;
        font-size: 0.85rem;
    }
    .stat-item .value {
        font-weight: 600;
        color: #1a1a2e;
    }
    .format-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
    }
    .format-tab {
        padding: 8px 16px;
        border-radius: 8px;
        background: #f1f5f9;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
    }
    .format-tab:hover {
        background: #e2e8f0;
    }
    .format-tab.active {
        background: #3498db;
        color: white;
    }
    .tab-content-format {
        display: none;
    }
    .tab-content-format.active {
        display: block;
    }
    .markdown-help {
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px;
        margin-top: 8px;
    }
    .markdown-help code {
        background: #e2e8f0;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.materials.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Materi
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-journal-richtext"></i>
                    <span class="fw-semibold">Edit Materi</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="stage_id" class="form-label">
                            Stage Tujuan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('stage_id') is-invalid @enderror" 
                                id="stage_id" 
                                name="stage_id" 
                                required>
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id', $material->stage_id) == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->title }} (Level {{ $stage->level->level_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('stage_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <label for="title" class="form-label">
                                Judul Materi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $material->title) }}"
                                   maxlength="255"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label for="order_index" class="form-label">
                                Urutan <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('order_index') is-invalid @enderror" 
                                   id="order_index" 
                                   name="order_index" 
                                   value="{{ old('order_index', $material->order_index) }}"
                                   min="1"
                                   required>
                            @error('order_index')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Format Tabs -->
                    <div class="mb-4">
                        <label class="form-label">Konten Materi</label>
                        <div class="format-tabs">
                            <button type="button" class="format-tab active" data-target="text-content">
                                <i class="bi bi-text-paragraph me-1"></i>Teks Biasa
                            </button>
                            <button type="button" class="format-tab" data-target="markdown-content">
                                <i class="bi bi-markdown me-1"></i>Markdown
                            </button>
                        </div>
                        
                        <div id="text-content" class="tab-content-format active">
                            <textarea class="form-control @error('content_text') is-invalid @enderror" 
                                      id="content_text" 
                                      name="content_text" 
                                      rows="6">{{ old('content_text', $material->content_text) }}</textarea>
                            @error('content_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div id="markdown-content" class="tab-content-format">
                            <textarea class="form-control @error('content_markdown') is-invalid @enderror" 
                                      id="content_markdown" 
                                      name="content_markdown" 
                                      rows="8"
                                      style="font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">{{ old('content_markdown', $material->content_markdown) }}</textarea>
                            @error('content_markdown')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="markdown-help">
                                <small class="text-muted">
                                    <code># Heading</code> &bull; 
                                    <code>**bold**</code> &bull; 
                                    <code>*italic*</code> &bull; 
                                    <code>- list item</code>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Image -->
                    @if($material->image_url)
                    <div class="mb-4">
                        <label class="form-label">Gambar Saat Ini</label>
                        <div class="current-image">
                            <img src="{{ $material->image_url }}" alt="Current image">
                        </div>
                    </div>
                    @endif
                    
                    <!-- Image Upload -->
                    <div class="mb-4">
                        <label class="form-label">{{ $material->image_url ? 'Ganti Gambar' : 'Upload Gambar' }} (Opsional)</label>
                        <div class="upload-zone" onclick="document.getElementById('image').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-muted"></i>
                            <p class="mb-1 mt-2 text-muted">Klik untuk upload gambar baru</p>
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                        </div>
                        <input type="file" 
                               class="d-none @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/jpeg,image/jpg,image/png"
                               onchange="previewImage(event)">
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div id="preview" class="preview-container">
                            <div class="d-flex align-items-center gap-3">
                                <img id="preview-img" src="">
                                <div>
                                    <small class="text-success fw-semibold d-block mb-2">Gambar baru dipilih</small>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearPreview()">
                                        <i class="bi bi-x-lg me-1"></i>Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-lg me-2"></i>Update Materi
                        </button>
                        <a href="{{ route('admin.materials.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button type="button" 
                                class="btn btn-outline-danger ms-auto"
                                onclick="confirmDelete('{{ route('admin.materials.destroy', $material->id) }}', 'Materi ini akan dihapus permanen.')">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card info-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle text-primary"></i>
                    <span class="fw-semibold">Informasi Materi</span>
                </div>
            </div>
            <div class="card-body">
                <div class="stat-item">
                    <span class="label">Stage</span>
                    <span class="value">{{ $material->stage->title }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Level</span>
                    <span class="badge badge-soft-primary">Level {{ $material->stage->level->level_number }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Urutan</span>
                    <span class="badge badge-soft-secondary">#{{ $material->order_index }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Dibuat</span>
                    <span class="value small">{{ $material->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Diupdate</span>
                    <span class="value small">{{ $material->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
        
        <div class="card info-card mt-3">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-link-45deg text-primary"></i>
                    <span class="fw-semibold">Tautan Cepat</span>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.stages.edit', $material->stage_id) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="bi bi-arrow-return-left me-2"></i>Kembali ke Stage
                </a>
                <a href="{{ route('admin.levels.edit', $material->stage->level_id) }}" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-layers me-2"></i>Lihat Level
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Format tabs
document.querySelectorAll('.format-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.format-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content-format').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById(this.dataset.target).classList.add('active');
    });
});

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview').classList.add('show');
        };
        reader.readAsDataURL(file);
    }
}

function clearPreview() {
    document.getElementById('image').value = '';
    document.getElementById('preview').classList.remove('show');
    document.getElementById('preview-img').src = '';
}
</script>
@endpush
