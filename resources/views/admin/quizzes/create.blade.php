@extends('admin.layout.app')

@section('title', 'Tambah Kuis Baru')
@section('page-title', 'Tambah Kuis Baru')
@section('page-subtitle', 'Buat kuis dengan pertanyaan pilihan ganda')

@push('styles')
<style>
    .form-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 16px 16px 0 0 !important;
        padding: 20px 24px;
    }
    .info-card {
        border-radius: 16px;
        border: 1px solid #e9ecef;
        background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
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
        border-color: #e74c3c;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.15);
    }
    .btn-submit {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 10px;
        color: #fff;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        color: #fff;
    }
    .question-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid #e9ecef;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .question-item:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e9ecef;
    }
    .question-number {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .question-number-badge {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .option-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }
    .option-card:hover {
        border-color: #e74c3c;
    }
    .option-label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    .option-badge {
        width: 28px;
        height: 28px;
        background: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        color: #666;
    }
    .image-preview {
        margin-top: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 10px;
        display: none;
    }
    .image-preview.show {
        display: block;
    }
    .image-preview img {
        border-radius: 8px;
        max-height: 150px;
    }
    .btn-add-question {
        border: 2px dashed #e74c3c;
        color: #e74c3c;
        background: transparent;
        padding: 16px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-add-question:hover {
        background: rgba(231, 76, 60, 0.1);
        border-color: #c0392b;
        color: #c0392b;
    }
    .correct-answer-section {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
    }
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .info-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-list li:last-child {
        border-bottom: none;
    }
    .info-list li i {
        color: #e74c3c;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Kuis
    </a>
</div>

<div class="row">
    <div class="col-lg-9">
        <div class="card form-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-question-circle-fill"></i>
                    <span class="fw-semibold">Tambah Kuis Baru</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.quizzes.store') }}" method="POST" id="quizForm" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-5 mb-4">
                            <label for="stage_id" class="form-label">
                                Stage Tujuan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('stage_id') is-invalid @enderror" 
                                    id="stage_id" 
                                    name="stage_id" 
                                    required>
                                <option value="">Pilih Stage...</option>
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}" {{ old('stage_id', request('stage_id')) == $stage->id ? 'selected' : '' }}>
                                        {{ $stage->title }} (Level {{ $stage->level->level_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('stage_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label for="title" class="form-label">Judul Kuis</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   maxlength="100"
                                   placeholder="Opsional">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-4">
                            <label for="passing_score" class="form-label">
                                Passing Score <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" 
                                       name="passing_score" 
                                       value="{{ old('passing_score', 60) }}"
                                       min="0"
                                       max="100"
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('passing_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-list-ol text-danger me-2"></i>Daftar Pertanyaan
                    </h5>
                    
                    <div id="questions-container">
                        <div class="question-item" data-index="0">
                            <div class="question-header">
                                <div class="question-number">
                                    <div class="question-number-badge">1</div>
                                    <span class="fw-semibold">Pertanyaan</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-question" style="display: none;">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="questions[0][question_text]" placeholder="Tulis pertanyaan di sini..." required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label"><i class="bi bi-image text-muted me-1"></i>Gambar (Opsional)</label>
                                <input type="file" class="form-control" name="questions[0][question_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewQuestionImage(this, 0)">
                                <div class="image-preview" id="question-preview-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="" alt="Preview">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearQuestionImage(0)"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="option-card">
                                        <div class="option-label"><span class="option-badge">A</span><span class="form-label mb-0">Pilihan A <span class="text-danger">*</span></span></div>
                                        <input type="text" class="form-control mb-2" name="questions[0][option_a]" placeholder="Teks pilihan A" required>
                                        <input type="file" class="form-control form-control-sm" name="questions[0][option_a_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, 0, 'a')">
                                        <div class="image-preview" id="option-preview-0-a"><img src="" alt="Preview" style="max-height: 80px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="option-card">
                                        <div class="option-label"><span class="option-badge">B</span><span class="form-label mb-0">Pilihan B <span class="text-danger">*</span></span></div>
                                        <input type="text" class="form-control mb-2" name="questions[0][option_b]" placeholder="Teks pilihan B" required>
                                        <input type="file" class="form-control form-control-sm" name="questions[0][option_b_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, 0, 'b')">
                                        <div class="image-preview" id="option-preview-0-b"><img src="" alt="Preview" style="max-height: 80px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="option-card">
                                        <div class="option-label"><span class="option-badge">C</span><span class="form-label mb-0">Pilihan C <span class="text-danger">*</span></span></div>
                                        <input type="text" class="form-control mb-2" name="questions[0][option_c]" placeholder="Teks pilihan C" required>
                                        <input type="file" class="form-control form-control-sm" name="questions[0][option_c_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, 0, 'c')">
                                        <div class="image-preview" id="option-preview-0-c"><img src="" alt="Preview" style="max-height: 80px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="option-card">
                                        <div class="option-label"><span class="option-badge">D</span><span class="form-label mb-0">Pilihan D <span class="text-danger">*</span></span></div>
                                        <input type="text" class="form-control mb-2" name="questions[0][option_d]" placeholder="Teks pilihan D" required>
                                        <input type="file" class="form-control form-control-sm" name="questions[0][option_d_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, 0, 'd')">
                                        <div class="image-preview" id="option-preview-0-d"><img src="" alt="Preview" style="max-height: 80px;"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="correct-answer-section">
                                <label class="form-label mb-2"><i class="bi bi-check-circle-fill text-success me-1"></i>Jawaban Benar <span class="text-danger">*</span></label>
                                <select class="form-select" name="questions[0][correct_answer]" required style="max-width: 200px;">
                                    <option value="">Pilih jawaban...</option>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-add-question" id="addQuestion">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Pertanyaan Baru
                        </button>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-lg me-2"></i>Simpan Kuis
                        </button>
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card info-card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-lightbulb text-warning"></i>
                    <span class="fw-semibold">Panduan</span>
                </div>
            </div>
            <div class="card-body">
                <ul class="info-list">
                    <li><i class="bi bi-check-circle"></i><span class="small">Minimal 1 pertanyaan per kuis</span></li>
                    <li><i class="bi bi-check-circle"></i><span class="small">4 pilihan jawaban per soal</span></li>
                    <li><i class="bi bi-check-circle"></i><span class="small">Passing score = nilai minimum lulus</span></li>
                    <li><i class="bi bi-check-circle"></i><span class="small">Gambar bersifat opsional</span></li>
                </ul>
            </div>
        </div>
        
        <div class="card info-card mt-3">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart text-primary"></i>
                    <span class="fw-semibold">Total Soal</span>
                </div>
            </div>
            <div class="card-body text-center">
                <div class="fs-2 fw-bold text-danger" id="question-count">1</div>
                <small class="text-muted">Pertanyaan</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let questionIndex = 1;

document.getElementById('addQuestion').addEventListener('click', function() {
    const container = document.getElementById('questions-container');
    const newQuestion = document.createElement('div');
    newQuestion.className = 'question-item';
    newQuestion.dataset.index = questionIndex;
    newQuestion.innerHTML = \`
        <div class="question-header">
            <div class="question-number">
                <div class="question-number-badge">\${questionIndex + 1}</div>
                <span class="fw-semibold">Pertanyaan</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-question"><i class="bi bi-trash me-1"></i>Hapus</button>
        </div>
        <div class="mb-4">
            <label class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="questions[\${questionIndex}][question_text]" placeholder="Tulis pertanyaan di sini..." required>
        </div>
        <div class="mb-4">
            <label class="form-label"><i class="bi bi-image text-muted me-1"></i>Gambar (Opsional)</label>
            <input type="file" class="form-control" name="questions[\${questionIndex}][question_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewQuestionImage(this, \${questionIndex})">
            <div class="image-preview" id="question-preview-\${questionIndex}">
                <div class="d-flex align-items-center gap-3">
                    <img src="" alt="Preview">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearQuestionImage(\${questionIndex})"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"><div class="option-card"><div class="option-label"><span class="option-badge">A</span><span class="form-label mb-0">Pilihan A <span class="text-danger">*</span></span></div><input type="text" class="form-control mb-2" name="questions[\${questionIndex}][option_a]" placeholder="Teks pilihan A" required><input type="file" class="form-control form-control-sm" name="questions[\${questionIndex}][option_a_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, \${questionIndex}, 'a')"><div class="image-preview" id="option-preview-\${questionIndex}-a"><img src="" alt="Preview" style="max-height: 80px;"></div></div></div>
            <div class="col-md-6"><div class="option-card"><div class="option-label"><span class="option-badge">B</span><span class="form-label mb-0">Pilihan B <span class="text-danger">*</span></span></div><input type="text" class="form-control mb-2" name="questions[\${questionIndex}][option_b]" placeholder="Teks pilihan B" required><input type="file" class="form-control form-control-sm" name="questions[\${questionIndex}][option_b_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, \${questionIndex}, 'b')"><div class="image-preview" id="option-preview-\${questionIndex}-b"><img src="" alt="Preview" style="max-height: 80px;"></div></div></div>
            <div class="col-md-6"><div class="option-card"><div class="option-label"><span class="option-badge">C</span><span class="form-label mb-0">Pilihan C <span class="text-danger">*</span></span></div><input type="text" class="form-control mb-2" name="questions[\${questionIndex}][option_c]" placeholder="Teks pilihan C" required><input type="file" class="form-control form-control-sm" name="questions[\${questionIndex}][option_c_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, \${questionIndex}, 'c')"><div class="image-preview" id="option-preview-\${questionIndex}-c"><img src="" alt="Preview" style="max-height: 80px;"></div></div></div>
            <div class="col-md-6"><div class="option-card"><div class="option-label"><span class="option-badge">D</span><span class="form-label mb-0">Pilihan D <span class="text-danger">*</span></span></div><input type="text" class="form-control mb-2" name="questions[\${questionIndex}][option_d]" placeholder="Teks pilihan D" required><input type="file" class="form-control form-control-sm" name="questions[\${questionIndex}][option_d_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, \${questionIndex}, 'd')"><div class="image-preview" id="option-preview-\${questionIndex}-d"><img src="" alt="Preview" style="max-height: 80px;"></div></div></div>
        </div>
        <div class="correct-answer-section">
            <label class="form-label mb-2"><i class="bi bi-check-circle-fill text-success me-1"></i>Jawaban Benar <span class="text-danger">*</span></label>
            <select class="form-select" name="questions[\${questionIndex}][correct_answer]" required style="max-width: 200px;"><option value="">Pilih...</option><option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option></select>
        </div>
    \`;
    container.appendChild(newQuestion);
    questionIndex++;
    updateRemoveButtons();
    updateQuestionCount();
});

document.getElementById('questions-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-question')) {
        e.target.closest('.question-item').remove();
        updateQuestionNumbers();
        updateRemoveButtons();
        updateQuestionCount();
    }
});

function updateQuestionNumbers() {
    document.querySelectorAll('.question-item').forEach((q, i) => {
        q.querySelector('.question-number-badge').textContent = i + 1;
    });
}

function updateRemoveButtons() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach(q => {
        q.querySelector('.remove-question').style.display = questions.length > 1 ? 'block' : 'none';
    });
}

function updateQuestionCount() {
    document.getElementById('question-count').textContent = document.querySelectorAll('.question-item').length;
}

function previewQuestionImage(input, index) {
    const container = document.getElementById(\`question-preview-\${index}\`);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { container.querySelector('img').src = e.target.result; container.classList.add('show'); };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearQuestionImage(index) {
    const container = document.getElementById(\`question-preview-\${index}\`);
    container.classList.remove('show');
    container.querySelector('img').src = '';
    document.querySelector(\`input[name="questions[\${index}][question_image]"]\`).value = '';
}

function previewOptionImage(input, qIndex, opt) {
    const container = document.getElementById(\`option-preview-\${qIndex}-\${opt}\`);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { container.querySelector('img').src = e.target.result; container.classList.add('show'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
