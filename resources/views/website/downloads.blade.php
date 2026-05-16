@extends('layouts.public')

@section('title', 'Downloads - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Downloads & Forms</h2>

        <div class="row g-4">
            @forelse($forms as $form)
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 48px;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title">{{ $form->title }}</h5>
                                    <p class="text-muted small">{{ $form->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-download me-1"></i>{{ $form->download_count }} downloads
                                        </small>
                                        <a href="{{ asset('storage/' . $form->file_path) }}"
                                           class="btn btn-primary btn-sm"
                                           download>
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-file-earmark-text text-muted" style="font-size: 64px;"></i>
                    <p class="text-muted mt-3">No forms available for download yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
