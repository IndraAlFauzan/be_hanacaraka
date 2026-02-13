<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Aksara Jawa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1a1f3a 0%, #2d3561 100%);
            padding-top: 20px;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar .brand {
            padding: 0 20px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .brand h4 {
            color: #fff;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar .nav-item {
            margin: 5px 10px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .sidebar .nav-link.active {
            background: #4f46e5;
            color: #fff;
        }
        
        .sidebar .nav-link i {
            font-size: 1.1rem;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .topbar {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .content-wrapper {
            padding: 30px;
        }
        
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .stat-card.primary .icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        
        .stat-card.success .icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
        }
        
        .stat-card.info .icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
        }
        
        .stat-card.warning .icon {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: #fff;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 10px;
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <h4><i class="bi bi-book-half"></i> Aksara Jawa</h4>
            <small class="text-white-50">Admin Panel</small>
        </div>
        
        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.levels.*') ? 'active' : '' }}" href="{{ route('admin.levels.index') }}">
                    <i class="bi bi-layers"></i>
                    <span>Levels</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.stages.*') ? 'active' : '' }}" href="{{ route('admin.stages.index') }}">
                    <i class="bi bi-grid-3x3"></i>
                    <span>Stages</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}" href="{{ route('admin.materials.index') }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Materials</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}" href="{{ route('admin.quizzes.index') }}">
                    <i class="bi bi-question-circle"></i>
                    <span>Quizzes</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.badges.*') ? 'active' : '' }}" href="{{ route('admin.badges.index') }}">
                    <i class="bi bi-award"></i>
                    <span>Badges</span>
                </a>
            </li>

            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.evaluations.*') ? 'active' : '' }}" href="{{ route('admin.evaluations.index') }}">
                    <i class="bi bi-pencil-square"></i>
                    <span>Evaluations</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start" style="background: none; border: none;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                <small class="text-muted">@yield('page-subtitle', 'Selamat datang di admin panel')</small>
            </div>
            @auth
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">{{ auth()->user()->name }}</span>
                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
            @endauth
        </div>
        
        <!-- Content -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
