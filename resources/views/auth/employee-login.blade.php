{{-- resources/views/auth/employee-login.blade.php --}}
@extends('layouts.public')

@section('title', 'Employee Login - RSCB')

@push('styles')
<style>



    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        backdrop-filter: blur(10px);
    }

    .brand-logo {
        width: 100px;
        height: auto;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-control {
        border-radius: 8px;
        padding: 12px;
    }

    .input-group-text {
        border-radius: 8px 0 0 8px;
        background: #f8f9fa;
        border-color: #dee2e6;
    }

    .input-group .form-control {
        border-radius: 0 8px 8px 0;
    }

    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px;
        font-weight: 600;
        border-radius: 8px;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-google {
        background: #fff;
        color: #333;
        border: 2px solid #e0e0e0;
        padding: 10px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-google:hover {
        background: #f8f9fa;
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .password-toggle {
        border-radius: 0 8px 8px 0;
        border-color: #dee2e6;
    }

    .password-toggle:hover {
        background: #f8f9fa;
    }

    .back-to-home {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s;
    }

    .back-to-home:hover {
        color: white;
    }

    .alert {
        border-radius: 10px;
    }

    .logo-fallback {
        background: linear-gradient(135deg, #667eea, #764ba2);
        width: 100px;
        height: 100px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 32px;
        margin: 0 auto;
    }
</style>
@endpush

@section('content')
<div class="login-page">
    <div class="login-container d-flex align-items-center justify-content-center p-4">
        <div class="container">
            {{-- Back to Home --}}
            <div class="row justify-content-center mb-3">
                <div class="col-md-5">
                    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left me-2"></i>Back to Home
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="login-card p-4">
                        {{-- Logo and Title --}}
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}"
                                 alt="RSCB Logo"
                                 class="brand-logo mb-3"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="logo-fallback" style="display: none;">RSCB</div>
                            <h3 class="fw-bold mt-3" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                Regional Sports Control Board
                            </h3>
                            <p class="text-muted">
                                <i class="bi bi-shield-lock me-1"></i>Employee Login Portal
                            </p>
                        </div>

                        {{-- Alert Messages --}}
                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-x-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Login Form --}}
                        <form method="POST" action="{{ route('employee.login') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="employee_id" class="form-label fw-semibold">Employee ID</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           id="employee_id"
                                           name="employee_id"
                                           value="{{ old('employee_id') }}"
                                           placeholder="Enter your Employee ID"
                                           required
                                           autofocus>
                                    <div class="invalid-feedback">
                                        Please enter your Employee ID.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control"
                                           id="password"
                                           name="password"
                                           placeholder="Enter your password"
                                           required>
                                    <button class="btn btn-outline-secondary password-toggle"
                                            type="button"
                                            onclick="togglePassword()"
                                            title="Show/Hide Password">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        Please enter your password.
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Default password is your PAN number (first-time login)
                                </small>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>

                            <button type="submit" class="btn btn-login w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>

                            <div class="text-center mb-3">
                                <span class="text-muted position-relative px-3">
                                    <span style="position: absolute; top: 50%; left: 0; right: 0; border-top: 1px solid #dee2e6;"></span>
                                    <span class="bg-white position-relative px-3 text-muted">or continue with</span>
                                </span>
                            </div>

                            <a href="{{ route('employee.auth.google') }}" class="btn btn-google w-100 mb-3 d-flex align-items-center justify-content-center">
                                <img src="https://www.google.com/favicon.ico" alt="Google" width="20" class="me-2">
                                Login with Google
                            </a>

                            <div class="text-center">
                                <a href="{{ route('employee.password.request') }}" class="text-decoration-none">
                                    <i class="bi bi-key me-1"></i>Forgot Password?
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }

    // Form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Auto-focus next input on Enter
    document.getElementById('employee_id').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('password').focus();
        }
    });
</script>
@endpush
