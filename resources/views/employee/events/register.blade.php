{{-- resources/views/employee/events/register.blade.php --}}
@extends('layouts.public')

@section('title', 'Event Registration')

@section('content')
<div class="page-content  container">
    <a href="{{ route('employee.events.available') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>

    {{-- Event Info --}}
    <div class="employee-card">
        <div class="card-header">{{ $event->event_name }}</div>
        <div class="card-body">
            <small class="text-muted">{{ $event->event_code }}</small>
            <p class="mt-2"><i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}</p>
            <p><i class="bi bi-calendar me-1"></i>{{ $event->start_date->format('d M Y') }}</p>
            <p class="text-danger"><i class="bi bi-clock me-1"></i>Last date: {{ $event->registration_last_date->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Registration Form --}}
    <form id="registrationForm" action="{{ route('employee.events.submit-registration', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="submit_type" id="submitType" value="submit">

        <div class="employee-card">
            <div class="card-header">Registration Form</div>
            <div class="card-body">
                @foreach($formFields as $field)
                <div class="form-group-mobile">
                    <label class="form-label-mobile">
                        {{ $field['label'] }} @if($field['required'] ?? false)<span class="text-danger">*</span>@endif
                    </label>

                    @switch($field['type'])
                        @case('text')
                            <input type="text" name="{{ $field['name'] }}" class="form-control-mobile"
                                   placeholder="{{ $field['placeholder'] ?? '' }}"
                                   {{ ($field['required'] ?? false) ? 'required' : '' }}>
                            @break
                        @case('textarea')
                            <textarea name="{{ $field['name'] }}" class="form-control-mobile" rows="3"
                                      placeholder="{{ $field['placeholder'] ?? '' }}"
                                      {{ ($field['required'] ?? false) ? 'required' : '' }}></textarea>
                            @break
                        @case('number')
                            <input type="number" name="{{ $field['name'] }}" class="form-control-mobile"
                                   placeholder="{{ $field['placeholder'] ?? '' }}"
                                   {{ ($field['required'] ?? false) ? 'required' : '' }}>
                            @break
                        @case('email')
                            <input type="email" name="{{ $field['name'] }}" class="form-control-mobile"
                                   placeholder="{{ $field['placeholder'] ?? '' }}"
                                   {{ ($field['required'] ?? false) ? 'required' : '' }}>
                            @break
                        @case('mobile')
                            <input type="tel" name="{{ $field['name'] }}" class="form-control-mobile"
                                   placeholder="10-digit number" maxlength="10"
                                   {{ ($field['required'] ?? false) ? 'required' : '' }}>
                            @break
                        @case('date')
                            <input type="date" name="{{ $field['name'] }}" class="form-control-mobile"
                                   {{ ($field['required'] ?? false) ? 'required' : '' }}>
                            @break
                        @case('file')
                            <input type="file" name="documents[{{ $field['name'] }}]" class="form-control-mobile"
                                   {{ ($field['required'] ?? false) ? 'required' : '' }}>
                            @break
                        @case('dropdown')
                            <select name="{{ $field['name'] }}" class="form-control-mobile"
                                    {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                <option value="">{{ $field['placeholder'] ?? 'Select' }}</option>
                                @foreach($field['options'] ?? [] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            @break
                        @case('declaration')
                            <div class="form-check">
                                <input type="checkbox" name="{{ $field['name'] }}" class="form-check-input" required>
                                <label class="form-check-label small">{{ $field['placeholder'] ?? 'I agree' }}</label>
                            </div>
                            @break
                        @default
                            <input type="text" name="{{ $field['name'] }}" class="form-control-mobile"
                                   placeholder="{{ $field['placeholder'] ?? '' }}">
                    @endswitch
                </div>
                @endforeach
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn btn-outline-secondary btn-mobile flex-fill" onclick="saveDraft()">
                <i class="bi bi-save me-1"></i>Save Draft
            </button>
            <button type="submit" class="btn btn-primary-mobile btn-mobile flex-fill">
                <i class="bi bi-send me-1"></i>Submit
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function saveDraft() {
    $('#submitType').val('draft');
    $('#registrationForm').submit();
}
</script>
@endpush
