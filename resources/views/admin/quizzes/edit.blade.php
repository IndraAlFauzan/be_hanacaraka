@extends('admin.layout.app')

@section('title', 'Edit Kuis - ' . ($quiz->title ?? 'Kuis'))
@section('page-title', 'Edit Kuis')
@section('page-subtitle', 'Perbarui kuis dan pertanyaan')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .form-card-header {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        padding: 28px 32px;
        border: none;
    }
    .form-card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.35rem;
    }
    .form-card-header p {
        margin: 8px 0 0 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }
    .form-card-body {
        padding: 32px;
    }
    .modern-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modern-label i {
        color: #e74c3c;
        font-size: 1.1rem;
    }
    .modern-input {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .modern-input:focus {
        border-color: #e74c3c;
        box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
    }
    .modern-select {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-position: right 18px center;
    }
    .modern-select:focus {
        border-color: #e74c3c;
        box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }
    .section-title h5 {
        margin: 0;
        font-weight: 700;
        color: #374151;
    }
    .section-title .badge {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
    }
    .question-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e9ecef;
        border-radius: 20px;
        padding: 28px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
        position: relative;
    }
    .question-item:hover {
        border-color: #fca5a5;
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.08);
    }
    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }
    .question-number {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .question-number-badge {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }
    .question-number span {
        font-weight: 600;
        color: #374151;
        font-size: 1.1rem;
    }
    .btn-remove-question {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 2px solid #fee2e2;
        background: #fef2f2;
        color: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .btn-remove-question:hover {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
    }
    .question-input {
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        padding: 16px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .question-input:focus {
        border-color: #e74c3c;
        box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
    }
    .option-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        height: 100%;
    }
    .option-card:hover {
        border-color: #fca5a5;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .option-badge {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #374151;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 12px;
    }
    .option-card.correct .option-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .current-image-box {
        background: #f9fafb;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
        text-align: center;
    }
    .current-image-box img {
        max-width: 120px;
        max-height: 100px;
        border-radius: 8px;
        object-fit: cover;
    }
    .current-image-box .label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 8px;
    }
    .image-upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        background: #fafafa;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .image-upload-zone:hover {
        border-color: #e74c3c;
        background: #fef2f2;
    }
    .image-upload-zone i {
        font-size: 2rem;
        color: #d1d5db;
        margin-bottom: 8px;
    }
    .image-upload-zone:hover i {
        color: #e74c3c;
    }
    .image-preview-container {
        margin-top: 12px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 12px;
        display: none;
    }
    .image-preview-container.show {
        display: block;
    }
    .image-preview-container img {
        max-width: 150px;
        max-height: 120px;
        border-radius: 8px;
        object-fit: cover;
    }
    .correct-answer-section {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 2px solid #a7f3d0;
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
    }
    .correct-answer-section .label {
        font-weight: 600;
        color: #065f46;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .correct-answer-section .label i {
        color: #10b981;
    }
    .correct-answer-select {
        border: 2px solid #a7f3d0;
        border-radius: 12px;
        padding: 14px 18px;
        background: white;
        font-weight: 600;
    }
    .correct-answer-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .btn-add-question {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border: 2px dashed #d1d5db;
        padding: 20px 32px;
        font-weight: 600;
        border-radius: 16px;
        color: #6b7280;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-add-question:hover {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-color: #e74c3c;
        color: #dc2626;
    }
    .btn-submit {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border: none;
        padding: 16px 36px;
        font-weight: 600;
        border-radius: 12px;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.35);
        color: white;
    }
    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        border: none;
        padding: 16px 36px;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background: #e5e7eb;
        color: #1f2937;
    }
    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        padding: 16px 36px;
        font-weight: 600;
        border-radius: 12px;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.35);
        color: white;
    }
    .stats-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .stats-card-header {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        padding: 20px 24px;
        border-bottom: none;
    }
    .stats-card-header h6 {
        margin: 0;
        font-weight: 700;
        color: #991b1b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .stats-card-body {
        padding: 24px;
    }
    .stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .stat-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .stat-item:first-child {
        padding-top: 0;
    }
    .stat-item .label {
        color: #6b7280;
        font-size: 0.9rem;
    }
    .stat-item .value {
        font-weight: 700;
        font-size: 1.1rem;
        color: #dc2626;
    }
    .quick-links {
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        padding: 20px;
    }
    .quick-links h6 {
        color: #1e40af;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }
    .quick-link-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: white;
        border-radius: 10px;
        text-decoration: none;
        color: #374151;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .quick-link-item:last-child {
        margin-bottom: 0;
    }
    .quick-link-item:hover {
        transform: translateX(4px);
        color: #1e40af;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .quick-link-item i {
        color: #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="form-card-header">
                <h5><i class="bi bi-pencil-square me-2"></i>Edit Kuis</h5>
                <p>Perbarui kuis "{{ $quiz->title ?? 'Tanpa Judul' }}" dan pertanyaannya</p>
            </div>
            <div class="form-card-body">
                <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST" id="quizForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Quiz Info Section -->
                    <div class="row mb-4">
                        <div class="col-md-5 mb-3">
                            <label class="modern-label">
                                <i class="bi bi-diagram-3"></i>
                                Stage <span class="text-danger">*</span>
                            </label>
                            <select class="form-select modern-select @error('stage_id') is-invalid @enderror" 
                                    id="stage_id" 
                                    name="stage_id" 
                                    required>
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}" {{ old('stage_id', $quiz->stage_id) == $stage->id ? 'selected' : '' }}>
                                        {{ $stage->title }} (Level {{ $stage->level->level_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('stage_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="modern-label">
                                <i class="bi bi-card-heading"></i>
                                Judul Kuis
                            </label>
                            <input type="text" 
                                   class="form-control modern-input @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $quiz->title) }}"
                                   placeholder="Judul kuis (opsional)"
                                   maxlength="100">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="modern-label">
                                <i class="bi bi-bullseye"></i>
                                Passing Score <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control modern-input @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" 
                                       name="passing_score" 
                                       value="{{ old('passing_score', $quiz->passing_score) }}"
                                       min="0"
                                       max="100"
                                       required>
                                <span class="input-group-text" style="border-radius: 0 12px 12px 0; border: 2px solid #e5e7eb; border-left: none;">%</span>
                            </div>
                            @error('passing_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Questions Section -->
                    <div class="section-title">
                        <h5><i class="bi bi-question-circle me-2"></i>Daftar Pertanyaan</h5>
                        <span class="badge" id="questionCount">{{ $quiz->questions->count() }} Soal</span>
                    </div>
                    
                    <div id="questions-container">
                        @foreach($quiz->questions as $index => $question)
                        <div class="question-item" data-index="{{ $index }}">
                            <div class="question-header">
                                <div class="question-number">
                                    <div class="question-number-badge">{{ $index + 1 }}</div>
                                    <span>Pertanyaan</span>
                                </div>
                                <button type="button" class="btn btn-remove-question remove-question">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Question Text -->
                            <div class="mb-4">
                                <label class="modern-label">
                                    <i class="bi bi-chat-left-text"></i>
                                    Teks Pertanyaan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control question-input" 
                                       name="questions[{{ $index }}][question_text]" 
                                       value="{{ $question->question_text }}"
                                       placeholder="Tulis pertanyaan di sini..."
                                       required>
                            </div>
                            
                            <!-- Question Image -->
                            <div class="mb-4">
                                <label class="modern-label">
                                    <i class="bi bi-image"></i>
                                    Gambar Pertanyaan (Opsional)
                                </label>
                                @if($question->question_image_url)
                                <div class="current-image-box">
                                    <div class="label">Gambar saat ini:</div>
                                    <img src="{{ $question->question_image_url }}" alt="Current">
                                    <input type="hidden" name="questions[{{ $index }}][existing_question_image]" value="{{ $question->question_image_url }}">
                                </div>
                                @endif
                                <input type="file" 
                                       class="form-control modern-input" 
                                       name="questions[{{ $index }}][question_image]"
                                       accept="image/jpeg,image/jpg,image/png"
                                       onchange="previewQuestionImage(this, {{ $index }})">
                                <div class="image-preview-container" id="question-preview-{{ $index }}">
                                    <img src="" alt="Preview">
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearQuestionImage({{ $index }})">
                                        <i class="bi bi-x"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Options -->
                            <div class="row">
                                @foreach(['a', 'b', 'c', 'd'] as $opt)
                                <div class="col-md-6 mb-3">
                                    <div class="option-card {{ $question->correct_answer == $opt ? 'correct' : '' }}">
                                        <div class="option-badge">{{ strtoupper($opt) }}</div>
                                        <input type="text" 
                                               class="form-control modern-input mb-2" 
                                               name="questions[{{ $index }}][option_{{ $opt }}]" 
                                               value="{{ $question->{'option_'.$opt} }}" 
                                               placeholder="Teks pilihan {{ strtoupper($opt) }}" 
                                               required>
                                        @if($question->{'option_'.$opt.'_image_url'})
                                        <div class="current-image-box">
                                            <div class="label">Gambar saat ini:</div>
                                            <img src="{{ $question->{'option_'.$opt.'_image_url'} }}" alt="Current" style="max-width: 80px; max-height: 60px;">
                                            <input type="hidden" name="questions[{{ $index }}][existing_option_{{ $opt }}_image]" value="{{ $question->{'option_'.$opt.'_image_url'} }}">
                                        </div>
                                        @endif
                                        <input type="file" 
                                               class="form-control form-control-sm" 
                                               name="questions[{{ $index }}][option_{{ $opt }}_image]" 
                                               accept="image/jpeg,image/jpg,image/png"
                                               onchange="previewOptionImage(this, {{ $index }}, '{{ $opt }}')">
                                        <div class="image-preview-container" id="option-preview-{{ $index }}-{{ $opt }}">
                                            <img src="" alt="Preview" style="max-width: 80px; max-height: 60px;">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Correct Answer -->
                            <div class="correct-answer-section">
                                <div class="label">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Jawaban Benar <span class="text-danger">*</span>
                                </div>
                                <select class="form-select correct-answer-select" name="questions[{{ $index }}][correct_answer]" required>
                                    <option value="a" {{ $question->correct_answer == 'a' ? 'selected' : '' }}>A</option>
                                    <option value="b" {{ $question->correct_answer == 'b' ? 'selected' : '' }}>B</option>
                                    <option value="c" {{ $question->correct_answer == 'c' ? 'selected' : '' }}>C</option>
                                    <option value="d" {{ $question->correct_answer == 'd' ? 'selected' : '' }}>D</option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Add Question Button -->
                    <button type="button" class="btn btn-add-question mb-4" id="addQuestion">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Pertanyaan Baru
                    </button>
                    
                    <hr class="my-4">
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle me-2"></i>Update Kuis
                        </button>
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="button" class="btn btn-delete ms-auto" 
                                onclick="confirmDelete('{{ route('admin.quizzes.destroy', $quiz->id) }}', 'Yakin ingin menghapus kuis ini? Semua pertanyaan dan percobaan terkait juga akan dihapus.')">
                            <i class="bi bi-trash me-2"></i>Hapus Kuis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Stats Card -->
        <div class="card stats-card mb-4">
            <div class="stats-card-header">
                <h6><i class="bi bi-bar-chart-fill"></i>Statistik Kuis</h6>
            </div>
            <div class="stats-card-body">
                <div class="stat-item">
                    <span class="label">Stage</span>
                    <span class="value">{{ $quiz->stage->title }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Level</span>
                    <span class="value">{{ $quiz->stage->level->level_number }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Jumlah Soal</span>
                    <span class="value">{{ $quiz->questions->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Passing Score</span>
                    <span class="value">{{ $quiz->passing_score }}%</span>
                </div>
                <div class="stat-item">
                    <span class="label">Total Percobaan</span>
                    <span class="value">{{ $quiz->attempts->count() ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="quick-links">
            <h6><i class="bi bi-lightning-charge"></i>Navigasi Cepat</h6>
            <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="quick-link-item">
                <i class="bi bi-eye"></i>
                <span>Lihat Detail Kuis</span>
            </a>
            <a href="{{ route('admin.stages.edit', $quiz->stage_id) }}" class="quick-link-item">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Stage</span>
            </a>
            <a href="{{ route('admin.quizzes.index') }}" class="quick-link-item">
                <i class="bi bi-grid"></i>
                <span>Semua Kuis</span>
            </a>
            <a href="{{ route('admin.quizzes.create', ['stage_id' => $quiz->stage_id]) }}" class="quick-link-item">
                <i class="bi bi-plus-circle"></i>
                <span>Buat Kuis Baru</span>
            </a>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
let questionIndex = {{ $quiz->questions->count() }};

document.getElementById('addQuestion').addEventListener('click', function() {
    const container = document.getElementById('questions-container');
    const newQuestion = document.createElement('div');
    newQuestion.className = 'question-item';
    newQuestion.dataset.index = questionIndex;
    newQuestion.innerHTML = `
        <div class="question-header">
            <div class="question-number">
                <div class="question-number-badge">${questionIndex + 1}</div>
                <span>Pertanyaan</span>
            </div>
            <button type="button" class="btn btn-remove-question remove-question">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        
        <div class="mb-4">
            <label class="modern-label">
                <i class="bi bi-chat-left-text"></i>
                Teks Pertanyaan <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   class="form-control question-input" 
                   name="questions[${questionIndex}][question_text]" 
                   placeholder="Tulis pertanyaan di sini..."
                   required>
        </div>
        
        <div class="mb-4">
            <label class="modern-label">
                <i class="bi bi-image"></i>
                Gambar Pertanyaan (Opsional)
            </label>
            <input type="file" 
                   class="form-control modern-input" 
                   name="questions[${questionIndex}][question_image]"
                   accept="image/jpeg,image/jpg,image/png"
                   onchange="previewQuestionImage(this, ${questionIndex})">
            <div class="image-preview-container" id="question-preview-${questionIndex}">
                <img src="" alt="Preview">
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearQuestionImage(${questionIndex})">
                    <i class="bi bi-x"></i> Hapus
                </button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="option-card">
                    <div class="option-badge">A</div>
                    <input type="text" class="form-control modern-input mb-2" name="questions[${questionIndex}][option_a]" placeholder="Teks pilihan A" required>
                    <input type="file" class="form-control form-control-sm" name="questions[${questionIndex}][option_a_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, ${questionIndex}, 'a')">
                    <div class="image-preview-container" id="option-preview-${questionIndex}-a">
                        <img src="" alt="Preview" style="max-width: 80px; max-height: 60px;">
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="option-card">
                    <div class="option-badge">B</div>
                    <input type="text" class="form-control modern-input mb-2" name="questions[${questionIndex}][option_b]" placeholder="Teks pilihan B" required>
                    <input type="file" class="form-control form-control-sm" name="questions[${questionIndex}][option_b_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, ${questionIndex}, 'b')">
                    <div class="image-preview-container" id="option-preview-${questionIndex}-b">
                        <img src="" alt="Preview" style="max-width: 80px; max-height: 60px;">
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="option-card">
                    <div class="option-badge">C</div>
                    <input type="text" class="form-control modern-input mb-2" name="questions[${questionIndex}][option_c]" placeholder="Teks pilihan C" required>
                    <input type="file" class="form-control form-control-sm" name="questions[${questionIndex}][option_c_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, ${questionIndex}, 'c')">
                    <div class="image-preview-container" id="option-preview-${questionIndex}-c">
                        <img src="" alt="Preview" style="max-width: 80px; max-height: 60px;">
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="option-card">
                    <div class="option-badge">D</div>
                    <input type="text" class="form-control modern-input mb-2" name="questions[${questionIndex}][option_d]" placeholder="Teks pilihan D" required>
                    <input type="file" class="form-control form-control-sm" name="questions[${questionIndex}][option_d_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, ${questionIndex}, 'd')">
                    <div class="image-preview-container" id="option-preview-${questionIndex}-d">
                        <img src="" alt="Preview" style="max-width: 80px; max-height: 60px;">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="correct-answer-section">
            <div class="label">
                <i class="bi bi-check-circle-fill"></i>
                Jawaban Benar <span class="text-danger">*</span>
            </div>
            <select class="form-select correct-answer-select" name="questions[${questionIndex}][correct_answer]" required>
                <option value="">Pilih jawaban benar...</option>
                <option value="a">A</option>
                <option value="b">B</option>
                <option value="c">C</option>
                <option value="d">D</option>
            </select>
        </div>
    `;
    
    container.appendChild(newQuestion);
    questionIndex++;
    updateQuestionNumbers();
    updateRemoveButtons();
});

document.getElementById('questions-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-question')) {
        e.target.closest('.question-item').remove();
        updateQuestionNumbers();
        updateRemoveButtons();
    }
});

function updateQuestionNumbers() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((q, index) => {
        const badge = q.querySelector('.question-number-badge');
        if (badge) badge.textContent = index + 1;
    });
    document.getElementById('questionCount').textContent = questions.length + ' Soal';
}

function updateRemoveButtons() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((q) => {
        const removeBtn = q.querySelector('.remove-question');
        if (removeBtn) {
            removeBtn.style.display = questions.length > 1 ? 'flex' : 'none';
        }
    });
}

updateRemoveButtons();

// Image preview functions
function previewQuestionImage(input, index) {
    const previewContainer = document.getElementById('question-preview-' + index);
    const img = previewContainer.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            previewContainer.classList.add('show');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearQuestionImage(index) {
    const previewContainer = document.getElementById('question-preview-' + index);
    const input = document.querySelector('input[name="questions[' + index + '][question_image]"]');
    
    previewContainer.classList.remove('show');
    previewContainer.querySelector('img').src = '';
    if (input) input.value = '';
}

function previewOptionImage(input, questionIndex, option) {
    const previewContainer = document.getElementById('option-preview-' + questionIndex + '-' + option);
    const img = previewContainer.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            previewContainer.classList.add('show');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm Delete Function
function confirmDelete(url, message) {
    if (confirm(message)) {
        const form = document.getElementById('delete-form');
        form.action = url;
        form.submit();
    }
}
</script>
@endpush
