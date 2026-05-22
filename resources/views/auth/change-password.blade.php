@extends('layouts.public')

@section('title', 'Change Password - RSCB')

@push('styles')
<style>

    .password-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
</style>
@endpush

@section('content')
<div class="password-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="password-card p-3">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock text-primary" style="font-size: 48px;"></i>
                        <h3 class="mt-3">Change Password</h3>
                        @if(auth()->guard('employee')->check() && auth()->guard('employee')->user()->force_password_change)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                You must change your default password before continuing.
                            </div>
                        @endif
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

                    <form method="POST" action="{{ route('employee.password.change') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                            <small class="text-muted">
                                Minimum 8 characters with uppercase, lowercase, number, and special character
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-1"></i>Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
