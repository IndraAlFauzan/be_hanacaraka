@extends('admin.layout.app')

@section('title', 'Edit Evaluasi')
@section('page-title', 'Edit Evaluasi')
@section('page-subtitle', 'Perbarui evaluasi menggambar aksara')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Form Edit Evaluasi
            </div>
            <div class="card-body">
                <form action="{{ route('admin.evaluations.update', $evaluation->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="stage_id" class="form-label">Stage <span class="text-danger">*</span></label>
                        <select class="form-select @error('stage_id') is-invalid @enderror" 
                                id="stage_id" 
                                name="stage_id" 
                                required>
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id', $evaluation->stage_id) == $stage->id ? 'selected' : '' }}>
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
                               value="{{ old('character_target', $evaluation->character_target) }}"
                               maxlength="10"
                               required>
                        @error('character_target')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="reference_image_url" class="form-label">URL Gambar Referensi <span class="text-danger">*</span></label>
                        <input type="url" 
                               class="form-control @error('reference_image_url') is-invalid @enderror" 
                               id="reference_image_url" 
                               name="reference_image_url" 
                               value="{{ old('reference_image_url', $evaluation->reference_image_url) }}"
                               required>
                        @error('reference_image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($evaluation->reference_image_url)
                            <div class="mt-2">
                                <label class="form-label">Preview Gambar Saat Ini:</label><br>
                                <img src="{{ $evaluation->reference_image_url }}" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label for="min_similarity_score" class="form-label">
                            Minimum Similarity Score (%) <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('min_similarity_score') is-invalid @enderror" 
                               id="min_similarity_score" 
                               name="min_similarity_score" 
                               value="{{ old('min_similarity_score', $evaluation->min_similarity_score) }}"
                               min="0"
                               max="100"
                               step="0.01"
                               required>
                        @error('min_similarity_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Evaluasi
                        </button>
                        <a href="{{ route('admin.evaluations.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="button" 
                                class="btn btn-danger ms-auto" 
                                onclick="if(confirm('Yakin ingin menghapus evaluasi ini?')) document.getElementById('delete-form').submit()">
                            <i class="bi bi-trash me-2"></i>Hapus Evaluasi
                        </button>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('admin.evaluations.destroy', $evaluation->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
        
        <!-- Submissions -->
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-image me-2"></i>Submission User ({{ $evaluation->submissions->count() }})
            </div>
            <div class="card-body">
                @if($evaluation->submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Gambar</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evaluation->submissions->take(10) as $submission)
                                    <tr>
                                        <td>{{ $submission->user->name }}</td>
                                        <td>
                                            @if($submission->drawing_image_url)
                                                <img src="{{ $submission->drawing_image_url }}" style="width: 40px; height: 40px; object-fit: cover;" class="rounded">
                                            @endif
                                        </td>
                                        <td><strong>{{ number_format($submission->similarity_score, 2) }}%</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $submission->status === 'passed' ? 'success' : ($submission->status === 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($submission->status) }}
                                            </span>
                                        </td>
                                        <td><small>{{ $submission->created_at->format('d M Y') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($evaluation->submissions->count() > 10)
                        <p class="text-center text-muted small mb-0 mt-2">
                            Menampilkan 10 submission terbaru dari {{ $evaluation->submissions->count() }} total
                        </p>
                    @endif
                @else
                    <p class="text-center text-muted">Belum ada submission</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Statistik
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="text-muted">Stage:</span><br>
                    <strong>{{ $evaluation->stage->title }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Level:</span><br>
                    <strong>Level {{ $evaluation->stage->level->level_number }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Total Submissions:</span><br>
                    <strong>{{ $evaluation->submissions->count() }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Passed:</span><br>
                    <strong class="text-success">{{ $evaluation->submissions->where('status', 'passed')->count() }}</strong>
                </div>
                <div>
                    <span class="text-muted">Failed:</span><br>
                    <strong class="text-danger">{{ $evaluation->submissions->where('status', 'failed')->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
