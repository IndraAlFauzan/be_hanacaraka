@extends('admin.layout.app')

@section('title', 'Edit Materi - ' . $material->title)
@section('page-title', 'Edit Materi')
@section('page-subtitle', 'Perbarui materi pembelajaran')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Form Edit Materi
            </div>
            <div class="card-body">
                <form action="{{ route('admin.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="stage_id" class="form-label">Stage <span class="text-danger">*</span></label>
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
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Judul Materi <span class="text-danger">*</span></label>
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
                        
                        <div class="col-md-4 mb-3">
                            <label for="order_index" class="form-label">Urutan <span class="text-danger">*</span></label>
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
                    
                    <div class="mb-3">
                        <label for="content_text" class="form-label">Konten Teks</label>
                        <textarea class="form-control @error('content_text') is-invalid @enderror" 
                                  id="content_text" 
                                  name="content_text" 
                                  rows="5">{{ old('content_text', $material->content_text) }}</textarea>
                        @error('content_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="content_markdown" class="form-label">Konten Markdown</label>
                        <textarea class="form-control @error('content_markdown') is-invalid @enderror" 
                                  id="content_markdown" 
                                  name="content_markdown" 
                                  rows="8"
                                  style="font-family: monospace;">{{ old('content_markdown', $material->content_markdown) }}</textarea>
                        @error('content_markdown')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Gambar Ilustrasi Baru</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/jpeg,image/jpg,image/png"
                               onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                        
                        @if($material->image_url)
                            <div class="mt-3">
                                <label class="form-label">Gambar Saat Ini:</label>
                                <div>
                                    <img src="{{ $material->image_url }}" class="img-thumbnail" style="max-width: 300px;">
                                </div>
                            </div>
                        @endif
                        
                        <div id="preview" class="mt-2" style="display: none;">
                            <label class="form-label">Preview Gambar Baru:</label>
                            <div>
                                <img id="preview-img" src="" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="clearPreview()">Hapus Preview</button>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Materi
                        </button>
                        <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="button" 
                                class="btn btn-danger ms-auto" 
                                onclick="if(confirm('Yakin ingin menghapus materi ini?')) document.getElementById('delete-form').submit()">
                            <i class="bi bi-trash me-2"></i>Hapus Materi
                        </button>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Info Materi
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="text-muted">Stage:</span><br>
                    <strong>{{ $material->stage->title }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Level:</span><br>
                    <strong>Level {{ $material->stage->level->level_number }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Dibuat:</span><br>
                    <small>{{ $material->created_at->format('d M Y H:i') }}</small>
                </div>
                <div>
                    <span class="text-muted">Diupdate:</span><br>
                    <small>{{ $material->updated_at->format('d M Y H:i') }}</small>
                </div>
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
