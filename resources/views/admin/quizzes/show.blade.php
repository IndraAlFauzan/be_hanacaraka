@extends('admin.layout.app')

@section('title', 'Detail Kuis')
@section('page-title', 'Detail Kuis')
@section('page-subtitle', $quiz->title ?? 'Kuis ' . $quiz->stage->title)

@push('styles')
<style>
    .quiz-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 24px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .quiz-info-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .quiz-stat {
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        backdrop-filter: blur(10px);
    }
    .quiz-stat h3 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }
    .quiz-stat small {
        opacity: 0.9;
        font-size: 0.8rem;
    }
    .question-card {
        border: 1px solid #e9ecef;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .question-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border-color: #667eea;
    }
    .question-header {
        background: #f8f9fc;
        padding: 16px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .question-number {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .question-body {
        padding: 20px;
    }
    .question-text {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 16px;
        line-height: 1.6;
    }
    .option-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
        background: #fff;
    }
    .option-item.correct {
        background: linear-gradient(90deg, rgba(17, 153, 142, 0.08) 0%, rgba(56, 239, 125, 0.08) 100%);
        border-color: #11998e;
    }
    .option-item.correct .option-badge {
        background: #11998e;
        color: white;
    }
    .option-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        color: #6c757d;
        flex-shrink: 0;
    }
    .option-content {
        flex: 1;
    }
    .option-text {
        font-size: 0.9rem;
        color: #4a4a4a;
    }
    .correct-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: #11998e;
        font-weight: 600;
        margin-top: 6px;
    }
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Quiz Info Card -->
    <div class="col-12">
        <div class="quiz-info-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <span class="badge bg-white bg-opacity-25 mb-2">
                        <i class="bi bi-collection me-1"></i>{{ $quiz->stage->title }}
                    </span>
                    <h2 class="mb-2 fw-bold">{{ $quiz->title ?? 'Kuis ' . $quiz->stage->title }}</h2>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-stack me-1"></i>Level {{ $quiz->stage->level->level_number }}: {{ $quiz->stage->level->title }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-light btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Kuis
                    </a>
                    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            
            <div class="row g-3 mt-3">
                <div class="col-md-3 col-6">
                    <div class="quiz-stat">
                        <h3>#{{ $quiz->id }}</h3>
                        <small>ID Kuis</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="quiz-stat">
                        <h3>{{ $quiz->passing_score }}%</h3>
                        <small>Passing Score</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="quiz-stat">
                        <h3>{{ $quiz->questions->count() }}</h3>
                        <small>Total Soal</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="quiz-stat">
                        <h3>{{ $quiz->created_at->format('d/m/Y') }}</h3>
                        <small>Tanggal Dibuat</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-list-ol me-2 text-primary"></i>Daftar Pertanyaan
            </h5>
            <span class="badge badge-soft-primary">{{ $quiz->questions->count() }} pertanyaan</span>
        </div>

        <div class="row g-4">
            @foreach($quiz->questions as $index => $question)
            <div class="col-md-6">
                <div class="question-card">
                    <div class="question-header">
                        <div class="question-number">{{ $index + 1 }}</div>
                        <span class="text-muted small">Pertanyaan {{ $index + 1 }} dari {{ $quiz->questions->count() }}</span>
                    </div>
                    <div class="question-body">
                        <div class="question-text">{{ $question->question_text }}</div>
                        
                        @if($question->question_image_url)
                        <div class="mb-3">
                            <img src="{{ $question->question_image_url }}" 
                                 alt="Question Image" 
                                 class="img-fluid rounded-3" 
                                 style="max-height: 200px; border: 2px solid #e9ecef;">
                        </div>
                        @endif

                        <div class="options-list">
                            @foreach(['a', 'b', 'c', 'd'] as $opt)
                            <div class="option-item {{ $question->correct_answer === $opt ? 'correct' : '' }}">
                                <div class="option-badge">{{ strtoupper($opt) }}</div>
                                <div class="option-content">
                                    <div class="option-text">{{ $question->{'option_' . $opt} }}</div>
                                    @if($question->{'option_' . $opt . '_image_url'})
                                    <img src="{{ $question->{'option_' . $opt . '_image_url'} }}" 
                                         alt="Option {{ strtoupper($opt) }}" 
                                         class="img-thumbnail mt-2" 
                                         style="max-width: 120px; max-height: 80px;">
                                    @endif
                                    @if($question->correct_answer === $opt)
                                    <div class="correct-indicator">
                                        <i class="bi bi-check-circle-fill"></i>Jawaban Benar
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
