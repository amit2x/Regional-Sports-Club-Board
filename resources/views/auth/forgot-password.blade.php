@extends('layouts.public')

@section('title', 'Forgot Password - RSCB')

@section('content')
<div class="password-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <a href="{{ route('employee.login') }}" class="text-white mb-3 d-inline-block">
                    <i class="bi bi-arrow-left me-1"></i>Back to Login
                </a>
                <div class="password-card p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-key text-primary" style="font-size: 48px;"></i>
                        <h3 class="mt-3">Forgot Password?</h3>
                        <p class="text-muted">Enter your Employee ID and Email to receive a password reset link.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('employee.password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Employee ID</label>
                            <input type="text" name="employee_id" class="form-control" required value="{{ old('employee_id') }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-envelope me-1"></i>Send Reset Link
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .password-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .password-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
</style>
@endpush
