<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Hanacaraka</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            --sidebar-bg: linear-gradient(180deg, #1e1e2e 0%, #2d3561 100%);
            --topbar-height: 70px;
            --border-radius: 12px;
            --transition-speed: 0.3s;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0f2f5;
            overflow-x: hidden;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            padding: 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1050;
            transition: all var(--transition-speed) ease;
            box-shadow: 4px 0 25px rgba(0,0,0,0.1);
        }
        
        .sidebar .brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.15);
        }
        
        .sidebar .brand-logo {
            width: 42px;
            height: 42px;
            background: var(--primary-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .sidebar .brand-text h5 {
            color: #fff;
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }
        
        .sidebar .brand-text span {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .sidebar .nav-section {
            padding: 20px 16px 8px;
        }
        
        .sidebar .nav-section-title {
            color: rgba(255,255,255,0.4);
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin-bottom: 8px;
        }
        
        .sidebar .nav-item {
            margin: 4px 0;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.65);
            padding: 12px 16px;
            border-radius: 10px;
            transition: all var(--transition-speed) ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--primary-gradient);
            border-radius: 0 3px 3px 0;
            transition: height var(--transition-speed) ease;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            transform: translateX(3px);
        }
        
        .sidebar .nav-link:hover::before {
            height: 60%;
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(90deg, rgba(102,126,234,0.2) 0%, rgba(118,75,162,0.1) 100%);
            color: #fff;
        }
        
        .sidebar .nav-link.active::before {
            height: 70%;
        }
        
        .sidebar .nav-link i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }
        
        .sidebar .nav-link .nav-badge {
            margin-left: auto;
            background: var(--primary-gradient);
            color: #fff;
            font-size: 0.65rem;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 16px 20px;
        }
        
        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f0f2f5;
            transition: margin-left var(--transition-speed) ease;
        }
        
        /* ========== TOPBAR ========== */
        .topbar {
            background: #fff;
            padding: 0 32px;
            height: var(--topbar-height);
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        
        .topbar .page-info h5 {
            margin: 0;
            font-weight: 700;
            color: #1a1a2e;
            font-size: 1.15rem;
        }
        
        .topbar .page-info small {
            color: #6c757d;
            font-size: 0.8rem;
        }
        
        .topbar .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .topbar .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: #f8f9fa;
            border-radius: 50px;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }
        
        .topbar .user-profile:hover {
            background: #e9ecef;
        }
        
        .topbar .user-avatar {
            width: 38px;
            height: 38px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .topbar .user-info {
            text-align: right;
        }
        
        .topbar .user-info .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: #1a1a2e;
        }
        
        .topbar .user-info .user-role {
            font-size: 0.7rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* ========== CONTENT WRAPPER ========== */
        .content-wrapper {
            padding: 28px 32px;
        }
        
        /* ========== BREADCRUMB ========== */
        .breadcrumb-wrapper {
            margin-bottom: 24px;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }
        
        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.85rem;
        }
        
        .breadcrumb-item.active {
            color: #1a1a2e;
            font-weight: 500;
        }
        
        /* ========== STAT CARDS ========== */
        .stat-card {
            background: #fff;
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: all var(--transition-speed) ease;
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.1;
            transform: translate(30%, -30%);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }
        
        .stat-card .icon {
            width: 56px;
            height: 56px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 16px;
        }
        
        .stat-card.primary::before { background: #667eea; }
        .stat-card.primary .icon { background: var(--primary-gradient); color: #fff; }
        
        .stat-card.success::before { background: #38ef7d; }
        .stat-card.success .icon { background: var(--success-gradient); color: #fff; }
        
        .stat-card.info::before { background: #00f2fe; }
        .stat-card.info .icon { background: var(--info-gradient); color: #fff; }
        
        .stat-card.warning::before { background: #ffd200; }
        .stat-card.warning .icon { background: var(--warning-gradient); color: #fff; }
        
        .stat-card h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }
        
        .stat-card p {
            color: #6c757d;
            font-size: 0.85rem;
            margin: 0;
        }
        
        /* ========== CARDS ========== */
        .card {
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border-radius: var(--border-radius);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.04);
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header i {
            color: #667eea;
        }
        
        .card-body {
            padding: 24px;
        }
        
        .card-footer {
            background: #fafafa;
            border-top: 1px solid #f0f0f0;
            padding: 16px 24px;
        }
        
        /* ========== BUTTONS ========== */
        .btn {
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all var(--transition-speed) ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-sm {
            padding: 7px 14px;
            font-size: 0.8rem;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            background: var(--primary-gradient);
        }
        
        .btn-success {
            background: var(--success-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3);
        }
        
        .btn-danger {
            background: var(--danger-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(235, 51, 73, 0.3);
        }
        
        .btn-outline-primary {
            border: 1.5px solid #667eea;
            color: #667eea;
        }
        
        .btn-outline-primary:hover {
            background: #667eea;
            color: #fff;
        }
        
        .btn-outline-danger:hover {
            background: var(--danger-gradient);
            border-color: transparent;
        }
        
        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        
        .btn-icon.btn-sm {
            width: 32px;
            height: 32px;
        }
        
        /* ========== TABLES ========== */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #f8f9fc;
            border: none;
            font-weight: 600;
            color: #6c757d;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            white-space: nowrap;
        }
        
        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-color: #f0f0f0;
            font-size: 0.875rem;
            color: #4a4a4a;
        }
        
        .table-hover tbody tr {
            transition: all var(--transition-speed) ease;
        }
        
        .table-hover tbody tr:hover {
            background: #f8f9fc;
        }
        
        .table-hover tbody tr:hover td {
            color: #1a1a2e;
        }
        
        /* ========== BADGES ========== */
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            font-size: 0.75rem;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }
        
        .badge-soft-primary {
            background: rgba(102, 126, 234, 0.15);
            color: #667eea;
        }
        
        .badge-soft-success {
            background: rgba(56, 239, 125, 0.15);
            color: #11998e;
        }
        
        .badge-soft-warning {
            background: rgba(247, 151, 30, 0.15);
            color: #f7971e;
        }
        
        .badge-soft-danger {
            background: rgba(235, 51, 73, 0.15);
            color: #eb3349;
        }
        
        .badge-soft-info {
            background: rgba(79, 172, 254, 0.15);
            color: #4facfe;
        }
        
        .badge-soft-secondary {
            background: rgba(108, 117, 125, 0.15);
            color: #6c757d;
        }
        
        /* ========== FORMS ========== */
        .form-label {
            font-weight: 500;
            color: #4a4a4a;
            font-size: 0.875rem;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.9rem;
            transition: all var(--transition-speed) ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: #eb3349;
        }
        
        .form-text {
            color: #9ca3af;
            font-size: 0.8rem;
            margin-top: 6px;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-switch .form-check-input {
            width: 48px;
            height: 26px;
        }
        
        /* ========== ALERTS ========== */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 16px 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-success {
            background: linear-gradient(90deg, rgba(56, 239, 125, 0.1) 0%, rgba(17, 153, 142, 0.1) 100%);
            color: #11998e;
            border-left: 4px solid #11998e;
        }
        
        .alert-danger {
            background: linear-gradient(90deg, rgba(235, 51, 73, 0.1) 0%, rgba(244, 92, 67, 0.1) 100%);
            color: #eb3349;
            border-left: 4px solid #eb3349;
        }
        
        .alert-warning {
            background: linear-gradient(90deg, rgba(247, 151, 30, 0.1) 0%, rgba(255, 210, 0, 0.1) 100%);
            color: #d97706;
            border-left: 4px solid #f7971e;
        }
        
        .alert i {
            font-size: 1.2rem;
        }
        
        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: #f0f2f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #9ca3af;
            font-size: 2rem;
        }
        
        .empty-state h5 {
            color: #4a4a4a;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .empty-state p {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        /* ========== DELETE MODAL ========== */
        .modal-confirm .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .modal-confirm .modal-header {
            border: none;
            padding: 24px 24px 0;
        }
        
        .modal-confirm .modal-body {
            padding: 20px 24px;
            text-align: center;
        }
        
        .modal-confirm .modal-footer {
            border: none;
            padding: 0 24px 24px;
            justify-content: center;
            gap: 12px;
        }
        
        .modal-confirm .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }
        
        .modal-confirm .icon-box.danger {
            background: rgba(235, 51, 73, 0.1);
            color: #eb3349;
        }
        
        .modal-confirm h5 {
            color: #1a1a2e;
            font-weight: 700;
        }
        
        .modal-confirm p {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        /* ========== PAGINATION ========== */
        .pagination {
            gap: 6px;
        }
        
        .page-link {
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            color: #6c757d;
            font-weight: 500;
            font-size: 0.875rem;
            background: #f8f9fa;
            transition: all var(--transition-speed) ease;
        }
        
        .page-link:hover {
            background: #e9ecef;
            color: #667eea;
        }
        
        .page-item.active .page-link {
            background: var(--primary-gradient);
            color: #fff;
        }
        
        /* ========== AVATAR ========== */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }
        
        .avatar-lg {
            width: 56px;
            height: 56px;
            font-size: 1.25rem;
        }
        
        /* ========== ACTION BUTTONS ========== */
        .action-buttons {
            display: flex;
            gap: 6px;
        }
        
        .action-buttons .btn {
            padding: 6px 10px;
        }
        
        /* ========== TOOLTIP ========== */
        .tooltip-inner {
            background: #1a1a2e;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.4s ease forwards;
        }
        
        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .topbar {
                padding: 0 20px;
            }
            
            .content-wrapper {
                padding: 20px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">
                <i class="bi bi-translate"></i>
            </div>
            <div class="brand-text">
                <h5>Hanacaraka</h5>
                <span>Admin Panel</span>
            </div>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Main Menu</div>
        </div>
        
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>
        
        <div class="nav-section">
            <div class="nav-section-title">Konten Pembelajaran</div>
        </div>
        
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.levels.*') ? 'active' : '' }}" href="{{ route('admin.levels.index') }}">
                    <i class="bi bi-stack"></i>
                    <span>Levels</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.stages.*') ? 'active' : '' }}" href="{{ route('admin.stages.index') }}">
                    <i class="bi bi-collection-fill"></i>
                    <span>Stages</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}" href="{{ route('admin.materials.index') }}">
                    <i class="bi bi-journal-richtext"></i>
                    <span>Materials</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}" href="{{ route('admin.quizzes.index') }}">
                    <i class="bi bi-patch-question-fill"></i>
                    <span>Quizzes</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.evaluations.*') ? 'active' : '' }}" href="{{ route('admin.evaluations.index') }}">
                    <i class="bi bi-brush-fill"></i>
                    <span>Evaluations</span>
                </a>
            </li>
        </ul>
        
        <div class="nav-section">
            <div class="nav-section-title">User & Rewards</div>
        </div>
        
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.badges.*') ? 'active' : '' }}" href="{{ route('admin.badges.index') }}">
                    <i class="bi bi-award-fill"></i>
                    <span>Badges</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-divider"></div>
        
        <ul class="nav flex-column px-2 mb-4">
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-icon btn-outline-secondary d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="page-info">
                    <h5>@yield('page-title', 'Dashboard')</h5>
                    <small>@yield('page-subtitle', 'Selamat datang di admin panel')</small>
                </div>
            </div>
            @auth
            <div class="topbar-right">
                <div class="dropdown">
                    <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-info d-none d-md-block">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><span class="dropdown-item-text fw-bold">{{ auth()->user()->email }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-left me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @endauth
        </div>
        
        <!-- Content -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="fade-in-up">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade modal-confirm" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="icon-box danger">
                        <i class="bi bi-trash3"></i>
                    </div>
                    <h5>Hapus Data?</h5>
                    <p id="deleteMessage">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="bi bi-trash3 me-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
        
        // Delete confirmation modal
        let deleteForm = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        
        function confirmDelete(formAction, message = 'Data yang dihapus tidak dapat dikembalikan.') {
            document.getElementById('deleteMessage').textContent = message;
            deleteForm = formAction;
            deleteModal.show();
        }
        
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteForm) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteForm;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
