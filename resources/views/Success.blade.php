@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="content-wrapper">
        <div class="tyaround">
            <div class="tybg">
                <h1>Your payment has been approved</h1>
                <br/>
                <p class="pty">You will get an email of your transaction information after which you will be sent your ticket of entry.</p>

                @if(isset($ticketIds) && count($ticketIds) > 0)
                    <div class="ticket-section">
                        <h2>Your Tickets for {{ $eventName }}</h2>
                        <p class="pty">Here are your unique ticket IDs. Please save them or take a screenshot - you'll need these to enter the event.</p>

                        <div class="ticket-list">
                            @foreach($ticketIds as $id)
                                <div class="ticket-item">
                                    <div class="ticket-id">{{ $id }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <a href="/" class="home-btn">Back to home</a>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .tyaround{
        width:80%;
        margin:auto;
        margin-top:40px;
    }

    .tybg{
        background: rgba(37, 36, 50, .8);
        color: #a0aec0;
        padding-bottom:150px;
        padding-top:40px;
        padding-left:50px;
    }

    .tybg p {
        font-size:15px;
        color: #a0aec0;
        margin-bottom:15px;
    }

    .tybg h1{
        font-size:30px;
    }

    .tybg h2{
        font-size:24px;
        margin-top:40px;
        margin-bottom:15px;
        color: #C04888;
    }

    .tybg a{
        color: #C04888;
        font-size:15px;
        margin-top:5px;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .tybg a:hover {
        color: #aa3a72;
    }

    .pty{
        color: #a0aec0;
    }

    .ticket-section {
        margin: 30px 0;
        padding: 20px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .ticket-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 20px 0;
    }

    .ticket-item {
        background: rgba(192, 72, 136, 0.2);
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid rgba(192, 72, 136, 0.3);
        transition: all 0.2s ease;
    }

    .ticket-item:hover {
        background: rgba(192, 72, 136, 0.3);
        transform: translateY(-2px);
    }

    .ticket-id {
        font-family: monospace;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }

    .home-btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: rgba(192, 72, 136, 0.8);
        color: white !important;
        border-radius: 6px;
        font-weight: bold;
    }

    .home-btn:hover {
        background: rgba(192, 72, 136, 1);
    }

    @media (max-width: 700px) {
        .tybg{
            background: rgba(37, 36, 50, .8);
            color: #a0aec0;
            padding-bottom:50px;
            padding-top:40px;
            padding-left:30px;
        }

        .tybg p {
            font-size:14px;
            color: #a0aec0;
            margin-bottom:10px;
        }

        .tyaround{
            width:85%;
        }

        .tybg h1{
            font-size:23px;
        }

        .tybg h2{
            font-size:20px;
        }

        .ticket-list {
            flex-direction: column;
        }
    }
</style>
