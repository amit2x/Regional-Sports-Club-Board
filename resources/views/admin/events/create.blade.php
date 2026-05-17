@extends('layouts.admin')

@section('title', 'Create Event')

@section('content')
<div class="page-header">
    <h1 class="page-title">Create New Event</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Event Name <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" class="form-control" value="{{ old('event_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Code <span class="text-danger">*</span></label>
                        <input type="text" name="event_code" class="form-control" value="{{ old('event_code') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Venue <span class="text-danger">*</span></label>
                        <input type="text" name="venue" class="form-control" value="{{ old('venue') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rules & Regulations</label>
                        <textarea name="rules_regulations" class="form-control" rows="4">{{ old('rules_regulations') }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Event Type <span class="text-danger">*</span></label>
                        <select name="event_type" class="form-select" required>
                            <option value="">Select</option>
                            <option value="regional">Regional</option>
                            <option value="airport">Airport</option>
                            <option value="inter_airport">Inter-Airport</option>
                            <option value="annual_meet">Annual Meet</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Participation Type</label>
                        <select name="participation_type" class="form-select">
                            <option value="individual">Individual</option>
                            <option value="team">Team</option>
                            <option value="both">Both</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Participants</label>
                        <input type="number" name="max_participants" class="form-control" value="{{ old('max_participants') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender Eligibility</label>
                        <select name="gender_eligibility" class="form-select">
                            <option value="all">All</option>
                            <option value="male">Male Only</option>
                            <option value="female">Female Only</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Region</label>
                        <select name="region_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Airport</label>
                        <select name="airport_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Date</label>
                            <input type="datetime-local" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Registration Last Date</label>
                        <input type="datetime-local" name="registration_last_date" class="form-control" required>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Banner Image</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*">
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-lg me-1"></i>Create Event
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
