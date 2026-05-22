@extends('layouts.admin')

@section('title', 'Regional Secretary Manual - RSCB')

@section('content')
<div class="manual-container py-4">
    <div class="manual-content">
        <h1>📘 Regional Sports Secretary Manual</h1>
        <p class="text-muted">Guide for Regional Sports Secretaries</p>

        <section>
            <h2>Your Responsibilities</h2>
            <ul>
                <li>Manage sports events for your region</li>
                <li>Oversee airports under your region</li>
                <li>Approve/reject participant registrations</li>
                <li>View regional participation reports</li>
                <li>Generate certificates for participants</li>
                <li>Manage announcements for your region</li>
            </ul>
        </section>

        <section>
            <h2>Regional Dashboard</h2>
            <p>Your dashboard displays region-specific statistics including total airports, employees, active events, and pending approvals.</p>
        </section>

        <section>
            <h2>Managing Events</h2>
            <p>You can create regional events and inter-airport tournaments. When creating an event, it will be associated with your region automatically.</p>
        </section>

        <section>
            <h2>Approval Workflow</h2>
            <div class="step-card">
                <span class="step-number">1</span>
                <strong>Review Pending Registrations</strong>
                <p class="mt-2 text-muted">Go to Registrations → Pending to see all awaiting approvals for your region.</p>
            </div>
            <div class="step-card">
                <span class="step-number">2</span>
                <strong>Verify Documents</strong>
                <p class="mt-2 text-muted">Check uploaded documents for authenticity before approval.</p>
            </div>
            <div class="step-card">
                <span class="step-number">3</span>
                <strong>Approve/Reject</strong>
                <p class="mt-2 text-muted">Approve eligible participants or reject with valid reasons.</p>
            </div>
        </section>
    </div>
</div>
@endsection
