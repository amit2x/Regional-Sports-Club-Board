@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Event</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
            <li class="breadcrumb-item active">Edit: {{ $event->event_name }}</li>
        </ol>
    </nav>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Main Details --}}
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Event Name <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" class="form-control"
                               value="{{ old('event_name', $event->event_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Event Code <span class="text-danger">*</span></label>
                        <input type="text" name="event_code" class="form-control"
                               value="{{ old('event_code', $event->event_code) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Venue <span class="text-danger">*</span></label>
                        <input type="text" name="venue" class="form-control"
                               value="{{ old('venue', $event->venue) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rules & Regulations</label>
                        <textarea name="rules_regulations" class="form-control" rows="4">{{ old('rules_regulations', $event->rules_regulations) }}</textarea>
                    </div>

                    {{-- Current Banner --}}
                    @if($event->banner_image)
                    <div class="mb-3">
                        <label class="form-label">Current Banner</label>
                        <div>
                            <img src="{{ asset('storage/' . $event->banner_image) }}"
                                 alt="Banner" class="rounded" style="max-height: 150px;">
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar Details --}}
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Event Type <span class="text-danger">*</span></label>
                        <select name="event_type" class="form-select" required>
                            @foreach(['regional' => 'Regional', 'airport' => 'Airport', 'inter_airport' => 'Inter-Airport', 'annual_meet' => 'Annual Meet'] as $value => $label)
                                <option value="{{ $value }}" {{ $event->event_type == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Participation Type</label>
                        <select name="participation_type" class="form-select">
                            @foreach(['individual' => 'Individual', 'team' => 'Team', 'both' => 'Both'] as $value => $label)
                                <option value="{{ $value }}" {{ $event->participation_type == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Max Participants</label>
                        <input type="number" name="max_participants" class="form-control"
                               value="{{ old('max_participants', $event->max_participants) }}"
                               placeholder="Leave blank for unlimited">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender Eligibility</label>
                        <select name="gender_eligibility" class="form-select">
                            @foreach(['all' => 'All', 'male' => 'Male Only', 'female' => 'Female Only'] as $value => $label)
                                <option value="{{ $value }}" {{ $event->gender_eligibility == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Age Criteria</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_age" class="form-control"
                                       placeholder="Min Age" value="{{ old('min_age', $event->min_age) }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_age" class="form-control"
                                       placeholder="Max Age" value="{{ old('max_age', $event->max_age) }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Region</label>
                        <select name="region_id" class="form-select">
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ $event->region_id == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }} ({{ $region->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Airport</label>
                        <select name="airport_id" class="form-select">
                            <option value="">Select Airport</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ $event->airport_id == $airport->id ? 'selected' : '' }}>
                                    {{ $airport->name }} ({{ $airport->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Form Template</label>
                        <select name="form_template_id" class="form-select">
                            <option value="">Select Template</option>
                            @foreach($formTemplates as $template)
                                <option value="{{ $template->id }}" {{ $event->form_template_id == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            <a href="{{ route('admin.forms.builder') }}" target="_blank">Create new template</a>
                        </small>
                    </div>

                    {{-- Dates --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" name="start_date" class="form-control"
                                   value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Date</label>
                            <input type="datetime-local" name="end_date" class="form-control"
                                   value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Registration Last Date</label>
                        <input type="datetime-local" name="registration_last_date" class="form-control"
                               value="{{ old('registration_last_date', $event->registration_last_date->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    {{-- Banner Upload --}}
                    <div class="mb-3">
                        <label class="form-label">Update Banner Image</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*">
                        <small class="text-muted">Leave blank to keep current banner</small>
                    </div>

                    {{-- Required Documents --}}
                    <div class="mb-3">
                        <label class="form-label">Required Documents</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="required_documents[]"
                                   value="medical_certificate" id="docMedical"
                                   {{ in_array('medical_certificate', $event->required_documents ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="docMedical">Medical Certificate</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="required_documents[]"
                                   value="id_card" id="docIdCard"
                                   {{ in_array('id_card', $event->required_documents ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="docIdCard">ID Card</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="required_documents[]"
                                   value="undertaking_form" id="docUndertaking"
                                   {{ in_array('undertaking_form', $event->required_documents ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="docUndertaking">Undertaking Form</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="required_documents[]"
                                   value="consent_form" id="docConsent"
                                   {{ in_array('consent_form', $event->required_documents ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="docConsent">Consent Form</label>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Update Event
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
