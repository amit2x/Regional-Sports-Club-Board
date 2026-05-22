{{-- resources/views/layouts/partials/footer.blade.php --}}
<footer class="footer-premium mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white mb-3 d-flex align-items-center">
                    <i class="bi bi-trophy-fill me-2 fs-4" style="color: var(--primary);"></i>RSCB
                </h5>
                <p class="text-white-50 mb-4">Promoting sports and fitness among airport employees across all regions of India.</p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white mb-3 fw-bold">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('website.events') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Events</a></li>
                    <li class="mb-2"><a href="{{ route('website.announcements') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Announcements</a></li>
                    <li class="mb-2"><a href="{{ route('website.gallery') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Gallery</a></li>
                    <li class="mb-2"><a href="{{ route('website.winners') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Winners</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white mb-3 fw-bold">Resources</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('website.downloads') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Downloads</a></li>
                    <li class="mb-2"><a href="{{ route('manual') }}" class="footer-link"><i class="bi bi-book me-1 small"></i>User Manual</a></li
                    <li class="mb-2"><a href="{{ route('website.faq') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>FAQ</a></li>
                    <li class="mb-2"><a href="{{ route('website.help') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Help Center</a></li>
                    <li class="mb-2"><a href="{{ route('website.about') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>About Us</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white mb-3 fw-bold">Legal</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('website.privacy') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Privacy Policy</a></li>
                    <li class="mb-2"><a href="{{ route('website.terms') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Terms & Conditions</a></li>
                    <li class="mb-2"><a href="{{ route('website.disclaimer') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Disclaimer</a></li>
                    <li class="mb-2"><a href="{{ route('website.accessibility') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Accessibility</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white mb-3 fw-bold">Contact</h6>
                <ul class="list-unstyled">
                    <li class="mb-2 text-white-50 small"><i class="bi bi-geo-alt me-2"></i>Kolkata, West Bengal, India</li>
                    <li class="mb-2"><a href="mailto:info@rscb.aai.aero" class="footer-link"><i class="bi bi-envelope me-2"></i>info@rscb.aai.aero</a></li>
                    <li class="mb-2 text-white-50 small"><i class="bi bi-telephone me-2"></i>+91-XXXXXXXXXX</li>
                </ul>
            </div>
        </div>
        <hr class="my-4 border-secondary opacity-20">
        <div class="row align-items-center pb-4">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small text-white-50">&copy; {{ date('Y') }} <strong class="text-white">RSCB</strong>. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <small class="text-white-50">Version 1.0.0 | Made with <i class="bi bi-heart-fill text-danger"></i> for Sports</small>
            </div>
        </div>
    </div>
</footer>
