@extends('admin.layout.app')

@section('title', 'Tambah Evaluasi Baru')
@section('page-title', 'Tambah Evaluasi Baru')
@section('page-subtitle', 'Buat evaluasi menggambar aksara Jawa')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Form Tambah Evaluasi
            </div>
            <div class="card-body">
                <form action="{{ route('admin.evaluations.store') }}" method="POST">
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
                    
                    <div class="mb-3">
                        <label for="character_target" class="form-label">Karakter Target Aksara Jawa <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('character_target') is-invalid @enderror" 
                               id="character_target" 
                               name="character_target" 
                               value="{{ old('character_target') }}"
                               maxlength="10"
                               placeholder="ꦲ atau ha"
                               required>
                        @error('character_target')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Karakter aksara Jawa yang harus digambar oleh user</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reference_image_url" class="form-label">URL Gambar Referensi <span class="text-danger">*</span></label>
                        <input type="url" 
                               class="form-control @error('reference_image_url') is-invalid @enderror" 
                               id="reference_image_url" 
                               name="reference_image_url" 
                               value="{{ old('reference_image_url') }}"
                               placeholder="https://example.com/aksara-ha.png"
                               required>
                        @error('reference_image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Gambar referensi yang akan dibandingkan dengan gambar user</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="min_similarity_score" class="form-label">
                            Minimum Similarity Score (%) <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('min_similarity_score') is-invalid @enderror" 
                               id="min_similarity_score" 
                               name="min_similarity_score" 
                               value="{{ old('min_similarity_score', 70) }}"
                               min="0"
                               max="100"
                               step="0.01"
                               required>
                        @error('min_similarity_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nilai minimum kesamaan untuk lulus evaluasi (default: 70%)</small>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Evaluasi
                        </button>
                        <a href="{{ route('admin.evaluations.index') }}" class="btn btn-secondary">
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
                <h6 class="fw-bold">Evaluasi Menggambar</h6>
                <p class="small">
                    Evaluasi menggambar aksara digunakan untuk menguji kemampuan user dalam menulis aksara Jawa dengan benar.
                </p>
                
                <hr>
                
                <h6 class="fw-bold">Similarity Score</h6>
                <p class="small">
                    Sistem akan membandingkan gambar yang digambar user dengan gambar referensi menggunakan algoritma computer vision. Score dihitung berdasarkan kesamaan bentuk.
                </p>
                
                <hr>
                
                <h6 class="fw-bold">Rekomendasi</h6>
                <ul class="small mb-0">
                    <li>Gunakan gambar referensi beresolusi tinggi</li>
                    <li>Background putih lebih mudah diproses</li>
                    <li>Set similarity score 60-80% untuk pemula</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
