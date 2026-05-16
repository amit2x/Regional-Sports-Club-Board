{{-- resources/views/admin/partials/footer.blade.php --}}
<footer class="admin-footer">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <small class="text-muted">
                    &copy; {{ date('Y') }} <strong>Regional Sports Control Board</strong>. All rights reserved.
                </small>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('website.privacy') }}" class="text-muted small text-decoration-none">Privacy</a>
                <a href="{{ route('website.terms') }}" class="text-muted small text-decoration-none">Terms</a>
                <a href="{{ route('website.help') }}" class="text-muted small text-decoration-none">Help</a>
                <span class="text-muted small">v1.0.0</span>
            </div>
        </div>
    </div>
</footer>
