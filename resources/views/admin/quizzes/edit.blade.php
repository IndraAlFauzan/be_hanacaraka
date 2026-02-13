@extends('admin.layout.app')

@section('title', 'Edit Kuis - ' . ($quiz->title ?? 'Kuis'))
@section('page-title', 'Edit Kuis')
@section('page-subtitle', 'Perbarui kuis dan pertanyaan')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Form Edit Kuis
            </div>
            <div class="card-body">
                <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST" id="quizForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stage_id" class="form-label">Stage <span class="text-danger">*</span></label>
                            <select class="form-select @error('stage_id') is-invalid @enderror" 
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
                        
                        <div class="col-md-3 mb-3">
                            <label for="title" class="form-label">Judul Kuis</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $quiz->title) }}"
                                   maxlength="100">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="passing_score" class="form-label">Passing Score (%) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('passing_score') is-invalid @enderror" 
                                   id="passing_score" 
                                   name="passing_score" 
                                   value="{{ old('passing_score', $quiz->passing_score) }}"
                                   min="0"
                                   max="100"
                                   required>
                            @error('passing_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Pertanyaan <span class="text-danger">*</span></h5>
                    
                    <div id="questions-container">
                        @foreach($quiz->questions as $index => $question)
                        <div class="question-item border rounded p-3 mb-3" data-index="{{ $index }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Pertanyaan {{ $index + 1 }}</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       name="questions[{{ $index }}][question_text]" 
                                       value="{{ $question->question_text }}"
                                       placeholder="Teks pertanyaan..."
                                       required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Gambar Pertanyaan (Opsional)</label>
                                @if($question->question_image_url)
                                <div class="mb-2">
                                    <img src="{{ $question->question_image_url }}" alt="Current" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    <input type="hidden" name="questions[{{ $index }}][existing_question_image]" value="{{ $question->question_image_url }}">
                                </div>
                                @endif
                                <input type="file" 
                                       class="form-control question-image-input" 
                                       name="questions[{{ $index }}][question_image]"
                                       accept="image/jpeg,image/jpg,image/png"
                                       onchange="previewQuestionImage(this, {{ $index }})">
                                <div class="question-image-preview mt-2" id="question-preview-{{ $index }}" style="display: none;">
                                    <img src="" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                                    <button type="button" class="btn btn-sm btn-danger ms-2" onclick="clearQuestionImage({{ $index }})">
                                        <i class="bi bi-x"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilihan A <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-1" name="questions[{{ $index }}][option_a]" value="{{ $question->option_a }}" placeholder="Teks pilihan A" required>
                                    @if($question->option_a_image_url)
                                    <div class="mb-1">
                                        <img src="{{ $question->option_a_image_url }}" alt="Current" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                        <input type="hidden" name="questions[{{ $index }}][existing_option_a_image]" value="{{ $question->option_a_image_url }}">
                                    </div>
                                    @endif
                                    <input type="file" class="form-control form-control-sm" name="questions[{{ $index }}][option_a_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, {{ $index }}, 'a')">
                                    <div class="option-image-preview mt-1" id="option-preview-{{ $index }}-a" style="display: none;">
                                        <img src="" alt="Preview" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilihan B <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-1" name="questions[{{ $index }}][option_b]" value="{{ $question->option_b }}" placeholder="Teks pilihan B" required>
                                    @if($question->option_b_image_url)
                                    <div class="mb-1">
                                        <img src="{{ $question->option_b_image_url }}" alt="Current" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                        <input type="hidden" name="questions[{{ $index }}][existing_option_b_image]" value="{{ $question->option_b_image_url }}">
                                    </div>
                                    @endif
                                    <input type="file" class="form-control form-control-sm" name="questions[{{ $index }}][option_b_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, {{ $index }}, 'b')">
                                    <div class="option-image-preview mt-1" id="option-preview-{{ $index }}-b" style="display: none;">
                                        <img src="" alt="Preview" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilihan C <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-1" name="questions[{{ $index }}][option_c]" value="{{ $question->option_c }}" placeholder="Teks pilihan C" required>
                                    @if($question->option_c_image_url)
                                    <div class="mb-1">
                                        <img src="{{ $question->option_c_image_url }}" alt="Current" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                        <input type="hidden" name="questions[{{ $index }}][existing_option_c_image]" value="{{ $question->option_c_image_url }}">
                                    </div>
                                    @endif
                                    <input type="file" class="form-control form-control-sm" name="questions[{{ $index }}][option_c_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, {{ $index }}, 'c')">
                                    <div class="option-image-preview mt-1" id="option-preview-{{ $index }}-c" style="display: none;">
                                        <img src="" alt="Preview" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilihan D <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-1" name="questions[{{ $index }}][option_d]" value="{{ $question->option_d }}" placeholder="Teks pilihan D" required>
                                    @if($question->option_d_image_url)
                                    <div class="mb-1">
                                        <img src="{{ $question->option_d_image_url }}" alt="Current" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                        <input type="hidden" name="questions[{{ $index }}][existing_option_d_image]" value="{{ $question->option_d_image_url }}">
                                    </div>
                                    @endif
                                    <input type="file" class="form-control form-control-sm" name="questions[{{ $index }}][option_d_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, {{ $index }}, 'd')">
                                    <div class="option-image-preview mt-1" id="option-preview-{{ $index }}-d" style="display: none;">
                                        <img src="" alt="Preview" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                <label class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
                                <select class="form-select" name="questions[{{ $index }}][correct_answer]" required>
                                    <option value="a" {{ $question->correct_answer == 'a' ? 'selected' : '' }}>A</option>
                                    <option value="b" {{ $question->correct_answer == 'b' ? 'selected' : '' }}>B</option>
                                    <option value="c" {{ $question->correct_answer == 'c' ? 'selected' : '' }}>C</option>
                                    <option value="d" {{ $question->correct_answer == 'd' ? 'selected' : '' }}>D</option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary mb-3" id="addQuestion">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Pertanyaan
                    </button>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Kuis
                        </button>
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="button" 
                                class="btn btn-danger ms-auto" 
                                onclick="if(confirm('Yakin ingin menghapus kuis ini?')) document.getElementById('delete-form').submit()">
                            <i class="bi bi-trash me-2"></i>Hapus Kuis
                        </button>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let questionIndex = {{ $quiz->questions->count() }};

document.getElementById('addQuestion').addEventListener('click', function() {
    const container = document.getElementById('questions-container');
    const newQuestion = document.createElement('div');
    newQuestion.className = 'question-item border rounded p-3 mb-3';
    newQuestion.dataset.index = questionIndex;
    newQuestion.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Pertanyaan ${questionIndex + 1}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control" 
                   name="questions[${questionIndex}][question_text]" 
                   placeholder="Teks pertanyaan..."
                   required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Gambar Pertanyaan (Opsional)</label>
            <input type="file" 
                   class="form-control question-image-input" 
                   name="questions[${questionIndex}][question_image]"
                   accept="image/jpeg,image/jpg,image/png"
                   onchange="previewQuestionImage(this, ${questionIndex})">
            <div class="question-image-preview mt-2" id="question-preview-${questionIndex}" style="display: none;">
                <img src="" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                <button type="button" class="btn btn-sm btn-danger ms-2" onclick="clearQuestionImage(${questionIndex})">
                    <i class="bi bi-x"></i> Hapus
                </button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Pilihan A <span class="text-danger">*</span></label>
                <input type="text" class="form-control mb-1" name="questions[${questionIndex}][option_a]" placeholder="Teks pilihan A" required>
                <input type="file" class="form-control form-control-sm" name="questions[${questionIndex}][option_a_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, ${questionIndex}, 'a')">
                <div class="option-image-preview mt-1" id="option-preview-${questionIndex}-a" style="display: none;">
                    <img src="" alt="Preview" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Pilihan B <span class="text-danger">*</span></label>
                <input type="text" class="form-control mb-1" name="questions[${questionIndex}][option_b]" placeholder="Teks pilihan B" required>
                <input type="file" class="form-control form-control-sm" name="questions[${questionIndex}][option_b_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, ${questionIndex}, 'b')">
                <div class="option-image-preview mt-1" id="option-preview-${questionIndex}-b" style="display: none;">
                    <img src="" alt="Preview" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Pilihan C <span class="text-danger">*</span></label>
                <input type="text" class="form-control mb-1" name="questions[${questionIndex}][option_c]" placeholder="Teks pilihan C" required>
                <input type="file" class="form-control form-control-sm" name="questions[${questionIndex}][option_c_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, ${questionIndex}, 'c')">
                <div class="option-image-preview mt-1" id="option-preview-${questionIndex}-c" style="display: none;">
                    <img src="" alt="Preview" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Pilihan D <span class="text-danger">*</span></label>
                <input type="text" class="form-control mb-1" name="questions[${questionIndex}][option_d]" placeholder="Teks pilihan D" required>
                <input type="file" class="form-control form-control-sm" name="questions[${questionIndex}][option_d_image]" accept="image/jpeg,image/jpg,image/png" onchange="previewOptionImage(this, ${questionIndex}, 'd')">
                <div class="option-image-preview mt-1" id="option-preview-${questionIndex}-d" style="display: none;">
                    <img src="" alt="Preview" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                </div>
            </div>
        </div>
        
        <div class="mt-2">
            <label class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
            <select class="form-select" name="questions[${questionIndex}][correct_answer]" required>
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
        q.querySelector('h6').textContent = `Pertanyaan ${index + 1}`;
    });
}

function updateRemoveButtons() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((q, index) => {
        const removeBtn = q.querySelector('.remove-question');
        if (questions.length > 1) {
            removeBtn.style.display = 'block';
        } else {
            removeBtn.style.display = 'none';
        }
    });
}

updateRemoveButtons();

// Image preview functions
function previewQuestionImage(input, index) {
    const previewContainer = document.getElementById(`question-preview-${index}`);
    const img = previewContainer.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearQuestionImage(index) {
    const previewContainer = document.getElementById(`question-preview-${index}`);
    const input = document.querySelector(`input[name="questions[${index}][question_image]"]`);
    
    previewContainer.style.display = 'none';
    previewContainer.querySelector('img').src = '';
    input.value = '';
}

function previewOptionImage(input, questionIndex, option) {
    const previewContainer = document.getElementById(`option-preview-${questionIndex}-${option}`);
    const img = previewContainer.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
