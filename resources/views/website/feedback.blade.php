@extends('layouts.public')

@section('title', 'Feedback - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h2 class="section-title">Feedback</h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('website.feedback.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="general">General Feedback</option>
                                    <option value="suggestion">Suggestion</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="appreciation">Appreciation</option>
                                    <option value="technical">Technical Issue</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rating *</label>
                                <div class="star-rating">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                        <label for="star{{ $i }}"><i class="bi bi-star-fill"></i></label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Your Feedback *</label>
                                <textarea name="feedback" class="form-control" rows="5" required placeholder="Tell us what you think..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>Submit Feedback
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        cursor: pointer;
        font-size: 24px;
        color: #ddd;
        padding: 0 2px;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #ffc107;
    }
</style>
@endpush
