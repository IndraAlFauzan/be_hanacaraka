@extends('admin.layout.app')

@section('title', 'Login Admin')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin-left: calc(var(--sidebar-width) * -1);">
    <div class="card shadow-lg" style="width: 100%; max-width: 450px; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="d-inline-block p-3 rounded-circle bg-primary bg-opacity-10 mb-3">
                    <i class="bi bi-shield-lock-fill text-primary" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold">Admin Login</h3>
                <p class="text-muted">Masuk ke Admin Panel Aksara Jawa</p>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="admin@aksarajawa.com"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••"
                               required>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Ingat Saya
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Masuk
                </button>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    Default: admin@aksarajawa.com / Admin123!
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        overflow: hidden;
    }
    .sidebar {
        display: none;
    }
    .main-content {
        margin-left: 0;
    }
    .topbar {
        display: none;
    }
</style>
@endpush
