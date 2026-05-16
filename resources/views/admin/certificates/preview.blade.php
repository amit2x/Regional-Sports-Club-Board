@extends('layouts.admin')

@section('title', 'Certificate Preview')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Certificate Preview</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.certificates.index') }}">Certificates</a></li>
                <li class="breadcrumb-item active">Preview</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" onclick="generateCertificate()">
        <i class="bi bi-award me-1"></i>Generate Certificate
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="bg-light p-4 text-center">
            <div style="max-width: 800px; margin: 0 auto; background: white; padding: 40px; border: 3px solid #667eea;">
                <h2 class="text-primary">Certificate of Participation</h2>
                <p class="mt-4">This is to certify that</p>
                <h3 class="text-warning">{{ $registration->employee->name }}</h3>
                <p>has participated in</p>
                <h4>{{ $registration->event->event_name }}</h4>
                <p>held at {{ $registration->event->venue }}</p>
                <p>{{ $registration->event->start_date->format('d F Y') }} - {{ $registration->event->end_date->format('d F Y') }}</p>
                <div class="mt-5">
                    <p>Certificate #: Will be generated</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateCertificate() {
    $.ajax({
        url: '{{ route("admin.certificates.generate", $registration->id) }}',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: (r) => {
            Swal.fire('Generated!', r.message, 'success').then(() => {
                window.location.href = r.download_url;
            });
        },
        error: (x) => Swal.fire('Error', x.responseJSON?.message || 'Failed', 'error')
    });
}
</script>
@endpush
