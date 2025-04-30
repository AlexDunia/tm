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
                <a href="/events/{{$onewelcome->name}}" class="slide-content">{{$onewelcome->herolink}}</a>
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
            <div class="pag">
                @include('_paginate')
                {{ $welcome->links() }}
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
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #ffffff !important;
    text-align: center;
    font-size: 4rem;
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
</style>
@endsection

