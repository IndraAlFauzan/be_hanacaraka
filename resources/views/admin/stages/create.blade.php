@extends('admin.layout.app')

@section('title', 'Tambah Stage Baru')
@section('page-title', 'Tambah Stage Baru')
@section('page-subtitle', 'Buat stage pembelajaran baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Form Tambah Stage
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stages.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="level_id" class="form-label">Level <span class="text-danger">*</span></label>
                        <select class="form-select @error('level_id') is-invalid @enderror" 
                                id="level_id" 
                                name="level_id" 
                                required>
                            <option value="">Pilih Level...</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ old('level_id', request('level_id')) == $level->id ? 'selected' : '' }}>
                                    Level {{ $level->level_number }}: {{ $level->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('level_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stage_number" class="form-label">Nomor Stage <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('stage_number') is-invalid @enderror" 
                                   id="stage_number" 
                                   name="stage_number" 
                                   value="{{ old('stage_number') }}"
                                   min="1"
                                   required>
                            @error('stage_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="xp_reward" class="form-label">XP Reward <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('xp_reward') is-invalid @enderror" 
                                   id="xp_reward" 
                                   name="xp_reward" 
                                   value="{{ old('xp_reward', 50) }}"
                                   min="0"
                                   required>
                            @error('xp_reward')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Stage <span class="text-danger">*</span></label>
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
                        <small class="text-muted">Contoh: Mengenal Huruf Ha</small>
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
                                Stage Aktif
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Stage
                        </button>
                        <a href="{{ route('admin.stages.index') }}" class="btn btn-secondary">
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
                <h6 class="fw-bold">Urutan Stage</h6>
                <p class="small">
                    Nomor stage menentukan urutan pembelajaran. Pastikan urut dari 1, 2, 3, dst dalam satu level.
                </p>
                
                <hr>
                
                <h6 class="fw-bold">XP Reward</h6>
                <p class="small">
                    Jumlah XP yang akan diberikan ketika user menyelesaikan stage ini. Default: 50 XP.
                </p>
                
                <hr>
                
                <h6 class="fw-bold">Setelah Membuat Stage</h6>
                <ul class="small mb-0">
                    <li>Tambahkan <strong>materi</strong> untuk penjelasan</li>
                    <li>Tambahkan <strong>kuis</strong> untuk latihan</li>
                    <li>Tambahkan <strong>evaluasi</strong> untuk menggambar aksara</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Tips
            </div>
            <div class="card-body">
                <p class="small mb-0">
                    Setiap stage sebaiknya fokus pada 1 huruf atau konsep aksara Jawa. Jangan terlalu banyak materi dalam 1 stage.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
