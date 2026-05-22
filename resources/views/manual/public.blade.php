@extends('layouts.public')

@section('title', 'User Manual - RSCB')

@push('styles')
<style>
    .manual-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .manual-sidebar {
        position: sticky;
        top: 100px;
    }

    .manual-sidebar .nav-link {
        padding: 10px 16px;
        color: #64748b;
        border-left: 3px solid transparent;
        border-radius: 0;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .manual-sidebar .nav-link:hover,
    .manual-sidebar .nav-link.active {
        color: #667eea;
        border-left-color: #667eea;
        background: rgba(102,126,234,0.05);
    }

    .manual-content h2 {
        color: #0f172a;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .manual-content h3 {
        color: #1e293b;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .manual-content .step-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 16px;
        border-left: 4px solid #667eea;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .manual-content .step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: var(--primary-gradient);
        color: white;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        margin-right: 10px;
    }

    .manual-content .tip-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 16px;
        margin: 16px 0;
    }

    .manual-content .warning-box {
        background: #fefce8;
        border: 1px solid #fef08a;
        border-radius: 12px;
        padding: 16px;
        margin: 16px 0;
    }

    .manual-content .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 16px;
        margin: 16px 0;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin: 20px 0;
    }

    .feature-item {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.3s;
    }

    .feature-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .feature-item i {
        font-size: 2rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .manual-sidebar { display: none; }
    }
</style>
@endpush

