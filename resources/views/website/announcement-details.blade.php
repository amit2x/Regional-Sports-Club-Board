@extends('layouts.public')

@section('title', $announcement->title . ' - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('website.announcements') }}">Announcements</a></li>
                        <li class="breadcrumb-item active">{{ \Str::limit($announcement->title, 50) }}</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h1 class="card-title h3">{{ $announcement->title }}</h1>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>Published: {{ $announcement->created_at->format('d M Y H:i') }}
                                    <i class="bi bi-eye ms-3 me-1"></i>{{ $announcement->views_count }} views
                                </small>
                            </div>
                            <span class="badge bg-{{ $announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning' : 'info') }} fs-6">
                                {{ ucfirst($announcement->priority) }} Priority
                            </span>
                        </div>

                        @if($announcement->valid_until)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                This announcement is valid until {{ $announcement->valid_until->format('d M Y') }}
                            </div>
                        @endif

                        <hr>

                        <div class="announcement-content mt-4">
                            {!! $announcement->content !!}
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('website.announcements') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Announcements
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
