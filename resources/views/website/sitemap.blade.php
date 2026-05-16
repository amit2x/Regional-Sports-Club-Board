@extends('layouts.public')

@section('title', 'Site Map - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Site Map</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h5><i class="bi bi-house me-2"></i>Main Pages</h5>
                                <ul class="list-unstyled">
                                    <li><a href="{{ url('/') }}">Home</a></li>
                                    <li><a href="{{ route('website.about') }}">About Us</a></li>
                                    <li><a href="{{ route('website.contact') }}">Contact Us</a></li>
                                    <li><a href="{{ route('website.help') }}">Help Center</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h5><i class="bi bi-calendar-event me-2"></i>Events</h5>
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('website.events') }}">Upcoming Events</a></li>
                                    <li><a href="{{ route('website.winners') }}">Winners</a></li>
                                    <li><a href="{{ route('website.gallery') }}">Gallery</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h5><i class="bi bi-info-circle me-2"></i>Information</h5>
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('website.announcements') }}">Announcements</a></li>
                                    <li><a href="{{ route('website.faq') }}">FAQ</a></li>
                                    <li><a href="{{ route('website.downloads') }}">Downloads</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h5><i class="bi bi-file-text me-2"></i>Legal</h5>
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('website.terms') }}">Terms & Conditions</a></li>
                                    <li><a href="{{ route('website.privacy') }}">Privacy Policy</a></li>
                                    <li><a href="{{ route('website.disclaimer') }}">Disclaimer</a></li>
                                    <li><a href="{{ route('website.copyright') }}">Copyright</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h5><i class="bi bi-person me-2"></i>Account</h5>
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('employee.login') }}">Employee Login</a></li>
                                    <li><a href="{{ route('employee.password.request') }}">Forgot Password</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h5><i class="bi bi-universal-access me-2"></i>Accessibility</h5>
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('website.accessibility') }}">Accessibility Statement</a></li>
                                    <li><a href="{{ route('website.feedback') }}">Feedback</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
