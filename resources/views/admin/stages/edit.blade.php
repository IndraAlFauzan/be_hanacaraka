@extends('admin.layout.app')

@section('title', 'Edit Stage - ' . $stage->title)
@section('page-title', 'Edit Stage')
@section('page-subtitle', 'Perbarui informasi stage')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Form Edit Stage
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stages.update', $stage->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="level_id" class="form-label">Level <span class="text-danger">*</span></label>
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
                        <div class="col-md-6 mb-3">
                            <label for="stage_number" class="form-label">Nomor Stage <span class="text-danger">*</span></label>
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
                        
                        <div class="col-md-6 mb-3">
                            <label for="xp_reward" class="form-label">XP Reward <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('xp_reward') is-invalid @enderror" 
                                   id="xp_reward" 
                                   name="xp_reward" 
                                   value="{{ old('xp_reward', $stage->xp_reward) }}"
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
                               value="{{ old('title', $stage->title) }}"
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
                                  rows="4">{{ old('description', $stage->description) }}</textarea>
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
                                   {{ old('is_active', $stage->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Stage Aktif
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Stage
                        </button>
                        <a href="{{ route('admin.stages.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="button" 
                                class="btn btn-danger ms-auto" 
                                onclick="if(confirm('Yakin ingin menghapus stage ini?')) document.getElementById('delete-form').submit()">
                            <i class="bi bi-trash me-2"></i>Hapus Stage
                        </button>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('admin.stages.destroy', $stage->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
        
        <!-- Konten dalam stage -->
        <div class="row mt-3">
            <!-- Materials -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-book me-2"></i>Materi ({{ $stage->materials->count() }})</span>
                        <a href="{{ route('admin.materials.create', ['stage_id' => $stage->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($stage->materials->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($stage->materials->sortBy('order_index') as $material)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $material->order_index }}. {{ $material->title }}</span>
                                        <a href="{{ route('admin.materials.edit', $material->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-muted p-3 mb-0">Belum ada materi</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Quizzes -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-question-circle me-2"></i>Kuis ({{ $stage->quizzes->count() }})</span>
                        <a href="{{ route('admin.quizzes.create', ['stage_id' => $stage->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($stage->quizzes->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($stage->quizzes as $quiz)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $quiz->title }}</span>
                                        <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-muted p-3 mb-0">Belum ada kuis</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Evaluations -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pencil-square me-2"></i>Evaluasi Menggambar ({{ $stage->evaluations->count() }})</span>
                <a href="{{ route('admin.evaluations.create', ['stage_id' => $stage->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Tambah Evaluasi
                </a>
            </div>
            <div class="card-body">
                @if($stage->evaluations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Karakter Target</th>
                                    <th>Min. Similarity</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stage->evaluations as $evaluation)
                                    <tr>
                                        <td><strong>{{ $evaluation->character_target }}</strong></td>
                                        <td>{{ $evaluation->min_similarity_score }}%</td>
                                        <td>
                                            <span class="badge bg-{{ $evaluation->is_active ? 'success' : 'secondary' }}">
                                                {{ $evaluation->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.evaluations.edit', $evaluation->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">Belum ada evaluasi menggambar</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Statistik Stage
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Materi:</span>
                        <strong>{{ $stage->materials->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Kuis:</span>
                        <strong>{{ $stage->quizzes->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Evaluasi:</span>
                        <strong>{{ $stage->evaluations->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Informasi
            </div>
            <div class="card-body">
                <p class="small mb-0">
                    <strong>Catatan:</strong> Menghapus stage akan menghapus semua materi, kuis, dan evaluasi yang terkait. Pastikan data sudah di-backup jika diperlukan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
