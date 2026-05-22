{{-- resources/views/layouts/partials/alerts.blade.php --}}
@if(session('success'))
<div class="alert alert-success alert-custom animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-custom animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <div>{{ session('error') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning alert-custom animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
    <div>{{ session('warning') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-custom animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
    <div>{{ session('info') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-custom animate__animated animate__fadeInDown" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-1 small">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
