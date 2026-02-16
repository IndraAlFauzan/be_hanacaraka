@extends('admin.layout.app')

@section('title', 'Tambah Stage Baru')
@section('page-title', 'Tambah Stage Baru')
@section('page-subtitle', 'Buat stage pembelajaran baru')

@push('styles')
<style>
    .form-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
        border-color: #11998e;
        box-shadow: 0 0 0 3px rgba(17, 153, 142, 0.15);
    }
    .form-text {
        color: #6c757d;
        font-size: 0.8rem;
        margin-top: 6px;
    }
    .form-switch .form-check-input {
        width: 48px;
        height: 24px;
    }
    .form-check-input:checked {
        background-color: #11998e;
        border-color: #11998e;
    }
    .btn-submit {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 10px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
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
        font-size: 0.85rem;
    }
    .info-list li:last-child {
        border-bottom: none;
    }
    .info-list li i {
        color: #11998e;
        margin-top: 2px;
    }
    .eval-type-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    .eval-type-card:hover {
        border-color: #11998e;
    }
    .eval-type-card.selected {
        border-color: #11998e;
        background: rgba(17, 153, 142, 0.05);
    }
    .eval-type-card i {
        font-size: 1.5rem;
        margin-bottom: 8px;
        display: block;
    }
    .eval-type-card .title {
        font-weight: 600;
        font-size: 0.9rem;
    }
    .eval-type-card .desc {
        font-size: 0.75rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.stages.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Stage
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span class="fw-semibold">Form Tambah Stage</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.stages.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="level_id" class="form-label">
                            Level <span class="text-danger">*</span>
                        </label>
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
                        <div class="col-md-6 mb-4">
                            <label for="stage_number" class="form-label">
                                Nomor Stage <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('stage_number') is-invalid @enderror" 
                                   id="stage_number" 
                                   name="stage_number" 
                                   value="{{ old('stage_number') }}"
                                   min="1"
                                   placeholder="contoh: 1"
                                   required>
                            @error('stage_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Urutan stage dalam level</div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="xp_reward" class="form-label">
                                XP Reward <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('xp_reward') is-invalid @enderror" 
                                       id="xp_reward" 
                                       name="xp_reward" 
                                       value="{{ old('xp_reward', 50) }}"
                                       min="0"
                                       required>
                                <span class="input-group-text">XP</span>
                            </div>
                            @error('xp_reward')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="title" class="form-label">
                            Judul Stage <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               maxlength="255"
                               placeholder="contoh: Mengenal Huruf Ha"
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
                                  rows="3"
                                  placeholder="Deskripsi singkat tentang stage ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="eval-type-card {{ old('evaluation_type', 'drawing') == 'drawing' ? 'selected' : '' }}" for="eval_drawing">
                                    <i class="bi bi-pencil-fill text-primary"></i>
                                    <div class="title">Drawing</div>
                                    <div class="desc">Menggambar aksara</div>
                                </label>
                                <input type="radio" name="evaluation_type" id="eval_drawing" value="drawing" 
                                       {{ old('evaluation_type', 'drawing') == 'drawing' ? 'checked' : '' }} class="d-none">
                            </div>
                            <div class="col-4">
                                <label class="eval-type-card {{ old('evaluation_type') == 'quiz' ? 'selected' : '' }}" for="eval_quiz">
                                    <i class="bi bi-question-circle-fill text-warning"></i>
                                    <div class="title">Quiz</div>
                                    <div class="desc">Kuis saja</div>
                                </label>
                                <input type="radio" name="evaluation_type" id="eval_quiz" value="quiz" 
                                       {{ old('evaluation_type') == 'quiz' ? 'checked' : '' }} class="d-none">
                            </div>
                            <div class="col-4">
                                <label class="eval-type-card {{ old('evaluation_type') == 'both' ? 'selected' : '' }}" for="eval_both">
                                    <i class="bi bi-ui-checks-grid text-success"></i>
                                    <div class="title">Both</div>
                                    <div class="desc">Drawing + Quiz</div>
                                </label>
                                <input type="radio" name="evaluation_type" id="eval_both" value="both" 
                                       {{ old('evaluation_type') == 'both' ? 'checked' : '' }} class="d-none">
                            </div>
                        </div>
                        @error('evaluation_type')
                            <div class="text-danger small mt-2">{{ $message }}</div>
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
                                Stage Aktif
                            </label>
                        </div>
                        <div class="form-text mt-1">Stage yang tidak aktif tidak akan ditampilkan kepada user</div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-success btn-submit">
                            <i class="bi bi-check-lg me-2"></i>Simpan Stage
                        </button>
                        <a href="{{ route('admin.stages.index') }}" class="btn btn-outline-secondary">
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
                <ul class="info-list">
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Nomor Stage</strong><br>
                            Menentukan urutan pembelajaran. Urut dari 1, 2, 3 dalam satu level.
                        </div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>XP Reward</strong><br>
                            Jumlah XP yang diberikan saat stage selesai. Default: 50 XP.
                        </div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Setelah Membuat</strong><br>
                            Tambahkan materi, kuis, dan evaluasi ke stage ini.
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card info-card mt-3">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-gem text-info"></i>
                    <span class="fw-semibold">Tips</span>
                </div>
            </div>
            <div class="card-body">
                <p class="small mb-0 text-muted">
                    Setiap stage sebaiknya fokus pada <strong>1 huruf</strong> atau konsep aksara Jawa. Jangan terlalu banyak materi dalam 1 stage agar user tidak kewalahan.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.eval-type-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.eval-type-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
</script>
@endpush
@endsection
