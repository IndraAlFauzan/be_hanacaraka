@extends('admin.layout.app')

@section('title', 'Tambah Level Baru')
@section('page-title', 'Tambah Level Baru')
@section('page-subtitle', 'Buat level pembelajaran baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Form Tambah Level
            </div>
            <div class="card-body">
                <form action="{{ route('admin.levels.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="level_number" class="form-label">Nomor Level <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('level_number') is-invalid @enderror" 
                               id="level_number" 
                               name="level_number" 
                               value="{{ old('level_number') }}"
                               min="1"
                               required>
                        @error('level_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nomor urutan level (harus unik)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Level <span class="text-danger">*</span></label>
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
                        <small class="text-muted">Contoh: Aksara Nglegena</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Deskripsi singkat tentang level ini</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="xp_required" class="form-label">XP yang Dibutuhkan <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('xp_required') is-invalid @enderror" 
                               id="xp_required" 
                               name="xp_required" 
                               value="{{ old('xp_required', 0) }}"
                               min="0"
                               required>
                        @error('xp_required')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jumlah XP yang harus dikumpulkan untuk membuka level ini</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Level Aktif
                            </label>
                        </div>
                        <small class="text-muted">Level yang tidak aktif tidak akan ditampilkan kepada user</small>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Level
                        </button>
                        <a href="{{ route('admin.levels.index') }}" class="btn btn-secondary">
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
                <h6 class="fw-bold">Aturan Nomor Level</h6>
                <ul class="small">
                    <li>Setiap nomor level harus unik</li>
                    <li>Disarankan urut dari 1, 2, 3, dst</li>
                    <li>Nomor menentukan urutan tampilan</li>
                </ul>
                
                <hr>
                
                <h6 class="fw-bold">XP Required</h6>
                <p class="small mb-0">
                    Level pertama biasanya 0 XP agar bisa langsung diakses. Level selanjutnya bisa disesuaikan dengan tingkat kesulitan.
                </p>
                
                <hr>
                
                <h6 class="fw-bold">Setelah Membuat Level</h6>
                <p class="small mb-0">
                    Jangan lupa untuk menambahkan <strong>stages</strong> ke dalam level ini agar user bisa belajar.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
