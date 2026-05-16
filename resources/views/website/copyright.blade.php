@extends('layouts.public')

@section('title', 'Copyright - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h2 class="section-title">Copyright Policy</h2>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <p>&copy; {{ date('Y') }} Regional Sports Control Board. All rights reserved.</p>
                        <p>All content on this portal including text, images, logos, and software is the property of RSCB and is protected by applicable copyright laws. Unauthorized use, reproduction, or distribution is strictly prohibited.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
