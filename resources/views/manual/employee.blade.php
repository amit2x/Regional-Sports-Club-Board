@extends('layouts.public')

@section('title', 'Employee User Manual - RSCB')

@push('styles')
<style>
    .manual-container { max-width: 1000px; margin: 0 auto; }
    .manual-content h2 { color: #0f172a; font-weight: 700; margin-top: 2rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0; }
    .manual-content h3 { color: #1e293b; font-weight: 600; margin-top: 1.5rem; }
    .step-card { background: white; border-radius: 14px; padding: 20px; margin-bottom: 16px; border-left: 4px solid #667eea; box-shadow: 0 2px 10px rgba(0,0,0,0.04); }
    .step-number { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 8px; font-weight: 700; font-size: 14px; margin-right: 10px; }
    .tip-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px; margin: 16px 0; }
    .warning-box { background: #fefce8; border: 1px solid #fef08a; border-radius: 12px; padding: 16px; margin: 16px 0; }
</style>
@endpush

@section('content')
<div class="manual-container py-4">
    <div class="manual-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">📘 Employee User Manual</h1>
            <a href="{{ route('manual.download') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i>Download PDF</a>
        </div>

        <section>
            <h2>Your Dashboard</h2>
            <p>After logging in, you'll see your personalized dashboard showing:</p>
            <ul>
                <li><strong>Registration Statistics:</strong> Total, Approved, Pending, Rejected</li>
                <li><strong>Upcoming Events:</strong> Events you're eligible for</li>
                <li><strong>Recent Registrations:</strong> Your latest event registrations</li>
            </ul>
        </section>

        <section>
            <h2>How to Register for an Event</h2>

            <div class="step-card">
                <span class="step-number">1</span>
                <strong>Go to Events</strong>
                <p class="mt-2 text-muted">Click on <strong>"Events"</strong> in the navigation or bottom menu.</p>
            </div>

            <div class="step-card">
                <span class="step-number">2</span>
                <strong>Choose an Event</strong>
                <p class="mt-2 text-muted">Browse available events. Only events you're eligible for will be shown. Click <strong>"Register"</strong> on the event you want to participate in.</p>
            </div>

            <div class="step-card">
                <span class="step-number">3</span>
                <strong>Fill the Registration Form</strong>
                <p class="mt-2 text-muted">Complete all required fields in the registration form. Fields marked with <span class="text-danger">*</span> are mandatory.</p>
            </div>

            <div class="step-card">
                <span class="step-number">4</span>
                <strong>Upload Documents</strong>
                <p class="mt-2 text-muted">Upload required documents like Medical Certificate, ID Card copy, and Undertaking Form. Accepted formats: PDF, JPG, PNG (Max 5MB).</p>
            </div>

            <div class="step-card">
                <span class="step-number">5</span>
                <strong>Submit or Save Draft</strong>
                <p class="mt-2 text-muted">Click <strong>"Submit"</strong> to send for approval, or <strong>"Save as Draft"</strong> to complete later.</p>
            </div>

            <div class="tip-box">
                <strong><i class="bi bi-lightbulb me-2"></i>Tip:</strong>
                <p class="mb-0 mt-1">You can save your registration as a draft and complete it later. Draft registrations are not sent for approval.</p>
            </div>
        </section>

        <section>
            <h2>Tracking Your Registrations</h2>
            <p>Go to <strong>"My Registrations"</strong> to view all your registrations and their status:</p>
            <ul>
                <li><span class="badge bg-secondary">Draft</span> - Not yet submitted</li>
                <li><span class="badge bg-warning">Pending</span> - Awaiting approval</li>
                <li><span class="badge bg-success">Approved</span> - Registration confirmed</li>
                <li><span class="badge bg-danger">Rejected</span> - Registration declined</li>
                <li><span class="badge bg-info">Withdrawn</span> - Cancelled by you</li>
            </ul>

            <div class="warning-box">
                <strong><i class="bi bi-exclamation-triangle me-2"></i>Note:</strong>
                <p class="mb-0 mt-1">You can only withdraw registrations that are in Draft or Pending status. Once approved, contact your sports secretary for any changes.</p>
            </div>
        </section>

        <section>
            <h2>Managing Your Profile</h2>
            <p>Click on your profile icon to:</p>
            <ul>
                <li>View your personal and employment details</li>
                <li>Change your password</li>
                <li>View notifications</li>
                <li>Logout from the portal</li>
            </ul>
        </section>

        <section>
            <h2>Notifications</h2>
            <p>You'll receive notifications for:</p>
            <ul>
                <li>New event announcements</li>
                <li>Registration approval/rejection</li>
                <li>Event reminders</li>
                <li>Document verification updates</li>
            </ul>
            <p>Click the bell icon <i class="bi bi-bell"></i> to view all notifications.</p>
        </section>
    </div>
</div>
@endsection
