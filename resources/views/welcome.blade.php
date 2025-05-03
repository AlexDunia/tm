@extends('layouts.app')

@section('content')
<div class="main-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div id="newslide" class="slideshow-container">
            @foreach($welcome->take(3)->reverse() as $index => $onewelcome)
            <div class="newslideshow fade">
                <div class="slide-image" style="background-image: url('{{ str_starts_with($onewelcome->heroimage, 'http') ? $onewelcome->heroimage : asset('storage/' . $onewelcome->heroimage) }}')">
                    <div class="image-overlay"></div>
                </div>
                <a href="/events/{{$onewelcome->name}}" class="slide-content">{{$onewelcome->name}}</a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="searchbox">
            <div class="selectandsearch">
                <form class="example" type="get" action="{{url('/search')}}">
                    <input type="text" placeholder="Search.." name="name" class="custom-input">
                    <i class="fas fa-search search-icon"></i>
                </form>
            </div>
        </div>
    </section>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Events Section -->
        <section class="events-section">
            <div class="circularbg">
                <div class="view">
                    <div class="latest">
                        <h1>Trending Events</h1>
                    </div>

                    <div class="t_grids">
                        @foreach($welcome as $onewelcome)
                        <div class="one_e">
                            <ul>
                                <li class="mypimage"><img src="{{ str_starts_with($onewelcome->image, 'http') ? $onewelcome->image : asset('storage/' . $onewelcome->image) }}"></li>
                                <li class="noe">{{$onewelcome->name}}</li>
                                <div class="toe"><i class="fa-solid fa-location-dot"></i> {{$onewelcome->location}}</div>
                                <div class="toe"><i class="fa-solid fa-calendar-days"></i> {{ date('n/j/Y', strtotime($onewelcome->date)) }}</div>
                                <div class="toe"><i class="fa-solid fa-ticket"></i> Starting @5000</div>
                                <button class="b_t"><a href="/events/{{$onewelcome->name}}">View Event</a></button>
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Pagination Section -->
        <section class="pagination-section">
            <div class="pagination-container">
                <div class="pagination-wrapper">
                    @include('_paginate')
                    {{ $welcome->links() }}
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
var slideIndex = 0;
showSlides();

function showSlides() {
    var slides = document.getElementsByClassName("newslideshow");
    var contents = document.getElementsByClassName("slide-content");
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    for (var i = 0; i < slides.length; i++) {
        slides[i].style.opacity = 0;
    }
    slides[slideIndex - 1].style.opacity = 1;

    // Make sure the slide-content is visible in the active slide only
    document.querySelectorAll('.slide-content').forEach(function(content) {
        content.style.opacity = "0";
    });

    if (contents.length > 0 && slideIndex <= contents.length) {
        contents[slideIndex - 1].style.opacity = "1";
    }

    setTimeout(showSlides, 5000);
}
</script>

<style>
/* Inline styles to ensure they take effect immediately */
.hero-section {
    position: relative;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

.slide-content {
    position: absolute;
    left: 8%;
    transform: none;
    color: #ffffff !important;
    text-align: left;
    font-size: 3rem;
    font-weight: 700;
    text-decoration: none;
    text-shadow: none !important;
    z-index: 100;
    width: 80%;
}

.search-section {
    z-index: 100;
    position: relative;
}

/* Enhanced pagination styles */
.pagination-container {
    display: flex;
    justify-content: center;
    margin: 40px auto;
    width: 100%;
}

.pagination-wrapper {
    width: 90%;
    max-width: 720px;
    background: rgba(37, 36, 50, 0.7);
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    justify-content: center;
    gap: 8px;
}

.pagination .page-item .page-link {
    background-color: #252432;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
    padding: 10px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
    font-weight: 500;
}

.pagination .page-item .page-link:hover {
    background-color: rgba(192, 72, 136, 0.3);
    border-color: rgba(192, 72, 136, 0.5);
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background-color: #c04888;
    border-color: #c04888;
    color: white;
}

.pagination .page-item.disabled .page-link {
    background-color: rgba(37, 36, 50, 0.5);
    color: rgba(255, 255, 255, 0.4);
    border-color: transparent;
    cursor: not-allowed;
}

/* Small screen responsiveness */
@media (max-width: 640px) {
    .pagination-wrapper {
        width: 95%;
        padding: 10px;
    }

    .pagination .page-item .page-link {
        padding: 8px 12px;
        font-size: 14px;
    }
}
</style>
@endsection

