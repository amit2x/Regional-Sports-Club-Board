@extends('layouts.admin')

@section('title', 'Airport Secretary Manual - RSCB')

@section('content')
<div class="manual-container py-4">
    <div class="manual-content">
        <h1>📘 Airport Sports Secretary Manual</h1>
        <p class="text-muted">Guide for Airport Sports Secretaries</p>

        <section>
            <h2>Your Responsibilities</h2>
            <ul>
                <li>Create airport-level sports events</li>
                <li>Verify employee registrations for your airport</li>
                <li>View airport-specific dashboard and reports</li>
                <li>Manage gallery and upload event photos</li>
                <li>Handle employee queries from your airport</li>
            </ul>
        </section>

        <section>
            <h2>Airport Dashboard</h2>
            <p>View your airport's statistics including active employees, events, and pending verifications.</p>
        </section>

        <section>
            <h2>Creating Airport Events</h2>
            <div class="step-card">
                <span class="step-number">1</span>
                <strong>Create Event</strong>
                <p class="mt-2 text-muted">Go to Events → Create. The event will be associated with your airport.</p>
            </div>
            <div class="step-card">
                <span class="step-number">2</span>
                <strong>Set Eligibility</strong>
                <p class="mt-2 text-muted">Configure who can participate - your airport employees will be eligible by default.</p>
            </div>
        </section>

        <section>
            <h2>Verifying Registrations</h2>
            <p>Review and verify employee registrations for events at your airport. Check documents and confirm eligibility before regional approval.</p>
        </section>
    </div>
</div>
@endsection
