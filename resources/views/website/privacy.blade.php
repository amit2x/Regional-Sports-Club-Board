@extends('layouts.public')

@section('title', 'Privacy Policy - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h2 class="section-title">Privacy Policy</h2>
                <p class="text-muted text-center mb-5">Last updated: {{ date('F d, Y') }}</p>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="privacy-content">
                            <h4>1. Introduction</h4>
                            <p>Regional Sports Control Board (RSCB) is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our portal.</p>

                            <h4>2. Information We Collect</h4>
                            <p><strong>Personal Information:</strong></p>
                            <ul>
                                <li>Employee ID and name</li>
                                <li>Contact information (email, phone number)</li>
                                <li>Date of birth, gender, blood group</li>
                                <li>Department, designation, and employment details</li>
                                <li>PAN number (for authentication purposes)</li>
                                <li>Profile photographs</li>
                                <li>Medical information relevant to sports participation</li>
                            </ul>

                            <p><strong>Usage Information:</strong></p>
                            <ul>
                                <li>Login history and IP addresses</li>
                                <li>Event registration and participation history</li>
                                <li>Documents uploaded for event registration</li>
                                <li>Activity logs within the portal</li>
                                <li>Device and browser information</li>
                            </ul>

                            <h4>3. How We Use Your Information</h4>
                            <p>We use the collected information for:</p>
                            <ul>
                                <li>User authentication and account management</li>
                                <li>Processing event registrations</li>
                                <li>Verifying eligibility for sports events</li>
                                <li>Generating participation certificates</li>
                                <li>Communication regarding events and announcements</li>
                                <li>Statistical analysis and reporting</li>
                                <li>Improving portal functionality and user experience</li>
                                <li>Compliance with legal and regulatory requirements</li>
                            </ul>

                            <h4>4. Data Storage and Security</h4>
                            <ul>
                                <li>All data is stored on secure servers with encryption.</li>
                                <li>Access to personal data is restricted to authorized personnel only.</li>
                                <li>We implement industry-standard security measures to protect your data.</li>
                                <li>Regular security audits and vulnerability assessments are conducted.</li>
                                <li>Data backup and disaster recovery procedures are in place.</li>
                            </ul>

                            <h4>5. Data Sharing and Disclosure</h4>
                            <p>We may share your information with:</p>
                            <ul>
                                <li>Authorized airport and regional sports secretaries</li>
                                <li>Event organizers and officials (limited to event-specific data)</li>
                                <li>Government authorities as required by law</li>
                                <li>Third-party service providers (under strict confidentiality agreements)</li>
                            </ul>
                            <p>We do NOT sell, rent, or trade personal information to third parties for marketing purposes.</p>

                            <h4>6. Data Retention</h4>
                            <ul>
                                <li>Employee data is retained for the duration of employment plus 3 years.</li>
                                <li>Event registration data is retained for 5 years for record-keeping.</li>
                                <li>Certificates and achievement records are retained permanently.</li>
                                <li>Activity logs are retained for 1 year.</li>
                                <li>Users can request data deletion by contacting the administrator.</li>
                            </ul>

                            <h4>7. Your Rights</h4>
                            <p>You have the right to:</p>
                            <ul>
                                <li>Access your personal data stored in the portal.</li>
                                <li>Request correction of inaccurate information.</li>
                                <li>Request deletion of your data (subject to retention policies).</li>
                                <li>Withdraw consent for non-essential data processing.</li>
                                <li>File a complaint with the designated Grievance Officer.</li>
                            </ul>

                            <h4>8. Cookies and Tracking</h4>
                            <ul>
                                <li>We use essential cookies for authentication and session management.</li>
                                <li>Analytics cookies help us understand portal usage patterns.</li>
                                <li>Users can disable non-essential cookies through browser settings.</li>
                                <li>We do not use tracking cookies for advertising purposes.</li>
                            </ul>

                            <h4>9. Third-Party Services</h4>
                            <ul>
                                <li>Google OAuth is used for alternative login (subject to Google's privacy policy).</li>
                                <li>Email notifications are sent through our authorized email service.</li>
                                <li>We are not responsible for the privacy practices of external links.</li>
                            </ul>

                            <h4>10. Children's Privacy</h4>
                            <p>This portal is intended for adult employees. We do not knowingly collect information from individuals under 18 years of age.</p>

                            <h4>11. Changes to Privacy Policy</h4>
                            <p>We may update this Privacy Policy from time to time. Users will be notified of significant changes through the portal or via email. Continued use of the portal after changes constitutes acceptance of the updated policy.</p>

                            <h4>12. Grievance Officer</h4>
                            <p>For privacy-related concerns, contact:</p>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1"><strong>Grievance Officer</strong></p>
                                <p class="mb-1">Regional Sports Control Board</p>
                                <p class="mb-1">New Delhi, India</p>
                                <p class="mb-1">Email: privacy@rscb.gov.in</p>
                                <p class="mb-0">Phone: +91-11-XXXXXXXX</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .privacy-content h4 {
        color: #667eea;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .privacy-content h4:first-child {
        margin-top: 0;
    }

    .privacy-content ul {
        margin-bottom: 1.5rem;
    }

    .privacy-content ul li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush
