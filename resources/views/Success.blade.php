@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-container" data-reference="{{ $reference ?? '' }}">
    <!-- Notification Area -->

    <div class="success-wrapper">
        <div class="success-card">
            <div class="success-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h1>Payment Successful!</h1>

            <p class="success-message">
                Your payment has been successfully processed. You will receive an email confirmation with your transaction details.
            </p>

            <div class="success-actions">
                <a href="/" class="home-btn">
                    <i class="fa-solid fa-house"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Recommendations Section (Added) -->
    @if(isset($recommendations) && $recommendations->isNotEmpty())
    <div class="recommendations-section">
        <h2>Events You Might Like</h2>
        <div class="recommendations-grid">
            @foreach($recommendations as $event)
            <div class="recommendation-card">
                <div class="recommendation-image">
                    <img src="{{ $event->image }}" alt="{{ $event->name }}">
                </div>
                <div class="recommendation-content">
                    <h3>{{ $event->name }}</h3>
                    <p class="recommendation-location">
                        <i class="fa-solid fa-location-dot"></i> {{ $event->location }}
                    </p>
                    <a href="/listone/{{ $event->id }}" class="view-event-btn">View Event</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection

<style>
    .success-wrapper {
        width: 90%;
        max-width: 800px;
        margin: 40px auto;
    }

    .success-card {
        background: rgba(37, 36, 50, 0.8);
        color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        text-align: center;
        border: 1px solid rgba(192, 72, 136, 0.3);
    }

    .success-icon {
        font-size: 60px;
        color: #4CAF50;
        margin-bottom: 20px;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .success-card h1 {
        font-size: 32px;
        margin-bottom: 15px;
        color: #ffffff;
    }

    .success-message {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 30px;
        line-height: 1.5;
    }

    .success-actions {
        margin-top: 30px;
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

    /* Recommendations Section Styles */
    .recommendations-section {
        width: 90%;
        max-width: 1000px;
        margin: 60px auto;
        background: rgba(37, 36, 50, 0.8);
        border-radius: 12px;
        padding: 30px;
        border: 1px solid rgba(192, 72, 136, 0.3);
    }

    .recommendations-section h2 {
        font-size: 24px;
        color: #ffffff;
        margin-bottom: 20px;
        text-align: center;
    }

    .recommendations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .recommendation-card {
        background: rgba(50, 49, 66, 0.6);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(192, 72, 136, 0.2);
    }

    .recommendation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border-color: rgba(192, 72, 136, 0.5);
    }

    .recommendation-image {
        height: 120px;
        overflow: hidden;
    }

    .recommendation-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .recommendation-card:hover .recommendation-image img {
        transform: scale(1.1);
    }

    .recommendation-content {
        padding: 15px;
    }

    .recommendation-content h3 {
        font-size: 16px;
        color: #ffffff;
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .recommendation-location {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .view-event-btn {
        display: block;
        background: #C04888;
        color: white;
        text-align: center;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .view-event-btn:hover {
        background: #d658a0;
    }

    @media (max-width: 700px) {
        .success-wrapper {
            width: 95%;
            margin: 20px auto;
        }

        .success-card {
            padding: 25px 20px;
        }

        .success-icon {
            font-size: 50px;
        }

        .success-card h1 {
            font-size: 24px;
        }

        .recommendations-section {
            padding: 20px 15px;
        }

        .recommendations-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .recommendations-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
