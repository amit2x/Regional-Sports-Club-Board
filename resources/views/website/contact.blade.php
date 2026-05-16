@extends('layouts.public')

@section('title', 'Contact Us - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Contact Us</h2>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Send us a Message</h5>

                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('website.contact.submit') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name *</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Subject *</label>
                                    <input type="text" name="subject" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message *</label>
                                    <textarea name="message" class="form-control" rows="5" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-1"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Contact Information</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                <strong>Headquarters:</strong><br>
                                Regional Sports Control Board<br>
                                New Delhi - 110001, India
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                <strong>Email:</strong><br>
                                info@rscb.gov.in
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-telephone-fill text-primary me-2"></i>
                                <strong>Phone:</strong><br>
                                +91-11-XXXXXXXX
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Regional Offices</h5>
                        @foreach($regions as $region)
                            <div class="mb-3">
                                <h6>{{ $region->name }}</h6>
                                <p class="text-muted small mb-1">Headquarters: {{ $region->headquarters }}</p>
                                @foreach($region->airports as $airport)
                                    <div class="ms-3 mb-2">
                                        <small class="fw-bold">{{ $airport->name }}</small><br>
                                        <small class="text-muted">{{ $airport->contact_person }}</small><br>
                                        <small class="text-muted">{{ $airport->email }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
