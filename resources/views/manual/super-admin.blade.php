@extends('layouts.admin')

@section('title', 'Administrator Manual - RSCB')

@push('styles')
<style>
    .manual-container { max-width: 1000px; margin: 0 auto; }
    .manual-content h2 { color: #0f172a; font-weight: 700; margin-top: 2rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0; }
    .manual-content h3 { color: #1e293b; font-weight: 600; margin-top: 1.5rem; }
    .step-card { background: white; border-radius: 14px; padding: 20px; margin-bottom: 16px; border-left: 4px solid #dc3545; box-shadow: 0 2px 10px rgba(0,0,0,0.04); }
    .step-card.secretary { border-left-color: #0d6efd; }
    .step-card.airport { border-left-color: #0dcaf0; }
    .step-number { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 8px; font-weight: 700; font-size: 14px; margin-right: 10px; }
    .tip-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px; margin: 16px 0; }
</style>
@endpush

@section('content')
<div class="manual-container py-4">
    <div class="manual-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">🛡️ Administrator Manual</h1>
            <a href="{{ route('manual.download') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i>Download PDF</a>
        </div>

        <section>
            <h2>Admin Dashboard Overview</h2>
            <p>As a Super Admin, you have complete access to manage the entire RSCB system. Your dashboard shows key statistics across all regions.</p>
        </section>

        <section>
            <h2>Managing Regions & Airports</h2>
            <div class="step-card">
                <span class="step-number">1</span>
                <strong>Navigate to Regions/Airports</strong>
                <p class="mt-2 text-muted">Use the sidebar menu to access <strong>Regions</strong> or <strong>Airports</strong> management.</p>
            </div>
            <div class="step-card">
                <span class="step-number">2</span>
                <strong>Add/Edit Records</strong>
                <p class="mt-2 text-muted">Use the <strong>"Add"</strong> button to create new regions/airports. Click edit icon to modify existing ones.</p>
            </div>
        </section>

        <section>
            <h2>Employee Management</h2>
            <div class="step-card">
                <span class="step-number">1</span>
                <strong>View Employees</strong>
                <p class="mt-2 text-muted">Access the Employees section to view all registered employees with filters for region, airport, status, and gender.</p>
            </div>
            <div class="step-card">
                <span class="step-number">2</span>
                <strong>Import Employees</strong>
                <p class="mt-2 text-muted">Use the <strong>"Import Excel"</strong> button to bulk import employees. Download the template first to ensure correct format.</p>
            </div>
            <div class="step-card">
                <span class="step-number">3</span>
                <strong>Manage Roles</strong>
                <p class="mt-2 text-muted">Go to <strong>Settings → Roles & Permissions</strong> to assign roles (Super Admin, Regional Secretary, Airport Secretary, Employee) to users.</p>
            </div>
        </section>

        <section>
            <h2>Event Management</h2>
            <div class="step-card">
                <span class="step-number">1</span>
                <strong>Create Events</strong>
                <p class="mt-2 text-muted">Navigate to Events → Create Event. Fill in event details, dates, eligibility criteria, and upload a banner image.</p>
            </div>
            <div class="step-card">
                <span class="step-number">2</span>
                <strong>Create Registration Forms</strong>
                <p class="mt-2 text-muted">Use the <strong>Form Builder</strong> to create custom registration forms. Drag and drop fields to design your form.</p>
            </div>
            <div class="step-card">
                <span class="step-number">3</span>
                <strong>Publish Events</strong>
                <p class="mt-2 text-muted">After creating an event and attaching a form template, click <strong>"Publish"</strong> to make it visible to eligible employees.</p>
            </div>
        </section>

        <section>
            <h2>Registration Approvals</h2>
            <div class="step-card">
                <span class="step-number">1</span>
                <strong>Review Registrations</strong>
                <p class="mt-2 text-muted">Go to <strong>Registrations</strong> to view all submitted registrations. Filter by pending status for approvals.</p>
            </div>
            <div class="step-card">
                <span class="step-number">2</span>
                <strong>Verify Documents</strong>
                <p class="mt-2 text-muted">Click on a registration to view uploaded documents. Verify each document before approving the registration.</p>
            </div>
            <div class="step-card">
                <span class="step-number">3</span>
                <strong>Approve or Reject</strong>
                <p class="mt-2 text-muted">Use the <strong>Approve</strong> or <strong>Reject</strong> buttons. For rejections, provide a clear reason that will be shared with the employee.</p>
            </div>
        </section>

        <section>
            <h2>Generating Certificates</h2>
            <p>After events are completed, generate participation certificates:</p>
            <ol>
                <li>Go to <strong>Certificates</strong> section</li>
                <li>Select an event and approved registrations</li>
                <li>Click <strong>"Bulk Generate"</strong> to create certificates with QR verification</li>
                <li>Certificates can be downloaded individually or in bulk</li>
            </ol>
        </section>

        <section>
            <h2>Reports & Analytics</h2>
            <p>Access comprehensive reports from the <strong>Reports</strong> section:</p>
            <ul>
                <li>Participation reports (filter by region, airport, gender, status)</li>
                <li>Event-wise statistics</li>
                <li>Region and gender analytics</li>
                <li>Export reports in Excel or PDF format</li>
            </ul>
        </section>

        <section>
            <h2>Audit Logs</h2>
            <p>Monitor all system activities through <strong>Settings → Audit Logs</strong>. Track user logins, data changes, approvals, and other actions.</p>
        </section>
    </div>
</div>
@endsection
