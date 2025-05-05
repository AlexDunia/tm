@extends('errors.minimal')

@section('title', 'Authentication Required')
@section('code', '401')
@section('icon', 'fas fa-lock')
@section('message', 'You need to be logged in to access this page. Your session may have expired due to inactivity.')

@section('content')
<div class="steps">
    <h2 class="steps-title">Next Steps</h2>
    <div class="step">
        <div class="step-number">1</div>
        <div class="step-text">Click the login button below to sign back into your account</div>
    </div>
    <div class="step">
        <div class="step-number">2</div>
        <div class="step-text">After logging in, you'll be able to continue where you left off</div>
    </div>
    <div class="step">
        <div class="step-number">3</div>
        <div class="step-text">For security reasons, your session automatically expires after 15 minutes of inactivity</div>
    </div>
</div>

<div x-data="{ countdown: 5 }" x-init="setInterval(() => { if (countdown > 0) countdown--; if (countdown === 0) window.location.href = '/login'; }, 1000)">
    <p>Redirecting to login page in <span x-text="countdown" class="timer"></span> seconds</p>
</div>
@endsection

@section('buttons')
<a href="/login" class="action-button">Log In Now</a>
<a href="/" class="action-button secondary">Return to Home</a>
@endsection
