@extends('layouts.admin')

@section('title', 'Upload Image')

@section('content')
<div class="page-header">
    <h1 class="page-title">Upload Gallery Image</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Gallery</a></li>
            <li class="breadcrumb-item active">Upload</li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Max size: 5MB. Supported: JPG, PNG, JPEG</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g., Cricket, Ceremony">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Related Event</label>
                        <select name="event_id" class="form-select">
                            <option value="">None</option>
                            @foreach(\App\Models\Event::all() as $event)
                                <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" class="form-check-input" value="1" id="isFeatured">
                            <label class="form-check-label" for="isFeatured">Feature on homepage</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload me-1"></i>Upload Image
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
