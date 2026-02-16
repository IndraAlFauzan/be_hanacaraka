@extends('admin.layout.app')

@section('title', 'Tambah Level Baru')
@section('page-title', 'Tambah Level Baru')
@section('page-subtitle', 'Buat level pembelajaran baru')

@push('styles')
<style>
    .form-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px 16px 0 0 !important;
        padding: 20px 24px;
    }
    .form-card .card-header i {
        opacity: 0.9;
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
    .form-label .text-danger {
        font-size: 0.9em;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }
    .form-text {
        color: #6c757d;
        font-size: 0.8rem;
        margin-top: 6px;
    }
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    .form-switch .form-check-input {
        width: 48px;
        height: 24px;
    }
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 10px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    .btn-cancel {
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 10px;
    }
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .info-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .info-list li:last-child {
        border-bottom: none;
    }
    .info-list li i {
        color: #667eea;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.levels.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Level
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span class="fw-semibold">Form Tambah Level</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.levels.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="level_number" class="form-label">
                                Nomor Level <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('level_number') is-invalid @enderror" 
                                   id="level_number" 
                                   name="level_number" 
                                   value="{{ old('level_number') }}"
                                   min="1"
                                   placeholder="contoh: 1"
                                   required>
                            @error('level_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nomor urutan level (harus unik)</div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="xp_required" class="form-label">
                                XP yang Dibutuhkan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('xp_required') is-invalid @enderror" 
                                       id="xp_required" 
                                       name="xp_required" 
                                       value="{{ old('xp_required', 0) }}"
                                       min="0"
                                       placeholder="0"
                                       required>
                                <span class="input-group-text">XP</span>
                            </div>
                            @error('xp_required')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Jumlah XP untuk membuka level ini</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="title" class="form-label">
                            Judul Level <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               maxlength="255"
                               placeholder="contoh: Aksara Nglegena"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Tuliskan deskripsi singkat tentang level ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 p-3 rounded-3" style="background: #f8f9fa;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                Level Aktif
                            </label>
                        </div>
                        <div class="form-text mt-1">Level yang tidak aktif tidak akan ditampilkan kepada user</div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="bi bi-check-lg me-2"></i>Simpan Level
                        </button>
                        <a href="{{ route('admin.levels.index') }}" class="btn btn-outline-secondary btn-cancel">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card info-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-lightbulb text-warning"></i>
                    <span class="fw-semibold">Panduan</span>
                </div>
            </div>
            <div class="card-body">
                <ul class="info-list small">
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Nomor Level</strong><br>
                            Setiap nomor level harus unik dan menentukan urutan tampilan
                        </div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>XP Required</strong><br>
                            Level pertama biasanya 0 XP agar bisa langsung diakses
                        </div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Setelah Membuat Level</strong><br>
                            Tambahkan <strong>stages</strong> ke dalam level ini agar user bisa belajar
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card info-card mt-3">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-book text-primary"></i>
                    <span class="fw-semibold">Contoh Level</span>
                </div>
            </div>
            <div class="card-body small">
                <div class="mb-2"><strong>Level 1:</strong> Aksara Nglegena (0 XP)</div>
                <div class="mb-2"><strong>Level 2:</strong> Sandhangan (200 XP)</div>
                <div class="mb-2"><strong>Level 3:</strong> Aksara Murda (500 XP)</div>
                <div><strong>Level 4:</strong> Aksara Swara (800 XP)</div>
            </div>
        </div>
    </div>
</div>
@endsection
