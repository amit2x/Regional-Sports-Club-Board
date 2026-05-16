@extends('layouts.public')

@section('title', 'Terms & Conditions - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h2 class="section-title">Terms & Conditions</h2>
                <p class="text-muted text-center mb-5">Last updated: {{ date('F d, Y') }}</p>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="terms-content">
                            <h4>1. Acceptance of Terms</h4>
                            <p>By accessing and using the Regional Sports Control Board (RSCB) portal, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you should not use the portal.</p>

                            <h4>2. Definitions</h4>
                            <ul>
                                <li><strong>"Portal"</strong> refers to the RSCB Management System website and web application.</li>
                                <li><strong>"User"</strong> refers to any employee, administrator, or authorized personnel accessing the portal.</li>
                                <li><strong>"Employee"</strong> refers to authorized employees of participating airports and regional offices.</li>
                                <li><strong>"Content"</strong> includes all information, data, text, images, and materials available on the portal.</li>
                            </ul>

                            <h4>3. User Registration and Account</h4>
                            <ul>
                                <li>Users must provide accurate and complete information during registration.</li>
                                <li>Each user is responsible for maintaining the confidentiality of their login credentials.</li>
                                <li>Users must not share their account credentials with any third party.</li>
                                <li>The portal reserves the right to suspend or terminate accounts that violate these terms.</li>
                                <li>Users must immediately report any unauthorized access or security breach.</li>
                            </ul>

                            <h4>4. User Obligations</h4>
                            <p>Users agree to:</p>
                            <ul>
                                <li>Comply with all applicable laws and regulations.</li>
                                <li>Use the portal only for its intended purpose.</li>
                                <li>Not engage in any activity that disrupts or interferes with the portal's operation.</li>
                                <li>Not upload or transmit any malicious code, viruses, or harmful content.</li>
                                <li>Respect the privacy and rights of other users.</li>
                                <li>Provide accurate information in all forms and submissions.</li>
                                <li>Not misuse or manipulate the portal's features or data.</li>
                            </ul>

                            <h4>5. Event Registration</h4>
                            <ul>
                                <li>Registration for events is subject to eligibility criteria specified for each event.</li>
                                <li>Submission of registration does not guarantee acceptance.</li>
                                <li>All registrations are subject to approval by designated authorities.</li>
                                <li>Users must provide all required documents as specified for each event.</li>
                                <li>False or misleading information may result in registration rejection or cancellation.</li>
                                <li>Registration deadlines must be strictly adhered to.</li>
                            </ul>

                            <h4>6. Intellectual Property Rights</h4>
                            <p>All content, trademarks, logos, and intellectual property on the portal are owned by or licensed to RSCB. Users may not copy, reproduce, distribute, or create derivative works without express written permission.</p>

                            <h4>7. Privacy and Data Protection</h4>
                            <p>The collection, use, and protection of personal information is governed by our Privacy Policy. By using the portal, you consent to the collection and use of your information as described in the Privacy Policy.</p>

                            <h4>8. Limitation of Liability</h4>
                            <p>RSCB shall not be liable for:</p>
                            <ul>
                                <li>Any indirect, incidental, or consequential damages arising from the use of the portal.</li>
                                <li>Loss of data or information due to technical issues beyond our control.</li>
                                <li>Any damages resulting from unauthorized access to user accounts.</li>
                                <li>Errors or omissions in the content provided on the portal.</li>
                            </ul>

                            <h4>9. Modifications to Service</h4>
                            <p>RSCB reserves the right to:</p>
                            <ul>
                                <li>Modify or discontinue any part of the portal without prior notice.</li>
                                <li>Update these terms and conditions at any time.</li>
                                <li>Change event details, schedules, or requirements as necessary.</li>
                                <li>Add or remove features and functionalities.</li>
                            </ul>

                            <h4>10. Termination</h4>
                            <p>RSCB may terminate or suspend user access to the portal immediately, without prior notice, for:</p>
                            <ul>
                                <li>Violation of these terms and conditions.</li>
                                <li>Fraudulent or illegal activities.</li>
                                <li>Actions that may harm the portal or other users.</li>
                                <li>Employment status changes (transfer, retirement, termination).</li>
                            </ul>

                            <h4>11. Governing Law</h4>
                            <p>These terms and conditions are governed by and construed in accordance with the laws of India. Any disputes arising from the use of this portal shall be subject to the exclusive jurisdiction of courts in New Delhi, India.</p>

                            <h4>12. Contact Information</h4>
                            <p>For questions about these Terms and Conditions, please contact:</p>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1"><strong>Regional Sports Control Board</strong></p>
                                <p class="mb-1">New Delhi, India</p>
                                <p class="mb-1">Email: legal@rscb.gov.in</p>
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
    .terms-content h4 {
        color: #667eea;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .terms-content h4:first-child {
        margin-top: 0;
    }

    .terms-content ul {
        margin-bottom: 1.5rem;
    }

    .terms-content ul li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush
