@extends('layouts.employee')

@section('title', 'Event Registration')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="bi bi-pencil-square me-2"></i>Event Registration
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee.events.available') }}">Events</a></li>
                    <li class="breadcrumb-item active">Register</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('employee.events.available') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Events
        </a>
    </div>
</div>

{{-- Event Details --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                @if($event->banner_image)
                    <img src="{{ asset('storage/' . $event->banner_image) }}"
                         alt="{{ $event->event_name }}"
                         class="img-fluid rounded">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-calendar-event text-muted" style="font-size: 48px;"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-9">
                <h3>{{ $event->event_name }}</h3>
                <p class="text-muted">{{ $event->event_code }}</p>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <small class="text-muted">Venue</small>
                        <p class="mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}</p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Event Date</small>
                        <p class="mb-2">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Last Date for Registration</small>
                        <p class="mb-2">
                            <i class="bi bi-clock me-1"></i>
                            {{ $event->registration_last_date->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>

                @if($event->description)
                    <div class="mt-3">
                        <h6>Description</h6>
                        <p>{{ $event->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Registration Form --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>Registration Form
        </h5>
        <small class="text-muted">All fields marked with * are required</small>
    </div>
    <div class="card-body">
        <form id="registrationForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="submit_type" id="submitType" value="draft">

            {{-- Employee Details (Auto-filled) --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Employee Name</label>
                    <input type="text" class="form-control" value="{{ $employee->name }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Employee ID</label>
                    <input type="text" class="form-control" value="{{ $employee->employee_id }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <input type="text" class="form-control" value="{{ $employee->department }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Designation</label>
                    <input type="text" class="form-control" value="{{ $employee->designation }}" readonly>
                </div>
            </div>

            <hr>

            {{-- Dynamic Form Fields --}}
            <div class="row g-3" id="dynamicFormFields">
                @foreach($formFields as $field)
                    <div class="{{ $field['type'] === 'textarea' ? 'col-md-12' : 'col-md-6' }}">
                        @include('employee.events.partials.form-field', ['field' => $field])
                    </div>
                @endforeach
            </div>

            {{-- Document Uploads --}}
            @if(count($requiredDocuments) > 0)
                <div class="mt-4">
                    <h5 class="mb-3">Required Documents</h5>
                    <div class="row g-3" id="documentUploads">
                        @foreach($requiredDocuments as $docType)
                            <div class="col-md-6">
                                <label class="form-label">
                                    {{ ucwords(str_replace('_', ' ', $docType)) }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file"
                                       name="documents[{{ $docType }}]"
                                       class="form-control"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       required>
                                <small class="text-muted">Accepted: PDF, JPG, PNG (Max 5MB)</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Rules & Regulations --}}
            @if($event->rules_regulations)
                <div class="mt-4">
                    <h5>Rules & Regulations</h5>
                    <div class="alert alert-info">
                        {!! nl2br(e($event->rules_regulations)) !!}
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="agreeRules" required>
                        <label class="form-check-label" for="agreeRules">
                            I have read and agree to the rules and regulations
                        </label>
                    </div>
                </div>
            @endif

            {{-- Form Actions --}}
            <div class="mt-4 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" onclick="saveDraft()">
                    <i class="bi bi-save me-1"></i>Save as Draft
                </button>
                <button type="button" class="btn btn-primary" onclick="submitRegistration()">
                    <i class="bi bi-send me-1"></i>Submit Registration
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function saveDraft() {
    $('#submitType').val('draft');
    submitForm();
}

function submitRegistration() {
    // Validate form
    if (!$('#registrationForm')[0].checkValidity()) {
        $('#registrationForm')[0].reportValidity();
        return;
    }

    // Check rules agreement
    @if($event->rules_regulations)
        if (!$('#agreeRules').prop('checked')) {
            Swal.fire('Required', 'Please agree to the rules and regulations', 'warning');
            return;
        }
    @endif

    Swal.fire({
        title: 'Submit Registration?',
        text: 'Are you sure you want to submit this registration? You cannot edit it after submission.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#submitType').val('submit');
            submitForm();
        }
    });
}

function submitForm() {
    let formData = new FormData($('#registrationForm')[0]);

    $.ajax({
        url: '{{ route("employee.events.submit-registration", $event->id) }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            Swal.fire({
                title: 'Please wait...',
                text: 'Submitting your registration',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response.message,
                icon: 'success'
            }).then(() => {
                if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                }
            });
        },
        error: function(xhr) {
            let message = 'Failed to submit registration';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire('Error', message, 'error');
        }
    });
}

// Auto-save draft every 30 seconds
let autoSaveInterval;
if ($('#submitType').val() === 'draft') {
    autoSaveInterval = setInterval(saveDraft, 30000);
}
</script>
@endpush
