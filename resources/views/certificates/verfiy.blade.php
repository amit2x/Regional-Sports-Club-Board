@extends('layouts.public')

@section('title', 'Certificate Verification')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(!$valid)
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-x-circle text-danger" style="font-size: 64px;"></i>
                        </div>
                        <h3 class="text-danger">Invalid Certificate</h3>
                        <p class="text-muted">
                            The certificate number you entered is invalid or does not exist in our records.
                        </p>
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="bi bi-house me-1"></i>Go to Homepage
                        </a>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-check-circle me-2"></i>Certificate Verified
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>Certificate Details</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted">Certificate Number</td>
                                        <td><strong>{{ $certificate->certificate_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Participant Name</td>
                                        <td><strong>{{ $certificate->registration->employee->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Employee ID</td>
                                        <td>{{ $certificate->registration->employee->employee_id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Event</td>
                                        <td>{{ $certificate->registration->event->event_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Event Date</td>
                                        <td>
                                            {{ $certificate->registration->event->start_date->format('d M Y') }} -
                                            {{ $certificate->registration->event->end_date->format('d M Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Venue</td>
                                        <td>{{ $certificate->registration->event->venue }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Issue Date</td>
                                        <td>{{ $certificate->issue_date->format('d F Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $certificate->qr_code_path) }}"
                                         alt="QR Code"
                                         class="img-fluid"
                                         style="max-width: 150px;">
                                </div>
                                <a href="{{ route('certificates.download', $certificate->id) }}"
                                   class="btn btn-primary">
                                    <i class="bi bi-download me-1"></i>Download Certificate
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            This certificate is digitally verified by Regional Sports Control Board.
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
