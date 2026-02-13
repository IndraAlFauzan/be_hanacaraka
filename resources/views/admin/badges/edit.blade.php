@extends('admin.layout.app')

@section('title', 'Edit Badge - ' . $badge->name)
@section('page-title', 'Edit Badge')
@section('page-subtitle', 'Perbarui badge penghargaan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Form Edit Badge
            </div>
            <div class="card-body">
                <form action="{{ route('admin.badges.update', $badge->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Badge <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $badge->name) }}"
                               maxlength="50"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $badge->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="icon_url" class="form-label">Icon/Emoji <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('icon_url') is-invalid @enderror" 
                               id="icon_url" 
                               name="icon_url" 
                               value="{{ old('icon_url', $badge->icon_url) }}"
                               maxlength="100"
                               required>
                        @error('icon_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            <label class="form-label">Preview:</label>
                            <div class="fs-1">{{ $badge->icon_url }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="criteria_type" class="form-label">Tipe Kriteria <span class="text-danger">*</span></label>
                        <select class="form-select @error('criteria_type') is-invalid @enderror" 
                                id="criteria_type" 
                                name="criteria_type" 
                                required>
                            <option value="total_xp" {{ old('criteria_type', $badge->criteria_type) == 'total_xp' ? 'selected' : '' }}>Total XP</option>
                            <option value="stages_completed" {{ old('criteria_type', $badge->criteria_type) == 'stages_completed' ? 'selected' : '' }}>Stages Completed</option>
                            <option value="level_reached" {{ old('criteria_type', $badge->criteria_type) == 'level_reached' ? 'selected' : '' }}>Level Reached</option>
                            <option value="streak_days" {{ old('criteria_type', $badge->criteria_type) == 'streak_days' ? 'selected' : '' }}>Streak Days</option>
                            <option value="perfect_quizzes" {{ old('criteria_type', $badge->criteria_type) == 'perfect_quizzes' ? 'selected' : '' }}>Perfect Quizzes (100%)</option>
                            <option value="evaluations_passed" {{ old('criteria_type', $badge->criteria_type) == 'evaluations_passed' ? 'selected' : '' }}>Evaluations Passed</option>
                        </select>
                        @error('criteria_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="criteria_value" class="form-label">Nilai Kriteria</label>
                        <input type="number" 
                               class="form-control @error('criteria_value') is-invalid @enderror" 
                               id="criteria_value" 
                               name="criteria_value" 
                               value="{{ old('criteria_value', $badge->criteria_value) }}"
                               min="0">
                        @error('criteria_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="xp_reward" class="form-label">XP Reward <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('xp_reward') is-invalid @enderror" 
                               id="xp_reward" 
                               name="xp_reward" 
                               value="{{ old('xp_reward', $badge->xp_reward) }}"
                               min="0"
                               required>
                        @error('xp_reward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Badge
                        </button>
                        <a href="{{ route('admin.badges.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="button" 
                                class="btn btn-danger ms-auto" 
                                onclick="if(confirm('Yakin ingin menghapus badge ini?')) document.getElementById('delete-form').submit()">
                            <i class="bi bi-trash me-2"></i>Hapus Badge
                        </button>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('admin.badges.destroy', $badge->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
        
        <!-- Users yang memiliki badge ini -->
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-people me-2"></i>User yang Memiliki Badge Ini ({{ $badge->users->count() }})
            </div>
            <div class="card-body">
                @if($badge->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Diraih Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($badge->users->take(20) as $user)
                                    <tr>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                        </td>
                                        <td><small>{{ $user->email }}</small></td>
                                        <td><small>{{ $user->pivot->earned_at->format('d M Y H:i') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($badge->users->count() > 20)
                        <p class="text-center text-muted small mb-0 mt-2">
                            Menampilkan 20 user terbaru dari {{ $badge->users->count() }} total
                        </p>
                    @endif
                @else
                    <p class="text-center text-muted">Belum ada user yang meraih badge ini</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Statistik Badge
            </div>
            <div class="card-body text-center">
                <div class="fs-1 mb-3">{{ $badge->icon_url }}</div>
                <h5>{{ $badge->name }}</h5>
                <p class="text-muted small">{{ $badge->description }}</p>
                
                <hr>
                
                <div class="mb-2">
                    <span class="text-muted">Total Pemilik:</span><br>
                    <strong class="fs-4">{{ $badge->users->count() }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted">XP Reward:</span><br>
                    <span class="badge bg-warning text-dark">+{{ $badge->xp_reward }} XP</span>
                </div>
                <div>
                    <span class="text-muted">Dibuat:</span><br>
                    <small>{{ $badge->created_at->format('d M Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
