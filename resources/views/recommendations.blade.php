@extends('layouts.app')

@section('content')
<div class="recommendation-container">
    <div class="recommendation-header">
        <h1>Recommended Events For You</h1>
        <p class="recommendation-subtitle">
            @if($source == 'user_history')
                Based on your previous ticket purchases
            @elseif($source == 'guest_history')
                Based on your recent activity
            @else
                Popular events you might like
            @endif
        </p>
    </div>

    @if($recommendations->isEmpty())
        <div class="no-recommendations">
            <div class="no-data-icon">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <h2>No Recommendations Available</h2>
            <p>We don't have any recommendations for you at the moment. Check back later or explore our events.</p>
            <a href="/" class="home-btn">
                <i class="fa-solid fa-house"></i> Browse Events
            </a>
        </div>
    @else
        <div class="recommendation-grid">
            @foreach($recommendations as $event)
                <div class="event-card" data-event-id="{{ $event->id }}">
                    <div class="event-card-image">
                        <img src="{{ $event->image }}" alt="{{ $event->name }}">
                        <div class="event-date">
                            @php
                                $date = $event->date;
                                $dateObj = null;

                                // Try to parse the date
                                if (strpos($date, '@') !== false) {
                                    try {
                                        $dateObj = \Carbon\Carbon::parse(str_replace('@', ' ', $date));
                                    } catch (\Exception $e) {
                                        // Date parsing failed
                                    }
                                } else {
                                    try {
                                        $dateObj = \Carbon\Carbon::parse($date);
                                    } catch (\Exception $e) {
                                        // Date parsing failed
                                    }
                                }
                            @endphp

                            @if($dateObj)
                                <span class="month">{{ $dateObj->format('M') }}</span>
                                <span class="day">{{ $dateObj->format('d') }}</span>
                            @else
                                <span class="unparsed-date">{{ $date }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="event-card-content">
                        <h3 class="event-card-title">{{ $event->name }}</h3>
                        <p class="event-card-location">
                            <i class="fa-solid fa-location-dot"></i> {{ $event->location }}
                        </p>
                        <div class="event-card-category">
                            <span class="category-tag">{{ $event->category }}</span>
                        </div>
                        <a href="/listone/{{ $event->id }}" class="event-card-button">View Event</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

<style>
    .recommendation-container {
        width: 90%;
        max-width: 1200px;
        margin: 40px auto;
    }

    .recommendation-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .recommendation-header h1 {
        font-size: 32px;
        color: #ffffff;
        margin-bottom: 10px;
    }

    .recommendation-subtitle {
        color: rgba(255, 255, 255, 0.7);
        font-size: 16px;
    }

    .recommendation-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .event-card {
        background: rgba(37, 36, 50, 0.8);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(192, 72, 136, 0.3);
        height: 100%;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(192, 72, 136, 0.7);
    }

    .event-card-image {
        position: relative;
        height: 180px;
        overflow: hidden;
    }

    .event-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .event-card:hover .event-card-image img {
        transform: scale(1.1);
    }

    .event-date {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(192, 72, 136, 0.9);
        color: white;
        border-radius: 8px;
        padding: 5px 10px;
        text-align: center;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        min-width: 50px;
    }

    .event-date .month {
        font-size: 12px;
        text-transform: uppercase;
    }

    .event-date .day {
        font-size: 18px;
    }

    .event-date .unparsed-date {
        font-size: 14px;
        padding: 5px;
    }

    .event-card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .event-card-title {
        font-size: 18px;
        color: #ffffff;
        margin-bottom: 10px;
        line-height: 1.3;
        flex-grow: 0;
    }

    .event-card-location {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 15px;
        flex-grow: 0;
    }

    .event-card-category {
        margin-top: auto;
        margin-bottom: 15px;
    }

    .category-tag {
        background: rgba(192, 72, 136, 0.2);
        color: #C04888;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .event-card-button {
        background: #C04888;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        display: block;
        text-decoration: none;
    }

    .event-card-button:hover {
        background: #d254a1;
        transform: translateY(-2px);
    }

    .no-recommendations {
        text-align: center;
        padding: 50px 20px;
        background: rgba(37, 36, 50, 0.8);
        border-radius: 12px;
        border: 1px solid rgba(192, 72, 136, 0.3);
    }

    .no-data-icon {
        font-size: 60px;
        color: rgba(192, 72, 136, 0.5);
        margin-bottom: 20px;
    }

    .no-recommendations h2 {
        font-size: 24px;
        color: #ffffff;
        margin-bottom: 10px;
    }

    .no-recommendations p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 25px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .home-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 25px;
        background: #C04888;
        color: white !important;
        border-radius: 8px;
        font-weight: bold;
        text-decoration: none;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .home-btn:hover {
        background: #d658a0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(192, 72, 136, 0.3);
    }

    @media (max-width: 768px) {
        .recommendation-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .recommendation-header h1 {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .recommendation-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