@section('content')
<div class="manual-container py-4">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="manual-sidebar">
                <h6 class="fw-bold mb-3 text-uppercase small text-muted">Contents</h6>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#overview">Overview</a>
                    <a class="nav-link" href="#features">Key Features</a>
                    <a class="nav-link" href="#getting-started">Getting Started</a>
                    <a class="nav-link" href="#how-to-login">How to Login</a>
                    <a class="nav-link" href="#browsing-events">Browsing Events</a>
                    <a class="nav-link" href="#announcements">Announcements</a>
                    <a class="nav-link" href="#gallery">Gallery</a>
                    <a class="nav-link" href="#contact">Contact & Support</a>
                </nav>
            </div>
        </div>

        {{-- Content --}}
        <div class="col-lg-9 manual-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">User Manual</h1>
                <a href="{{ route('manual.download') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download me-1"></i>Download PDF
                </a>
            </div>

            {{-- Overview --}}
            <section id="overview">
                <h2>📖 Overview</h2>
                <p>Welcome to the <strong>Regional Sports Control Board (RSCB)</strong> portal – a centralized platform for managing sports events across airports and regional offices. This manual will guide you through all the features available on the portal.</p>

                <div class="info-box">
                    <strong><i class="bi bi-info-circle me-2"></i>About RSCB</strong>
                    <p class="mb-0 mt-1">RSCB is dedicated to promoting sports and fitness among airport employees. The portal allows employees to view upcoming events, register for participation, and stay updated with announcements.</p>
                </div>
            </section>

            {{-- Features --}}
            <section id="features">
                <h2>✨ Key Features</h2>
                <div class="feature-grid">
                    <div class="feature-item">
                        <i class="bi bi-calendar-event"></i>
                        <h6>Event Listings</h6>
                        <small class="text-muted">Browse upcoming sports events and tournaments</small>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-megaphone"></i>
                        <h6>Announcements</h6>
                        <small class="text-muted">Stay updated with latest news and notices</small>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-images"></i>
                        <h6>Photo Gallery</h6>
                        <small class="text-muted">View photos from past sports events</small>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-trophy"></i>
                        <h6>Winners</h6>
                        <small class="text-muted">See winners and achievements</small>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-download"></i>
                        <h6>Downloads</h6>
                        <small class="text-muted">Access forms and documents</small>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-person-check"></i>
                        <h6>Registration</h6>
                        <small class="text-muted">Register for events (login required)</small>
                    </div>
                </div>
            </section>

            {{-- Getting Started --}}
            <section id="getting-started">
                <h2>🚀 Getting Started</h2>

                <h3>System Requirements</h3>
                <ul>
                    <li>A modern web browser (Chrome, Firefox, Edge, Safari)</li>
                    <li>Internet connection</li>
                    <li>Valid Employee ID for login</li>
                </ul>

                <h3>Accessing the Portal</h3>
                <div class="step-card">
                    <span class="step-number">1</span>
                    <strong>Open your web browser</strong>
                    <p class="mt-2 text-muted">Launch any modern web browser on your computer or mobile device.</p>
                </div>
                <div class="step-card">
                    <span class="step-number">2</span>
                    <strong>Visit the RSCB website</strong>
                    <p class="mt-2 text-muted">Enter the RSCB portal URL in the address bar. The homepage displays upcoming events and announcements.</p>
                </div>
                <div class="step-card">
                    <span class="step-number">3</span>
                    <strong>Explore the website</strong>
                    <p class="mt-2 text-muted">Browse through Events, Announcements, Gallery, and Winners sections from the navigation menu.</p>
                </div>
            </section>

            {{-- How to Login --}}
            <section id="how-to-login">
                <h2>🔐 How to Login</h2>

                <div class="step-card">
                    <span class="step-number">1</span>
                    <strong>Click on "Employee Login"</strong>
                    <p class="mt-2 text-muted">Find the <strong>"Employee Login"</strong> button on the top right corner of the website, or use the <strong>"Login"</strong> option in the mobile bottom menu.</p>
                </div>

                <div class="step-card">
                    <span class="step-number">2</span>
                    <strong>Enter your credentials</strong>
                    <p class="mt-2 text-muted">
                        <strong>Employee ID:</strong> Your unique employee identification number<br>
                        <strong>Password:</strong> Your PAN number (for first-time login)
                    </p>
                </div>

                <div class="step-card">
                    <span class="step-number">3</span>
                    <strong>Change default password</strong>
                    <p class="mt-2 text-muted">On first login, you will be prompted to change your password. Create a strong password with at least 8 characters including uppercase, lowercase, numbers, and special characters.</p>
                </div>

                <div class="warning-box">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Important!</strong>
                    <ul class="mb-0 mt-1">
                        <li>Never share your login credentials with anyone.</li>
                        <li>Change your password immediately after first login.</li>
                        <li>Contact your sports secretary if you forget your password.</li>
                    </ul>
                </div>

                <h3>Alternative: Google Login</h3>
                <p>If enabled, you can also log in using your Google account linked to your employee email. Click on <strong>"Login with Google"</strong> on the login page.</p>
            </section>

            {{-- Browsing Events --}}
            <section id="browsing-events">
                <h2>📅 Browsing Events</h2>

                <div class="step-card">
                    <span class="step-number">1</span>
                    <strong>Navigate to Events</strong>
                    <p class="mt-2 text-muted">Click on <strong>"Events"</strong> in the navigation menu or bottom bar.</p>
                </div>

                <div class="step-card">
                    <span class="step-number">2</span>
                    <strong>Filter events</strong>
                    <p class="mt-2 text-muted">Use the filters to find events by type (Regional, Airport, Inter-Airport) or search by name.</p>
                </div>

                <div class="step-card">
                    <span class="step-number">3</span>
                    <strong>View event details</strong>
                    <p class="mt-2 text-muted">Click on any event to see full details including venue, dates, eligibility criteria, and registration deadline.</p>
                </div>

                <div class="tip-box">
                    <strong><i class="bi bi-lightbulb me-2"></i>Tip:</strong>
                    <p class="mb-0 mt-1">Login to see events you are eligible for and to register directly.</p>
                </div>
            </section>

            {{-- Announcements --}}
            <section id="announcements">
                <h2>📢 Announcements</h2>
                <p>The Announcements section displays important notices, circulars, and updates from RSCB. Announcements are color-coded by priority:</p>
                <ul>
                    <li><span class="badge bg-danger">Urgent</span> - Requires immediate attention</li>
                    <li><span class="badge bg-warning">High</span> - Important updates</li>
                    <li><span class="badge bg-info">Medium</span> - General notices</li>
                    <li><span class="badge bg-secondary">Low</span> - Informational</li>
                </ul>
            </section>

            {{-- Gallery --}}
            <section id="gallery">
                <h2>🖼️ Gallery</h2>
                <p>View photos from past sports events in the Gallery section. You can filter by category and click on any image to view it in full size.</p>
            </section>

            {{-- Contact --}}
            <section id="contact">
                <h2>📞 Contact & Support</h2>
                <p>If you need help or have questions:</p>
                <ul>
                    <li>Visit the <a href="{{ route('website.contact') }}">Contact Us</a> page</li>
                    <li>Email: info@rscb.aai.aero</li>
                    <li>Contact your Airport Sports Secretary</li>
                    <li>Visit the <a href="{{ route('website.faq') }}">FAQ</a> page for common questions</li>
                </ul>
            </section>

            <div class="text-center mt-5">
                <a href="{{ route('employee.login') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Access More Features
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
