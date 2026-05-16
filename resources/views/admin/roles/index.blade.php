@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Roles & Permissions</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
        <i class="bi bi-plus-lg me-1"></i>Create Role
    </button>
</div>

<div class="row g-4">
    @foreach($roles as $role)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="mb-1">{{ ucwords(str_replace('_', ' ', $role->name)) }}</h5>
                        <small class="text-muted">{{ $role->users_count }} users</small>
                    </div>
                    <span class="badge bg-primary">{{ $role->permissions_count }} permissions</span>
                </div>
                <div class="d-flex flex-wrap gap-1">
                    @foreach($role->permissions->take(10) as $permission)
                        <span class="badge bg-light text-dark">{{ $permission->name }}</span>
                    @endforeach
                    @if($role->permissions_count > 10)
                        <span class="badge bg-light text-dark">+{{ $role->permissions_count - 10 }} more</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
