@extends('admin.layout.app')

@section('title', 'Kelola Kuis')
@section('page-title', 'Kelola Kuis')
@section('page-subtitle', 'Daftar semua kuis pembelajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-patch-question-fill"></i>
            <span>Daftar Kuis</span>
            <span class="badge badge-soft-primary ms-2">{{ $quizzes->total() ?? $quizzes->count() }} total</span>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <select class="form-select form-select-sm" style="width: 220px;" onchange="window.location.href = this.value;">
                <option value="{{ route('admin.quizzes.index') }}" {{ !request('stage_id') ? 'selected' : '' }}>📁 Semua Stage</option>
                @foreach($stages as $stage)
                    <option value="{{ route('admin.quizzes.index', ['stage_id' => $stage->id]) }}" 
                            {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                        {{ Str::limit($stage->title, 25) }} (L{{ $stage->level->level_number }})
                    </option>
                @endforeach
            </select>
            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Kuis</span>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Judul Kuis</th>
                        <th>Stage</th>
                        <th style="width: 120px;">Passing Score</th>
                        <th style="width: 120px;">Pertanyaan</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                    <tr>
                        <td>
                            <span class="text-muted fw-medium">#{{ $quiz->id }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $quiz->title ?? 'Kuis ' . $quiz->stage->title }}</div>
                        </td>
                        <td>
                            <span class="badge badge-soft-info">
                                <i class="bi bi-collection me-1"></i>{{ $quiz->stage->title }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                    <div class="progress-bar bg-success" style="width: {{ $quiz->passing_score }}%"></div>
                                </div>
                                <span class="fw-semibold text-success">{{ $quiz->passing_score }}%</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-soft-warning">
                                <i class="bi bi-question-circle me-1"></i>{{ $quiz->questions_count }} soal
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.quizzes.show', $quiz->id) }}" 
                                   class="btn btn-sm btn-icon btn-outline-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" 
                                   class="btn btn-sm btn-icon btn-outline-primary" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-icon btn-outline-danger" 
                                        data-bs-toggle="tooltip" 
                                        title="Hapus"
                                        onclick="confirmDelete('/admin/quizzes/{{ $quiz->id }}', 'Kuis beserta semua pertanyaan dan hasil akan dihapus.')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-patch-question"></i>
                                </div>
                                <h5>Belum ada kuis</h5>
                                <p>Tambahkan kuis untuk menguji pemahaman pengguna.</p>
                                <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Tambah Kuis
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($quizzes->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
            {{ $quizzes->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
