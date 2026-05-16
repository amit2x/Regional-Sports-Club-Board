@extends('layouts.admin')

@section('title', 'Role Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Role: {{ ucwords(str_replace('_', ' ', $role->name)) }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">{{ $role->name }}</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Assigned Permissions ({{ $role->permissions->count() }})</h5></div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($role->permissions as $permission)
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-2 small">
                            <i class="bi bi-check-circle text-success me-1"></i>{{ $permission->name }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Users with this Role ({{ $users->total() }})</h5></div>
            <div class="card-body">
                @foreach($users as $user)
                <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="mb-0 small fw-medium">{{ $user->name }}</p>
                        <small class="text-muted">{{ $user->employee_id }}</small>
                    </div>
                </div>
                @endforeach
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
