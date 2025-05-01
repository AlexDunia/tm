@extends('layouts.app')

@section('content')
<div class="container search-results-container my-5">
    <h1 class="search-title mb-4">Results for <span class="search-term">"{{$si}}"</span></h1>

    <div class="search-results-grid">
        @foreach($products as $onewelcome)
        <div class="event-card">
            <div class="event-image">
                @if(!empty($onewelcome->image))
                    @php
                        $imagePath = $onewelcome->image;
                        // Debug information
                        $fullPath = asset('storage/' . $imagePath);
                    @endphp
                    <img src="{{$fullPath}}" alt="{{$onewelcome['name']}}" class="card-image"
                         onerror="this.onerror=null; this.src='/images/default-event.jpg'; console.log('Failed to load: ' + this.src);">
                    <!-- Add fallback image path -->
                @else
                    <img src="/images/default-event.jpg" alt="{{$onewelcome['name']}}" class="card-image">
                @endif
            </div>
            <div class="event-title">
                <h3>{{$onewelcome['name']}}</h3>
            </div>
            <div class="event-details">
                <div class="detail-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Genesis Event Center, Lagos, Nigeria.</span>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>{{$onewelcome['date']}}</span>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Starting @5000</span>
                </div>
            </div>
            <div class="event-action">
                <a href="/events/{{$onewelcome['name']}}" class="btn-view-event">View Event</a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="pagination-container mt-4">
        {{ $products->links() }}
    </div>
    @endif

    @if(count($products) === 0)
    <div class="no-results">
        <p>No events found matching your search. Try different keywords.</p>
    </div>
    @endif
</div>

<style>
:root {
    --primary: #C04888;
    --primary-hover: #a73672;
    --bg-dark: #13121a;
    --bg-card: rgba(28, 27, 36, 0.7);
    --text-light: #ffffff;
    --text-muted: #a0aec0;
    --border-color: rgba(255, 255, 255, 0.1);
}

.search-results-container {
    max-width: 1200px;
    padding: 0 20px;
    margin: 0 auto;
}

.search-title {
    font-weight: 600;
    color: var(--text-light);
    font-size: 2rem;
    margin-bottom: 2rem;
    text-align: left;
}

.search-term {
    color: var(--primary);
    font-weight: 700;
}

.search-results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 25px;
}

.event-card {
    display: flex;
    flex-direction: column;
    border-radius: 12px;
    overflow: hidden;
    background: var(--bg-card);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    border: 1px solid var(--border-color);
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
}

.event-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.event-card:hover .card-image {
    transform: scale(1.05);
}

.event-title {
    background-color: var(--primary);
    padding: 15px;
    text-align: center;
}

.event-title h3 {
    color: var(--text-light);
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    line-height: 1.3;
}

.event-details {
    padding: 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.detail-item i {
    color: var(--primary);
    font-size: 16px;
    min-width: 16px;
    margin-top: 4px;
}

.detail-item span {
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.4;
}

.event-action {
    padding: 0 15px 15px;
}

.btn-view-event {
    display: block;
    width: 100%;
    text-align: center;
    padding: 12px;
    background: var(--primary);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease, transform 0.3s ease;
}

.btn-view-event:hover {
    background: var(--primary-hover);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.no-results {
    text-align: center;
    padding: 3rem;
    color: var(--text-muted);
    background: var(--bg-card);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

/* Pagination Styles */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 8px;
}

.page-item .page-link {
    background-color: var(--bg-card);
    border: 1px solid var(--border-color);
    color: var(--text-light);
    padding: 10px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.page-item .page-link:hover {
    background-color: rgba(192, 72, 136, 0.2);
    border-color: var(--primary);
}

.page-item.active .page-link {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
}

.page-item.disabled .page-link {
    background-color: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    border-color: transparent;
    cursor: not-allowed;
}

@media (max-width: 991px) {
    .search-results-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }
}

@media (max-width: 767px) {
    .search-title {
        font-size: 1.5rem;
    }

    .search-results-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .event-image {
        height: 180px;
    }

    .event-title h3 {
        font-size: 1.1rem;
    }
}

@media (max-width: 480px) {
    .search-results-grid {
        grid-template-columns: 1fr;
    }

    .event-image {
        height: 200px;
    }
}
</style>
@endsection
