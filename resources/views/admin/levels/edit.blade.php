@extends('admin.layout.app')

@section('title', 'Edit Level - ' . $level->title)
@section('page-title', 'Edit Level')
@section('page-subtitle', 'Perbarui informasi level')

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
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
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
        background-color: #667eea;
        border-color: #667eea;
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
    .stage-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .stage-row:hover {
        background: #f8f9fa;
    }
    .stage-number {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
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
                    <i class="bi bi-pencil-square"></i>
                    <span class="fw-semibold">Edit Level #{{ $level->level_number }}</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.levels.update', $level->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="level_number" class="form-label">
                                Nomor Level <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('level_number') is-invalid @enderror" 
                                   id="level_number" 
                                   name="level_number" 
                                   value="{{ old('level_number', $level->level_number) }}"
                                   min="1"
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
                                       value="{{ old('xp_required', $level->xp_required) }}"
                                       min="0"
                                       required>
                                <span class="input-group-text">XP</span>
                            </div>
                            @error('xp_required')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                               value="{{ old('title', $level->title) }}"
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
                                  rows="4">{{ old('description', $level->description) }}</textarea>
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
                                   {{ old('is_active', $level->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                Level Aktif
                            </label>
                        </div>
                        <div class="form-text mt-1">Level yang tidak aktif tidak akan ditampilkan kepada user</div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="bi bi-check-lg me-2"></i>Update Level
                        </button>
                        <a href="{{ route('admin.levels.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button type="button" 
                                class="btn btn-outline-danger ms-auto"
                                onclick="confirmDelete('{{ route('admin.levels.destroy', $level->id) }}', 'Level ini dan semua stage, materi, kuis yang terkait akan dihapus permanen.')">
                            <i class="bi bi-trash me-2"></i>Hapus Level
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Stages in this level -->
        <div class="card mt-4">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-list-task text-primary"></i>
                    <span>Stages dalam Level Ini</span>
                    <span class="badge badge-soft-primary ms-auto">{{ $level->stages->count() }} stages</span>
                </div>
                <a href="{{ route('admin.stages.create', ['level_id' => $level->id]) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Stage
                </a>
            </div>
            <div class="card-body">
                @if($level->stages->count() > 0)
                    @foreach($level->stages->sortBy('stage_number') as $stage)
                    <div class="stage-row">
                        <div class="stage-number">{{ $stage->stage_number }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $stage->title }}</div>
                            <small class="text-muted">
                                {{ $stage->materials->count() }} materi • 
                                {{ $stage->quizzes->count() }} kuis
                            </small>
                        </div>
                        <span class="badge badge-soft-{{ $stage->is_active ? 'success' : 'secondary' }}">
                            {{ $stage->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <span class="badge badge-soft-info">{{ $stage->xp_reward }} XP</span>
                        <a href="{{ route('admin.stages.edit', $stage->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit Stage">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state py-4">
                        <div class="empty-state-icon" style="width: 60px; height: 60px;">
                            <i class="bi bi-inbox" style="font-size: 1.5rem;"></i>
                        </div>
                        <p class="text-muted mb-2">Belum ada stage dalam level ini</p>
                        <a href="{{ route('admin.stages.create', ['level_id' => $level->id]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Stage Pertama
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
                    <span class="fw-semibold">Statistik Level</span>
                </div>
            </div>
            <div class="card-body">
                <div class="stat-item">
                    <span class="label">Total Stages</span>
                    <span class="value">{{ $level->stages->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Stages Aktif</span>
                    <span class="value text-success">{{ $level->stages->where('is_active', true)->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Total Materi</span>
                    <span class="value text-info">{{ $level->stages->sum(fn($s) => $s->materials->count()) }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Total Kuis</span>
                    <span class="value text-warning">{{ $level->stages->sum(fn($s) => $s->quizzes->count()) }}</span>
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
                    <strong class="text-danger">Menghapus level</strong> akan menghapus semua stages, materials, quizzes, dan evaluations yang terkait. Pastikan data sudah di-backup jika diperlukan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
