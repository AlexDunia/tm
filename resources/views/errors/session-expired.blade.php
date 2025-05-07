@extends('errors.minimal')

@section('title', 'Session Expired')
@section('code', '419')
@section('icon', 'fas fa-clock')
@section('message', 'Your session has expired due to inactivity.')

@section('content')
<div class="steps">
    <h2 class="steps-title">What Happened?</h2>
    <div class="step">
        <div class="step-number">1</div>
        <div class="step-text">Your session timed out after a period of inactivity</div>
    </div>
    <div class="step">
        <div class="step-number">2</div>
        <div class="step-text">For security reasons, we automatically log you out when you're inactive</div>
    </div>
</div>
@endsection

@section('buttons')
<a href="/login" class="action-button">Log In Again</a>
<a href="/" class="action-button secondary">Return to Home</a>
@endsection
