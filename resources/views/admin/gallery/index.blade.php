@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Gallery Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Gallery</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Upload Image
    </a>
</div>

{{-- Upload Multiple --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form id="multipleUploadForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Select Images</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                    <small class="text-muted">You can select multiple images</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g., Cricket, Football">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Event</label>
                    <select name="event_id" class="form-select">
                        <option value="">None</option>
                        @foreach(\App\Models\Event::all() as $event)
                            <option value="{{ $event->id }}">{{ $event->event_code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-cloud-upload me-1"></i>Upload
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Gallery Grid --}}
<div class="row g-3">
    @forelse($images as $image)
    <div class="col-lg-2 col-md-3 col-4">
        <div class="card border-0 shadow-sm">
            <img src="{{ asset('storage/' . $image->thumbnail_path) }}"
                 alt="{{ $image->title }}"
                 class="card-img-top"
                 style="height: 150px; object-fit: cover;"
                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><rect fill=%22%23667eea%22 width=%22200%22 height=%22200%22/><text fill=%22white%22 x=%22100%22 y=%22110%22 text-anchor=%22middle%22>IMG</text></svg>'">
            <div class="card-body p-2">
                <p class="card-text small mb-2 text-truncate" title="{{ $image->title }}">{{ $image->title }}</p>
                <div class="d-flex gap-1">
                    <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="btn btn-sm btn-info flex-fill" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                    <button class="btn btn-sm btn-danger flex-fill delete-image" data-id="{{ $image->id }}" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-images text-muted" style="font-size: 64px;"></i>
        <p class="text-muted mt-3">No images in gallery.</p>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">Upload First Image</a>
    </div>
    @endforelse
</div>
{{ $images->links() }}
@endsection

@push('scripts')
<script>
// Multiple upload
$('#multipleUploadForm').submit(function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    $.ajax({
        url: '{{ route("admin.gallery.upload-multiple") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: () => Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }),
        success: (r) => { Swal.fire('Uploaded!', r.message, 'success').then(() => location.reload()); },
        error: (x) => Swal.fire('Error', x.responseJSON?.message || 'Upload failed', 'error')
    });
});

// Delete image
$('.delete-image').click(function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Delete Image?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/gallery/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: () => location.reload(),
                error: () => Swal.fire('Error', 'Failed to delete', 'error')
            });
        }
    });
});
</script>
@endpush
