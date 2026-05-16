@extends('layouts.public')

@section('title', 'FAQ - RSCB')

@push('styles')
<style>
    .faq-section {
        max-width: 900px;
        margin: 0 auto;
    }

    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        border-color: #667eea;
    }

    .faq-search {
        position: relative;
        margin-bottom: 2rem;
    }

    .faq-search .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .faq-search input {
        padding-left: 45px;
        border-radius: 50px;
        height: 55px;
        font-size: 1rem;
    }

    .faq-categories .btn {
        border-radius: 50px;
        margin: 3px;
    }

    .no-results {
        display: none;
        text-align: center;
        padding: 3rem;
    }
</style>
@endpush

@section('content')
<section class="py-5">
    <div class="container">
        <div class="faq-section">
            {{-- Header --}}
            <div class="text-center mb-5">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="text-muted">Find answers to common questions about RSCB</p>
            </div>

            {{-- Search --}}
            <div class="faq-search">
                <i class="bi bi-search search-icon fs-5"></i>
                <input type="text"
                       id="faqSearch"
                       class="form-control"
                       placeholder="Search for questions...">
            </div>

            {{-- Categories --}}
            <div class="faq-categories text-center mb-4">
                <button class="btn btn-outline-primary btn-sm category-filter active" data-category="all">All</button>
                <button class="btn btn-outline-primary btn-sm category-filter" data-category="general">General</button>
                <button class="btn btn-outline-primary btn-sm category-filter" data-category="registration">Registration</button>
                <button class="btn btn-outline-primary btn-sm category-filter" data-category="account">Account</button>
                <button class="btn btn-outline-primary btn-sm category-filter" data-category="technical">Technical</button>
                <button class="btn btn-outline-primary btn-sm category-filter" data-category="events">Events</button>
            </div>

            {{-- FAQ Accordion --}}
            <div class="accordion" id="faqAccordion">
                @foreach($faqs as $index => $faq)
                    <div class="accordion-item mb-3 border rounded-3 shadow-sm faq-item"
                         data-category="{{ $faq['category'] ?? 'general' }}">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq{{ $index }}">
                                <span class="me-3 text-primary fw-bold">Q{{ $index + 1 }}.</span>
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="faq{{ $index }}"
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                <div class="d-flex">
                                    <span class="me-3 text-success fw-bold">A.</span>
                                    <p class="mb-0">{{ $faq['answer'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- No Results --}}
            <div class="no-results" id="noResults">
                <i class="bi bi-search text-muted" style="font-size: 64px;"></i>
                <h4 class="mt-3">No matching questions found</h4>
                <p class="text-muted">Try different keywords or browse all categories</p>
                <button class="btn btn-primary" onclick="resetFilters()">Show All FAQs</button>
            </div>

            {{-- Still Have Questions --}}
            <div class="text-center mt-5 p-4 bg-light rounded-3">
                <h4>Still have questions?</h4>
                <p class="text-muted">Can't find the answer you're looking for? Please contact our support team.</p>
                <a href="{{ route('website.contact') }}" class="btn btn-primary">
                    <i class="bi bi-envelope me-1"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('faqSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.faq-item');
        let found = false;

        items.forEach(item => {
            const question = item.querySelector('.accordion-button').textContent.toLowerCase();
            const answer = item.querySelector('.accordion-body').textContent.toLowerCase();

            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = '';
                found = true;
            } else {
                item.style.display = 'none';
            }
        });

        document.getElementById('noResults').style.display = found ? 'none' : 'block';
        if (found) {
            document.getElementById('faqAccordion').style.display = '';
        } else {
            document.getElementById('faqAccordion').style.display = 'none';
        }
    });

    // Category filter
    document.querySelectorAll('.category-filter').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const category = this.dataset.category;
            const items = document.querySelectorAll('.faq-item');

            if (category === 'all') {
                items.forEach(item => item.style.display = '');
                document.getElementById('noResults').style.display = 'none';
                document.getElementById('faqAccordion').style.display = '';
            } else {
                items.forEach(item => {
                    if (item.dataset.category === category) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });
    });

    function resetFilters() {
        document.getElementById('faqSearch').value = '';
        document.querySelectorAll('.faq-item').forEach(item => item.style.display = '');
        document.getElementById('noResults').style.display = 'none';
        document.getElementById('faqAccordion').style.display = '';
        document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
        document.querySelector('.category-filter[data-category="all"]').classList.add('active');
    }
</script>
@endpush
