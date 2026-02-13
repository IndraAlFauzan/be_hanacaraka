@extends('admin.layout.app')

@section('title', 'Detail Kuis')
@section('page-title', 'Detail Kuis')
@section('page-subtitle', $quiz->title ?? 'Kuis ' . $quiz->stage->title)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-info-circle me-2"></i>Informasi Kuis</span>
                    <div>
                        <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">ID Kuis:</th>
                                <td>#{{ $quiz->id }}</td>
                            </tr>
                            <tr>
                                <th>Judul:</th>
                                <td>{{ $quiz->title ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Stage:</th>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $quiz->stage->title }} (Level {{ $quiz->stage->level->level_number }})
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Passing Score:</th>
                                <td><span class="badge bg-success">{{ $quiz->passing_score }}%</span></td>
                            </tr>
                            <tr>
                                <th>Total Pertanyaan:</th>
                                <td><span class="badge bg-primary">{{ $quiz->questions->count() }} soal</span></td>
                            </tr>
                            <tr>
                                <th>Dibuat:</th>
                                <td>{{ $quiz->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Daftar Pertanyaan</h5>

                @foreach($quiz->questions as $index => $question)
                <div class="card mb-3 border">
                    <div class="card-header bg-light">
                        <strong>Pertanyaan {{ $index + 1 }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="mb-2"><strong>{{ $question->question_text }}</strong></p>
                                
                                @if($question->question_image_url)
                                <div class="mb-3">
                                    <img src="{{ $question->question_image_url }}" 
                                         alt="Question Image" 
                                         class="img-thumbnail" 
                                         style="max-width: 300px; max-height: 300px;">
                                </div>
                                @endif

                                <div class="row mt-3">
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-2 {{ $question->correct_answer === 'a' ? 'border-success bg-success bg-opacity-10' : '' }}">
                                            <strong>A.</strong> {{ $question->option_a }}
                                            @if($question->option_a_image_url)
                                            <div class="mt-2">
                                                <img src="{{ $question->option_a_image_url }}" 
                                                     alt="Option A" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 150px; max-height: 150px;">
                                            </div>
                                            @endif
                                            @if($question->correct_answer === 'a')
                                            <span class="badge bg-success mt-2">Jawaban Benar</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-2 {{ $question->correct_answer === 'b' ? 'border-success bg-success bg-opacity-10' : '' }}">
                                            <strong>B.</strong> {{ $question->option_b }}
                                            @if($question->option_b_image_url)
                                            <div class="mt-2">
                                                <img src="{{ $question->option_b_image_url }}" 
                                                     alt="Option B" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 150px; max-height: 150px;">
                                            </div>
                                            @endif
                                            @if($question->correct_answer === 'b')
                                            <span class="badge bg-success mt-2">Jawaban Benar</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-2 {{ $question->correct_answer === 'c' ? 'border-success bg-success bg-opacity-10' : '' }}">
                                            <strong>C.</strong> {{ $question->option_c }}
                                            @if($question->option_c_image_url)
                                            <div class="mt-2">
                                                <img src="{{ $question->option_c_image_url }}" 
                                                     alt="Option C" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 150px; max-height: 150px;">
                                            </div>
                                            @endif
                                            @if($question->correct_answer === 'c')
                                            <span class="badge bg-success mt-2">Jawaban Benar</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-2 {{ $question->correct_answer === 'd' ? 'border-success bg-success bg-opacity-10' : '' }}">
                                            <strong>D.</strong> {{ $question->option_d }}
                                            @if($question->option_d_image_url)
                                            <div class="mt-2">
                                                <img src="{{ $question->option_d_image_url }}" 
                                                     alt="Option D" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 150px; max-height: 150px;">
                                            </div>
                                            @endif
                                            @if($question->correct_answer === 'd')
                                            <span class="badge bg-success mt-2">Jawaban Benar</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection
