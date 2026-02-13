@extends('admin.layout.app')

@section('title', 'Tambah Materi Baru')
@section('page-title', 'Tambah Materi Baru')
@section('page-subtitle', 'Buat materi pembelajaran baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Form Tambah Materi
            </div>
            <div class="card-body">
                <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="stage_id" class="form-label">Stage <span class="text-danger">*</span></label>
                        <select class="form-select @error('stage_id') is-invalid @enderror" 
                                id="stage_id" 
                                name="stage_id" 
                                required>
                            <option value="">Pilih Stage...</option>
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id', request('stage_id')) == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->title }} (Level {{ $stage->level->level_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('stage_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Judul Materi <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   maxlength="255"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="order_index" class="form-label">Urutan <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('order_index') is-invalid @enderror" 
                                   id="order_index" 
                                   name="order_index" 
                                   value="{{ old('order_index', 1) }}"
                                   min="1"
                                   required>
                            @error('order_index')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content_text" class="form-label">Konten Teks</label>
                        <textarea class="form-control @error('content_text') is-invalid @enderror" 
                                  id="content_text" 
                                  name="content_text" 
                                  rows="5">{{ old('content_text') }}</textarea>
                        @error('content_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Konten penjelasan dalam format teks biasa</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content_markdown" class="form-label">Konten Markdown</label>
                        <textarea class="form-control @error('content_markdown') is-invalid @enderror" 
                                  id="content_markdown" 
                                  name="content_markdown" 
                                  rows="8"
                                  style="font-family: monospace;">{{ old('content_markdown') }}</textarea>
                        @error('content_markdown')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Konten dalam format Markdown (opsional)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Gambar Ilustrasi</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/jpeg,image/jpg,image/png"
                               onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG. Maksimal 2MB (opsional)</small>
                        <div id="preview" class="mt-2" style="display: none;">
                            <img id="preview-img" src="" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="clearPreview()">Hapus Preview</button>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Materi
                        </button>
                        <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Informasi
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Urutan Materi</h6>
                <p class="small">
                    Order index menentukan urutan tampilan materi dalam stage. Mulai dari 1, 2, 3, dst.
                </p>
                
                <hr>
                
                <h6 class="fw-bold">Format Konten</h6>
                <p class="small">
                    Anda bisa menggunakan <strong>content_text</strong> untuk teks biasa atau <strong>content_markdown</strong> untuk konten dengan format Markdown.
                </p>
                
                <hr>
                
                <h6 class="fw-bold">Tips Markdown</h6>
                <ul class="small mb-0">
                    <li><code># Heading</code> untuk judul</li>
                    <li><code>**bold**</code> untuk tebal</li>
                    <li><code>*italic*</code> untuk miring</li>
                    <li><code>- item</code> untuk list</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function clearPreview() {
    document.getElementById('image').value = '';
    document.getElementById('preview').style.display = 'none';
    document.getElementById('preview-img').src = '';
}
</script>
@endsection
