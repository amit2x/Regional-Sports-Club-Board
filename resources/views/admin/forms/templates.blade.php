
@extends('layouts.admin')

@section('title', 'Form Templates')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Form Templates</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Templates</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.forms.builder') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Create Template
    </a>
</div>

<div class="row g-4">
    @forelse($templates as $template)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="mb-1">{{ $template->name }}</h5>
                        <small class="text-muted">By {{ $template->creator->name ?? 'N/A' }}</small>
                    </div>
                    @if($template->is_reusable)
                        <span class="badge bg-success">Reusable</span>
                    @endif
                </div>
                <p class="text-muted small">{{ $template->description ?? 'No description' }}</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.forms.preview', $template->id) }}" class="btn btn-sm btn-outline-primary">Preview</a>
                    <a href="{{ route('admin.forms.builder', $template->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-file-earmark-text text-muted" style="font-size: 48px;"></i>
        <p class="text-muted mt-3">No templates created yet.</p>
        <a href="{{ route('admin.forms.builder') }}" class="btn btn-primary">Create First Template</a>
    </div>
    @endforelse
</div>
{{ $templates->links() }}
@endsection
