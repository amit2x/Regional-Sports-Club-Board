{{-- resources/views/admin/forms/preview.blade.php --}}
@extends('layouts.admin')

@section('title', 'Form Preview')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Form Preview: {{ $template->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.forms.templates') }}">Templates</a></li>
                <li class="breadcrumb-item active">Preview</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.forms.builder', $template->id) }}" class="btn btn-primary">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h4 class="mb-4">{{ $template->name }}</h4>
                @if($template->description)
                    <p class="text-muted mb-4">{{ $template->description }}</p>
                @endif

                <form>
                    @foreach($template->form_fields as $field)
                    <div class="mb-3">
                        <label class="form-label">
                            {{ $field['label'] }}
                            @if($field['required'] ?? false)
                                <span class="text-danger">*</span>
                            @endif
                        </label>

                        @switch($field['type'])
                            @case('text')
                                <input type="text" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}" disabled>
                                @break
                            @case('textarea')
                                <textarea class="form-control" rows="3" placeholder="{{ $field['placeholder'] ?? '' }}" disabled></textarea>
                                @break
                            @case('number')
                                <input type="number" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}" disabled>
                                @break
                            @case('email')
                                <input type="email" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}" disabled>
                                @break
                            @case('date')
                                <input type="date" class="form-control" disabled>
                                @break
                            @case('file')
                                <input type="file" class="form-control" disabled>
                                <small class="text-muted">{{ $field['placeholder'] ?? '' }}</small>
                                @break
                            @case('dropdown')
                                <select class="form-select" disabled>
                                    <option>{{ $field['placeholder'] ?? 'Select' }}</option>
                                    @foreach($field['options'] ?? [] as $option)
                                        <option>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @break
                            @case('radio')
                                @foreach($field['options'] ?? [] as $option)
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" disabled>
                                        <label class="form-check-label">{{ $option }}</label>
                                    </div>
                                @endforeach
                                @break
                            @case('checkbox')
                                @foreach($field['options'] ?? [] as $option)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" disabled>
                                        <label class="form-check-label">{{ $option }}</label>
                                    </div>
                                @endforeach
                                @break
                            @case('declaration')
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" disabled>
                                    <label class="form-check-label">{{ $field['placeholder'] ?? 'I agree' }}</label>
                                </div>
                                @break
                            @default
                                <input type="text" class="form-control" disabled>
                        @endswitch
                    </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
