@extends('layouts.public')

@section('title', 'Announcements - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Announcements</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                @forelse($announcements as $announcement)
                    <a href="{{ route('website.announcement-details', $announcement->id) }}" class="text-decoration-none">
                        <div class="card shadow-sm mb-3 hover-shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title text-dark">{{ $announcement->title }}</h5>
                                        <p class="text-muted">{{ \Str::limit(strip_tags($announcement->content), 200) }}</p>
                                    </div>
                                    <span class="badge bg-{{ $announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning' : 'info') }}">
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>{{ $announcement->created_at->format('d M Y') }}
                                        <i class="bi bi-eye ms-3 me-1"></i>{{ $announcement->views_count }} views
                                    </small>
                                    <span class="text-primary">Read More <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-megaphone text-muted" style="font-size: 64px;"></i>
                        <p class="text-muted mt-3">No announcements at the moment.</p>
                    </div>
                @endforelse

                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
