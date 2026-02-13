@extends('admin.layout.app')

@section('title', 'Edit Level - ' . $level->title)
@section('page-title', 'Edit Level')
@section('page-subtitle', 'Perbarui informasi level')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Form Edit Level
            </div>
            <div class="card-body">
                <form action="{{ route('admin.levels.update', $level->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="level_number" class="form-label">Nomor Level <span class="text-danger">*</span></label>
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
                        <small class="text-muted">Nomor urutan level (harus unik)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Level <span class="text-danger">*</span></label>
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
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4">{{ old('description', $level->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="xp_required" class="form-label">XP yang Dibutuhkan <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('xp_required') is-invalid @enderror" 
                               id="xp_required" 
                               name="xp_required" 
                               value="{{ old('xp_required', $level->xp_required) }}"
                               min="0"
                               required>
                        @error('xp_required')
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
                                   {{ old('is_active', $level->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Level Aktif
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Level
                        </button>
                        <a href="{{ route('admin.levels.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="button" 
                                class="btn btn-danger ms-auto" 
                                onclick="if(confirm('Yakin ingin menghapus level ini?')) document.getElementById('delete-form').submit()">
                            <i class="bi bi-trash me-2"></i>Hapus Level
                        </button>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('admin.levels.destroy', $level->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
        
        <!-- Stages in this level -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-task me-2"></i>Stages dalam Level Ini ({{ $level->stages->count() }})</span>
                <a href="{{ route('admin.stages.create', ['level_id' => $level->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Tambah Stage
                </a>
            </div>
            <div class="card-body">
                @if($level->stages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Judul</th>
                                    <th>XP</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($level->stages->sortBy('stage_number') as $stage)
                                <tr>
                                    <td><strong>{{ $stage->stage_number }}</strong></td>
                                    <td>{{ $stage->title }}</td>
                                    <td><span class="badge bg-info">{{ $stage->xp_reward }} XP</span></td>
                                    <td>
                                        <span class="badge bg-{{ $stage->is_active ? 'success' : 'secondary' }}">
                                            {{ $stage->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.stages.edit', $stage->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada stage dalam level ini. <a href="{{ route('admin.stages.create', ['level_id' => $level->id]) }}">Tambah stage pertama</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Statistik Level
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total Stages:</span>
                        <strong>{{ $level->stages->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Stages Aktif:</span>
                        <strong>{{ $level->stages->where('is_active', true)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total Materi:</span>
                        <strong>{{ $level->stages->sum(fn($s) => $s->materials->count()) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total Kuis:</span>
                        <strong>{{ $level->stages->sum(fn($s) => $s->quizzes->count()) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Perhatian
            </div>
            <div class="card-body">
                <p class="small mb-0">
                    <strong>Menghapus level</strong> akan menghapus semua stages, materials, quizzes, dan evaluations yang terkait. Pastikan data sudah di-backup jika diperlukan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
