@extends('admin.layout.app')

@section('title', 'Edit Stage - ' . $stage->title)
@section('page-title', 'Edit Stage')
@section('page-subtitle', 'Perbarui informasi stage')

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
        font-weight: 700;
        color: #1a1a2e;
    }
    .content-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .content-item:hover {
        background: #f8f9fa;
    }
    .content-number {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        color: #6c757d;
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
    .aksara-char {
        font-family: 'Noto Sans Javanese', sans-serif;
        font-size: 1.2rem;
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
                    <i class="bi bi-pencil-square"></i>
                    <span class="fw-semibold">Edit Stage #{{ $stage->stage_number }}</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.stages.update', $stage->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="level_id" class="form-label">
                            Level <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('level_id') is-invalid @enderror" 
                                id="level_id" 
                                name="level_id" 
                                required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ old('level_id', $stage->level_id) == $level->id ? 'selected' : '' }}>
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
                                   value="{{ old('stage_number', $stage->stage_number) }}"
                                   min="1"
                                   required>
                            @error('stage_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                       value="{{ old('xp_reward', $stage->xp_reward) }}"
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
                               value="{{ old('title', $stage->title) }}"
                               maxlength="255"
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
                                  rows="3">{{ old('description', $stage->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="eval-type-card {{ old('evaluation_type', $stage->evaluation_type) == 'drawing' ? 'selected' : '' }}" for="eval_drawing">
                                    <i class="bi bi-pencil-fill text-primary"></i>
                                    <div class="title">Drawing</div>
                                    <div class="desc">Menggambar aksara</div>
                                </label>
                                <input type="radio" name="evaluation_type" id="eval_drawing" value="drawing" 
                                       {{ old('evaluation_type', $stage->evaluation_type) == 'drawing' ? 'checked' : '' }} class="d-none">
                            </div>
                            <div class="col-4">
                                <label class="eval-type-card {{ old('evaluation_type', $stage->evaluation_type) == 'quiz' ? 'selected' : '' }}" for="eval_quiz">
                                    <i class="bi bi-question-circle-fill text-warning"></i>
                                    <div class="title">Quiz</div>
                                    <div class="desc">Kuis saja</div>
                                </label>
                                <input type="radio" name="evaluation_type" id="eval_quiz" value="quiz" 
                                       {{ old('evaluation_type', $stage->evaluation_type) == 'quiz' ? 'checked' : '' }} class="d-none">
                            </div>
                            <div class="col-4">
                                <label class="eval-type-card {{ old('evaluation_type', $stage->evaluation_type) == 'both' ? 'selected' : '' }}" for="eval_both">
                                    <i class="bi bi-ui-checks-grid text-success"></i>
                                    <div class="title">Both</div>
                                    <div class="desc">Drawing + Quiz</div>
                                </label>
                                <input type="radio" name="evaluation_type" id="eval_both" value="both" 
                                       {{ old('evaluation_type', $stage->evaluation_type) == 'both' ? 'checked' : '' }} class="d-none">
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
                                   {{ old('is_active', $stage->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                Stage Aktif
                            </label>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn btn-success btn-submit">
                            <i class="bi bi-check-lg me-2"></i>Update Stage
                        </button>
                        <a href="{{ route('admin.stages.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button type="button" 
                                class="btn btn-outline-danger ms-auto"
                                onclick="confirmDelete('{{ route('admin.stages.destroy', $stage->id) }}', 'Stage ini dan semua materi, kuis, evaluasi yang terkait akan dihapus permanen.')">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Konten dalam stage -->
        <div class="row mt-4 g-4">
            <!-- Materials -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-book text-info"></i>
                            <span>Materi</span>
                            <span class="badge badge-soft-info ms-auto">{{ $stage->materials->count() }}</span>
                        </div>
                        <a href="{{ route('admin.materials.create', ['stage_id' => $stage->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        @if($stage->materials->count() > 0)
                            @foreach($stage->materials->sortBy('order_index') as $material)
                            <div class="content-item">
                                <div class="content-number">{{ $material->order_index }}</div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">{{ $material->title }}</div>
                                </div>
                                <a href="{{ route('admin.materials.edit', $material->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-book d-block mb-2" style="font-size: 1.5rem;"></i>
                                Belum ada materi
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Quizzes -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-question-circle text-warning"></i>
                            <span>Kuis</span>
                            <span class="badge badge-soft-warning ms-auto">{{ $stage->quizzes->count() }}</span>
                        </div>
                        <a href="{{ route('admin.quizzes.create', ['stage_id' => $stage->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        @if($stage->quizzes->count() > 0)
                            @foreach($stage->quizzes as $quiz)
                            <div class="content-item">
                                <div class="content-number">
                                    <i class="bi bi-patch-question-fill text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">{{ $quiz->title }}</div>
                                    <small class="text-muted">{{ $quiz->questions->count() }} soal</small>
                                </div>
                                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-question-circle d-block mb-2" style="font-size: 1.5rem;"></i>
                                Belum ada kuis
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Evaluations -->
        <div class="card mt-4">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-success"></i>
                    <span>Evaluasi Menggambar</span>
                    <span class="badge badge-soft-success ms-auto">{{ $stage->evaluations->count() }}</span>
                </div>
                <a href="{{ route('admin.evaluations.create', ['stage_id' => $stage->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Tambah
                </a>
            </div>
            <div class="card-body">
                @if($stage->evaluations->count() > 0)
                    <div class="row g-3">
                        @foreach($stage->evaluations as $evaluation)
                        <div class="col-md-6">
                            <div class="content-item border rounded-3 p-3">
                                <div class="aksara-char fw-bold" style="font-size: 1.8rem;">{{ $evaluation->character_target }}</div>
                                <div class="flex-grow-1 ms-2">
                                    <div class="fw-semibold small">{{ $evaluation->character_target }}</div>
                                    <small class="text-muted">Min: {{ $evaluation->min_similarity_score }}%</small>
                                </div>
                                <span class="badge badge-soft-{{ $evaluation->is_active ? 'success' : 'secondary' }}">
                                    {{ $evaluation->is_active ? 'Aktif' : 'Off' }}
                                </span>
                                <a href="{{ route('admin.evaluations.edit', $evaluation->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-4">
                        <div class="empty-state-icon" style="width: 60px; height: 60px;">
                            <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i>
                        </div>
                        <p class="text-muted mb-2">Belum ada evaluasi menggambar</p>
                        <a href="{{ route('admin.evaluations.create', ['stage_id' => $stage->id]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Evaluasi
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card info-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-fill text-primary"></i>
                    <span class="fw-semibold">Statistik Stage</span>
                </div>
            </div>
            <div class="card-body">
                <div class="stat-item">
                    <span class="label">Total Materi</span>
                    <span class="value text-info">{{ $stage->materials->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Total Kuis</span>
                    <span class="value text-warning">{{ $stage->quizzes->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Total Evaluasi</span>
                    <span class="value text-success">{{ $stage->evaluations->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">XP Reward</span>
                    <span class="value text-primary">{{ $stage->xp_reward }} XP</span>
                </div>
            </div>
        </div>
        
        <div class="card info-card mt-3">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                    <span class="fw-semibold">Perhatian</span>
                </div>
            </div>
            <div class="card-body">
                <p class="small mb-0 text-muted">
                    <strong class="text-danger">Menghapus stage</strong> akan menghapus semua materi, kuis, dan evaluasi yang terkait. Pastikan data sudah di-backup.
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
