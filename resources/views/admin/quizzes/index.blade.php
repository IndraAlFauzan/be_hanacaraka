@extends('admin.layout.app')

@section('title', 'Kelola Kuis')
@section('page-title', 'Kelola Kuis')
@section('page-subtitle', 'Daftar semua kuis')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span><i class="bi bi-question-circle me-2"></i>Daftar Kuis</span>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value;">
                        <option value="{{ route('admin.quizzes.index') }}" {{ !request('stage_id') ? 'selected' : '' }}>Semua Stage</option>
                        @foreach($stages as $stage)
                            <option value="{{ route('admin.quizzes.index', ['stage_id' => $stage->id]) }}" 
                                    {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                                {{ $stage->title }} (Level {{ $stage->level->level_number }})
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('admin.quizzes.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Kuis
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Stage</th>
                        <th>Passing Score</th>
                        <th>Pertanyaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                    <tr>
                        <td><small class="text-muted">#{{ $quiz->id }}</small></td>
                        <td><strong>{{ $quiz->title ?? 'Kuis ' . $quiz->stage->title }}</strong></td>
                        <td>
                            <span class="badge bg-info">
                                {{ $quiz->stage->title }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $quiz->passing_score }}%</span>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $quiz->questions_count }} soal</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.quizzes.show', $quiz->id) }}" 
                                   class="btn btn-outline-info" 
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" 
                                   class="btn btn-outline-primary" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        onclick="deleteQuiz({{ $quiz->id }})"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada kuis yang dibuat</p>
                            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Kuis Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($quizzes->hasPages())
        <div class="mt-3">
            {{ $quizzes->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteQuiz(id) {
    if (confirm('Yakin ingin menghapus kuis ini? Semua pertanyaan dan hasil kuis akan ikut terhapus!')) {
        const form = document.getElementById('delete-form');
        form.action = '/admin/quizzes/' + id;
        form.submit();
    }
}
</script>
@endsection
