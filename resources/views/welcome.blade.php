@extends('layouts.app')

@section('content')
<div class="main-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div id="newslide" class="slideshow-container">
            @foreach($welcome->take(3)->reverse() as $index => $onewelcome)
            <div class="newslideshow fade">
                <div class="slide-image" style="background-image: url('{{ str_starts_with($onewelcome->heroimage, 'http') ? $onewelcome->heroimage : asset('storage/' . $onewelcome->heroimage) }}')">
                    <a href="/events/{{$onewelcome->name}}" class="slide-content">{{$onewelcome->herolink}}</a>
                </div>
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
        contents[i].style.display = "none";
    }
    slides[slideIndex - 1].style.opacity = 1;
    contents[slideIndex - 1].style.display = "block";
    setTimeout(showSlides, 5000);
}
</script>
@endsection

