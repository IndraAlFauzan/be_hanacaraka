@extends('admin.layout.app')

@section('title', 'Kelola Stages')
@section('page-title', 'Kelola Stages')
@section('page-subtitle', 'Daftar semua stage pembelajaran')

@push('styles')
<style>
    /* Modern Card Enhancement */
    .card-stages {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    
    .card-stages .card-header {
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        border-bottom: 1px solid rgba(102, 126, 234, 0.08);
        padding: 1.25rem 1.5rem;
    }
    
    /* Header Responsive */
    .header-content {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .header-title {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }
    
    .header-title i {
        font-size: 1.25rem;
        color: #667eea;
    }
    
    .header-title span {
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .header-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem;
    }
    
    /* Custom Select */
    .filter-select {
        min-width: 180px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 2rem 0.5rem 0.875rem;
        font-size: 0.875rem;
        background-color: #fff;
        transition: all 0.2s ease;
    }
    
    .filter-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }
    
    /* Modern Table */
    .table-modern {
        margin: 0;
    }
    
    .table-modern thead th {
        background: #f8fafc;
        border: none;
        padding: 1rem 1.25rem;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    
    .table-modern thead th:first-child {
        padding-left: 1.5rem;
    }
    
    .table-modern thead th:last-child {
        padding-right: 1.5rem;
    }
    
    .table-modern tbody tr {
        transition: all 0.15s ease;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.03) 0%, rgba(102, 126, 234, 0.06) 100%);
    }
    
    .table-modern tbody tr:last-child {
        border-bottom: none;
    }
    
    .table-modern tbody td {
        padding: 1.125rem 1.25rem;
        vertical-align: middle;
        border: none;
    }
    
    .table-modern tbody td:first-child {
        padding-left: 1.5rem;
    }
    
    .table-modern tbody td:last-child {
        padding-right: 1.5rem;
    }
    
    /* Stage Number Badge */
    .stage-number {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    /* Stage Title */
    .stage-title {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.925rem;
    }
    
    /* XP Value */
    .xp-value {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-weight: 600;
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.8125rem;
    }
    
    /* Content Badges */
    .content-badges {
        display: flex;
        gap: 0.375rem;
    }
    
    .content-badge {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    /* Action Buttons */
    .action-btn-group {
        display: flex;
        gap: 0.5rem;
    }
    
    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
    
    .action-btn-edit {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        border: 1px solid transparent;
    }
    
    .action-btn-edit:hover {
        background: #667eea;
        color: #fff;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
    }
    
    .action-btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid transparent;
    }
    
    .action-btn-delete:hover {
        background: #ef4444;
        color: #fff;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
    }
    
    /* Modern Empty State */
    .empty-state-modern {
        text-align: center;
        padding: 5rem 2rem;
    }
    
    .empty-state-icon-wrapper {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        position: relative;
    }
    
    .empty-state-icon-wrapper::before {
        content: '';
        position: absolute;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 2px dashed rgba(102, 126, 234, 0.2);
        animation: spin 20s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .empty-state-icon-wrapper i {
        font-size: 3rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .empty-state-modern h5 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }
    
    .empty-state-modern p {
        color: #64748b;
        font-size: 0.925rem;
        margin-bottom: 1.5rem;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .empty-state-modern .btn-primary {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(102, 126, 234, 0.35);
    }
    
    /* Modern Pagination - Fix for Tailwind default */
    .pagination-modern {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        background: #fafbfc;
    }
    
    .pagination-modern nav > div:first-child {
        display: none !important; /* Hide mobile "Showing X to Y" */
    }
    
    .pagination-modern nav > div:last-child {
        width: 100%;
    }
    
    /* Hide giant SVG icons from Tailwind pagination */
    .pagination-modern svg {
        width: 16px !important;
        height: 16px !important;
    }
    
    /* Override Tailwind pagination styles */
    .pagination-modern nav span[aria-current="page"] span {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
        border-radius: 8px !important;
        padding: 0.5rem 0.875rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        border: none !important;
    }
    
    .pagination-modern nav a,
    .pagination-modern nav span > span {
        border-radius: 8px !important;
        padding: 0.5rem 0.875rem !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        border: none !important;
        background: #fff !important;
        color: #64748b !important;
        margin: 0 2px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .pagination-modern nav a:hover {
        background: #667eea !important;
        color: #fff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
    }
    
    /* Disabled state */
    .pagination-modern nav span[aria-disabled="true"] span {
        background: #f1f5f9 !important;
        color: #cbd5e1 !important;
        cursor: not-allowed;
        box-shadow: none !important;
    }
    
    .pagination-modern .pagination {
        gap: 0.375rem;
        margin: 0;
    }
    
    .pagination-modern .page-item .page-link {
        border: none;
        border-radius: 8px;
        padding: 0.5rem 0.875rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #64748b;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .pagination-modern .page-item .page-link:hover {
        background: #667eea;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
    }
    
    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
    }
    
    .pagination-modern .page-item.disabled .page-link {
        background: #f1f5f9;
        color: #cbd5e1;
        box-shadow: none;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: stretch;
        }
        
        .header-title {
            justify-content: center;
        }
        
        .header-actions {
            justify-content: center;
        }
        
        .filter-select {
            width: 100%;
            max-width: 280px;
        }
        
        .table-modern thead {
            display: none;
        }
        
        .table-modern tbody tr {
            display: block;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #fff;
        }
        
        .table-modern tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .table-modern tbody td:first-child {
            padding-left: 0;
        }
        
        .table-modern tbody td:last-child {
            padding-right: 0;
            border-bottom: none;
            justify-content: flex-end;
        }
        
        .table-modern tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
        }
        
        .empty-state-icon-wrapper {
            width: 100px;
            height: 100px;
        }
        
        .empty-state-icon-wrapper::before {
            width: 120px;
            height: 120px;
        }
        
        .empty-state-icon-wrapper i {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="card card-stages">
    <!-- Card Header -->
    <div class="card-header">
        <div class="header-content">
            <div class="header-title">
                <i class="bi bi-collection-fill"></i>
                <span>Daftar Stages</span>
                <span class="badge badge-soft-primary">{{ $stages->total() ?? $stages->count() }} total</span>
            </div>
            <div class="header-actions">
                <select class="form-select form-select-sm filter-select" onchange="window.location.href = this.value;">
                    <option value="{{ route('admin.stages.index') }}" {{ !request('level_id') ? 'selected' : '' }}>📁 Semua Level</option>
                    @foreach($levels as $level)
                        <option value="{{ route('admin.stages.index', ['level_id' => $level->id]) }}" 
                                {{ request('level_id') == $level->id ? 'selected' : '' }}>
                            Level {{ $level->level_number }}: {{ Str::limit($level->title, 20) }}
                        </option>
                    @endforeach
                </select>
                <a href="{{ route('admin.stages.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    <span>Tambah Stage</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Card Body -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No.</th>
                        <th>Judul Stage</th>
                        <th>Level</th>
                        <th style="width: 90px;">XP</th>
                        <th style="width: 100px;">Tipe</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 160px;">Konten</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stages as $stage)
                    <tr>
                        <td data-label="No.">
                            <div class="stage-number">
                                {{ $stage->stage_number }}
                            </div>
                        </td>
                        <td data-label="Judul">
                            <span class="stage-title">{{ $stage->title }}</span>
                        </td>
                        <td data-label="Level">
                            <span class="badge badge-soft-info">
                                <i class="bi bi-stack me-1"></i>Level {{ $stage->level->level_number }}
                            </span>
                        </td>
                        <td data-label="XP">
                            <span class="xp-value">
                                <i class="bi bi-lightning-fill"></i> {{ $stage->xp_reward }}
                            </span>
                        </td>
                        <td data-label="Tipe">
                            @php
                                $typeConfig = [
                                    'drawing' => ['class' => 'badge-soft-primary', 'icon' => 'bi-brush', 'label' => 'Drawing'],
                                    'quiz' => ['class' => 'badge-soft-warning', 'icon' => 'bi-patch-question', 'label' => 'Quiz'],
                                    'both' => ['class' => 'badge-soft-info', 'icon' => 'bi-collection', 'label' => 'Both']
                                ];
                                $type = $typeConfig[$stage->evaluation_type ?? 'drawing'];
                            @endphp
                            <span class="badge {{ $type['class'] }}">
                                <i class="bi {{ $type['icon'] }} me-1"></i>{{ $type['label'] }}
                            </span>
                        </td>
                        <td data-label="Status">
                            @if($stage->is_active)
                                <span class="badge badge-soft-success">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge badge-soft-secondary">
                                    <i class="bi bi-pause-circle me-1"></i>Off
                                </span>
                            @endif
                        </td>
                        <td data-label="Konten">
                            <div class="content-badges">
                                <span class="badge content-badge {{ $stage->materials_count > 0 ? 'badge-soft-primary' : 'badge-soft-secondary' }}" data-bs-toggle="tooltip" title="Materials">
                                    <i class="bi bi-journal-richtext"></i> {{ $stage->materials_count ?? 0 }}
                                </span>
                                <span class="badge content-badge {{ $stage->quizzes_count > 0 ? 'badge-soft-warning' : 'badge-soft-secondary' }}" data-bs-toggle="tooltip" title="Quizzes">
                                    <i class="bi bi-patch-question"></i> {{ $stage->quizzes_count ?? 0 }}
                                </span>
                                <span class="badge content-badge {{ $stage->evaluations_count > 0 ? 'badge-soft-success' : 'badge-soft-secondary' }}" data-bs-toggle="tooltip" title="Evaluations">
                                    <i class="bi bi-brush"></i> {{ $stage->evaluations_count ?? 0 }}
                                </span>
                            </div>
                        </td>
                        <td data-label="Aksi">
                            <div class="action-btn-group">
                                <a href="{{ route('admin.stages.edit', $stage->id) }}" 
                                   class="action-btn action-btn-edit" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="action-btn action-btn-delete" 
                                        data-bs-toggle="tooltip" 
                                        title="Hapus"
                                        onclick="confirmDelete('/admin/stages/{{ $stage->id }}', 'Stage &quot;{{ $stage->title }}&quot; beserta materi, kuis, dan evaluasi akan dihapus.')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-0 border-0">
                            <div class="empty-state-modern">
                                <div class="empty-state-icon-wrapper">
                                    <i class="bi bi-collection"></i>
                                </div>
                                <h5>Belum ada stage</h5>
                                <p>
                                    @if(request('level_id'))
                                        Level ini belum memiliki stage. Mulai tambahkan stage pertama untuk level ini.
                                    @else
                                        Mulai dengan membuat stage pertama untuk memulai pembelajaran.
                                    @endif
                                </p>
                                <a href="{{ route('admin.stages.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Tambah Stage Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($stages->hasPages())
        <div class="pagination-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                Menampilkan {{ $stages->firstItem() }} - {{ $stages->lastItem() }} dari {{ $stages->total() }} stage
            </div>
            <nav>
                {{ $stages->appends(request()->query())->onEachSide(1)->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection
