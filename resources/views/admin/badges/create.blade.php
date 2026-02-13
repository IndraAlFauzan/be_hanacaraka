@extends('admin.layout.app')

@section('title', 'Tambah Badge Baru')
@section('page-title', 'Tambah Badge Baru')
@section('page-subtitle', 'Buat badge penghargaan untuk user')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Form Tambah Badge
            </div>
            <div class="card-body">
                <form action="{{ route('admin.badges.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Badge <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               maxlength="50"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: Pemula Sejati, Master Aksara</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Deskripsi singkat tentang badge ini</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="icon_url" class="form-label">Icon/Emoji <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('icon_url') is-invalid @enderror" 
                               id="icon_url" 
                               name="icon_url" 
                               value="{{ old('icon_url', '🏆') }}"
                               maxlength="100"
                               required>
                        @error('icon_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Emoji atau URL gambar untuk icon badge</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="criteria_type" class="form-label">Tipe Kriteria <span class="text-danger">*</span></label>
                        <select class="form-select @error('criteria_type') is-invalid @enderror" 
                                id="criteria_type" 
                                name="criteria_type" 
                                required>
                            <option value="">Pilih Tipe...</option>
                            <option value="total_xp" {{ old('criteria_type') == 'total_xp' ? 'selected' : '' }}>Total XP</option>
                            <option value="stages_completed" {{ old('criteria_type') == 'stages_completed' ? 'selected' : '' }}>Stages Completed</option>
                            <option value="level_reached" {{ old('criteria_type') == 'level_reached' ? 'selected' : '' }}>Level Reached</option>
                            <option value="streak_days" {{ old('criteria_type') == 'streak_days' ? 'selected' : '' }}>Streak Days</option>
                            <option value="perfect_quizzes" {{ old('criteria_type') == 'perfect_quizzes' ? 'selected' : '' }}>Perfect Quizzes (100%)</option>
                            <option value="evaluations_passed" {{ old('criteria_type') == 'evaluations_passed' ? 'selected' : '' }}>Evaluations Passed</option>
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
                               value="{{ old('criteria_value') }}"
                               min="0">
                        @error('criteria_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jumlah yang harus dicapai (contoh: 1000 untuk 1000 XP)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="xp_reward" class="form-label">XP Reward <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('xp_reward') is-invalid @enderror" 
                               id="xp_reward" 
                               name="xp_reward" 
                               value="{{ old('xp_reward', 100) }}"
                               min="0"
                               required>
                        @error('xp_reward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Bonus XP yang diberikan saat mendapat badge</small>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Badge
                        </button>
                        <a href="{{ route('admin.badges.index') }}" class="btn btn-secondary">
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
                <h6 class="fw-bold">Tipe Kriteria</h6>
                <ul class="small mb-3">
                    <li><strong>total_xp:</strong> Total XP yang dikumpulkan</li>
                    <li><strong>stages_completed:</strong> Jumlah stage yang diselesaikan</li>
                    <li><strong>level_reached:</strong> Level yang dicapai</li>
                    <li><strong>streak_days:</strong> Hari berturut-turut belajar</li>
                    <li><strong>perfect_quizzes:</strong> Kuis dengan nilai 100%</li>
                    <li><strong>evaluations_passed:</strong> Evaluasi yang lulus</li>
                </ul>
                
                <hr>
                
                <h6 class="fw-bold">Tips Icon</h6>
                <p class="small mb-2">Gunakan emoji untuk tampilan yang menarik:</p>
                <div class="small">
                    🏆 🥇 🥈 🥉 ⭐ 🌟 💫 ✨ 🎖️ 🏅 🎯 🔥 💎 👑 🎓
                </div>
                
                <hr>
                
                <h6 class="fw-bold">Contoh Badge</h6>
                <ul class="small mb-0">
                    <li>Pemula: 10 stages completed</li>
                    <li>Rajin: 7 streak days</li>
                    <li>Master: 5000 total XP</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
