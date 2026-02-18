@extends('admin.layout.app')

@section('title', 'Edit Badge - ' . $badge->name)
@section('page-title', 'Edit Badge')
@section('page-subtitle', 'Perbarui badge penghargaan')

@push('styles')
<style>
    .form-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
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
        border-color: #f7971e;
        box-shadow: 0 0 0 3px rgba(247, 151, 30, 0.15);
    }
    .form-text {
        color: #6c757d;
        font-size: 0.8rem;
        margin-top: 6px;
    }
    .btn-submit {
        background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        border: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 10px;
        color: #fff;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(247, 151, 30, 0.4);
        color: #fff;
    }
    .emoji-picker {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-top: 8px;
    }
    .emoji-picker span {
        font-size: 1.5rem;
        cursor: pointer;
        padding: 4px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .emoji-picker span:hover {
        background: #e9ecef;
        transform: scale(1.2);
    }
    .preview-box {
        background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        border: 2px solid #ffd200;
    }
    .preview-icon {
        font-size: 3rem;
        margin-bottom: 12px;
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
    .user-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .user-row:hover {
        background: #f8f9fa;
    }
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        background: #667eea;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.badges.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Badge
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill"></i>
                    <span class="fw-semibold">Edit Badge</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.badges.update', $badge->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Nama Badge <span class="text-danger">*</span>
                        </label>
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
                    
                    <div class="mb-4">
                        <label for="icon" class="form-label">
                            Gambar Icon Badge <span class="text-muted">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        
                        @if($badge->icon_path)
                        <div class="mb-3">
                            <label class="form-label small text-muted">Icon Saat Ini:</label>
                            <div>
                                <img src="{{ asset('storage/' . $badge->icon_path) }}" alt="{{ $badge->name }}" style="max-width: 100px; max-height: 100px; border-radius: 12px; border: 2px solid #e9ecef;">
                            </div>
                        </div>
                        @endif
                        
                        <input type="file" 
                               class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" 
                               name="icon"
                               accept="image/*"
                               onchange="previewIcon(this)">
                        <div class="form-text">Format: JPG, PNG, GIF, SVG, WEBP. Maksimal 2MB</div>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="iconPreview" class="mt-3" style="display: none;">
                            <label class="form-label small text-muted">Preview Icon Baru:</label>
                            <div>
                                <img id="iconPreviewImg" src="" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 12px; border: 2px solid #e9ecef;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4 mt-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $badge->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="criteria_type" class="form-label">
                                Tipe Kriteria <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('criteria_type') is-invalid @enderror" 
                                    id="criteria_type" 
                                    name="criteria_type" 
                                    required>
                                <option value="xp_milestone" {{ old('criteria_type', $badge->criteria_type) == 'xp_milestone' ? 'selected' : '' }}>XP Milestone (Total XP)</option>
                                <option value="streak" {{ old('criteria_type', $badge->criteria_type) == 'streak' ? 'selected' : '' }}>Streak (Hari Berturut-turut)</option>
                                <option value="level_complete" {{ old('criteria_type', $badge->criteria_type) == 'level_complete' ? 'selected' : '' }}>Level Complete</option>
                                <option value="custom" {{ old('criteria_type', $badge->criteria_type) == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                            @error('criteria_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
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
                    </div>
                    
                    <div class="mb-4">
                        <label for="xp_reward" class="form-label">
                            XP Reward <span class="text-danger">*</span>
                        </label>
                        <div class="input-group" style="max-width: 200px;">
                            <span class="input-group-text">+</span>
                            <input type="number" 
                                   class="form-control @error('xp_reward') is-invalid @enderror" 
                                   id="xp_reward" 
                                   name="xp_reward" 
                                   value="{{ old('xp_reward', $badge->xp_reward) }}"
                                   min="0"
                                   required>
                            <span class="input-group-text">XP</span>
                        </div>
                        @error('xp_reward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-lg me-2"></i>Update Badge
                        </button>
                        <a href="{{ route('admin.badges.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button type="button" 
                                class="btn btn-outline-danger ms-auto"
                                onclick="confirmDelete('{{ route('admin.badges.destroy', $badge->id) }}', 'Badge ini dan semua data user yang memilikinya akan dihapus permanen.')">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Users yang memiliki badge ini -->
        <div class="card mt-4">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary"></i>
                    <span>Pemilik Badge</span>
                    <span class="badge badge-soft-primary ms-auto">{{ $badge->users->count() }} user</span>
                </div>
            </div>
            <div class="card-body">
                @if($badge->users->count() > 0)
                    @foreach($badge->users->take(10) as $user)
                    <div class="user-row">
                        <div class="user-avatar">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <small class="text-muted">{{ $user->pivot->earned_at instanceof \Carbon\Carbon ? $user->pivot->earned_at->format('d M Y') : \Carbon\Carbon::parse($user->pivot->earned_at)->format('d M Y') }}</small>
                    </div>
                    @endforeach
                    @if($badge->users->count() > 10)
                        <p class="text-center text-muted small mb-0 mt-3">
                            +{{ $badge->users->count() - 10 }} user lainnya
                        </p>
                    @endif
                @else
                    <div class="empty-state py-4">
                        <div class="empty-state-icon" style="width: 60px; height: 60px;">
                            <i class="bi bi-people" style="font-size: 1.5rem;"></i>
                        </div>
                        <p class="text-muted mb-0">Belum ada user yang meraih badge ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card info-card mb-3">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-eye text-primary"></i>
                    <span class="fw-semibold">Preview Badge</span>
                </div>
            </div>
            <div class="card-body">
                <div class="preview-box">
                    <div class="preview-icon">
                        @if($badge->icon_path)
                            <img src="{{ asset('storage/' . $badge->icon_path) }}" alt="{{ $badge->name }}" style="max-width: 80px; max-height: 80px; object-fit: contain;">
                        @else
                            <span style="font-size: 3rem;">📷</span>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">{{ $badge->name }}</h5>
                    <p class="text-muted small mb-0">{{ $badge->description }}</p>
                </div>
            </div>
        </div>
        
        <div class="card info-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-fill text-primary"></i>
                    <span class="fw-semibold">Statistik</span>
                </div>
            </div>
            <div class="card-body">
                <div class="stat-item">
                    <span class="label">Total Pemilik</span>
                    <span class="value text-primary">{{ $badge->users->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">XP Reward</span>
                    <span class="badge badge-soft-warning">+{{ $badge->xp_reward }} XP</span>
                </div>
                <div class="stat-item">
                    <span class="label">Kriteria</span>
                    <span class="value small">{{ ucfirst(str_replace('_', ' ', $badge->criteria_type)) }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Dibuat</span>
                    <span class="value small">{{ $badge->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewIcon(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('iconPreview').style.display = 'block';
                document.getElementById('iconPreviewImg').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
