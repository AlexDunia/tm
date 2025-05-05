@extends('errors.minimal')

@section('title', 'Page Not Found')
@section('code', '404')
@section('icon', 'fas fa-map-signs')
@section('message', 'The page you were looking for could not be found. It might have been removed, renamed, or may never have existed.')

@section('content')
<div class="steps">
    <h2 class="steps-title">What You Can Do</h2>
    <div class="step">
        <div class="step-number">1</div>
        <div class="step-text">Check the URL for spelling errors or typos</div>
    </div>
    <div class="step">
        <div class="step-number">2</div>
        <div class="step-text">Go back to the previous page and try a different link</div>
    </div>
    <div class="step">
        <div class="step-number">3</div>
        <div class="step-text">Use the navigation menu or search bar to find what you're looking for</div>
    </div>
</div>
@endsection

@section('buttons')
<a href="/" class="action-button">Go to Home</a>
<a href="javascript:history.back()" class="action-button secondary">Go Back</a>
@endsection
