@extends('layouts.admin')

@section('title', 'Edit Announcement')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Announcement</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title', $announcement->title) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="10" required>{{ old('content', $announcement->content) }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            @foreach(['low', 'medium', 'high', 'urgent'] as $p)
                                <option value="{{ $p }}" {{ $announcement->priority == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Valid From</label>
                        <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', $announcement->valid_from->format('Y-m-d')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Valid Until</label>
                        <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', $announcement->valid_until ? $announcement->valid_until->format('Y-m-d') : '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['draft', 'published', 'archived'] as $s)
                                <option value="{{ $s }}" {{ $announcement->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i>Update Announcement
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
