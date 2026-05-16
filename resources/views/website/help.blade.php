@extends('layouts.public')

@section('title', 'Help Center - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Help Center</h2>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-9">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-book text-primary" style="font-size: 48px;"></i>
                                <h5 class="mt-3">User Guide</h5>
                                <p class="text-muted">Learn how to use the RSCB portal effectively</p>
                                <a href="{{ route('website.faq') }}" class="btn btn-outline-primary btn-sm">View FAQ</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-envelope text-success" style="font-size: 48px;"></i>
                                <h5 class="mt-3">Contact Support</h5>
                                <p class="text-muted">Get in touch with our support team</p>
                                <a href="{{ route('website.contact') }}" class="btn btn-outline-success btn-sm">Contact Us</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-download text-warning" style="font-size: 48px;"></i>
                                <h5 class="mt-3">Downloads</h5>
                                <p class="text-muted">Download forms and documents</p>
                                <a href="{{ route('website.downloads') }}" class="btn btn-outline-warning btn-sm">View Downloads</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-shield-check text-danger" style="font-size: 48px;"></i>
                                <h5 class="mt-3">Report Issue</h5>
                                <p class="text-muted">Report technical problems or security concerns</p>
                                <a href="{{ route('website.contact') }}" class="btn btn-outline-danger btn-sm">Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
