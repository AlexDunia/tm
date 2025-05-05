@extends('errors.minimal')

@section('title', 'Server Error')
@section('code', '500')
@section('icon', 'fas fa-server')
@section('message', 'Something went wrong on our servers. Our technical team has been notified and is working to fix the issue.')

@section('content')
<div class="steps">
    <h2 class="steps-title">What You Can Do</h2>
    <div class="step">
        <div class="step-number">1</div>
        <div class="step-text">Try refreshing the page - sometimes that's all it takes</div>
    </div>
    <div class="step">
        <div class="step-number">2</div>
        <div class="step-text">Try again in a few minutes - the problem might be temporary</div>
    </div>
    <div class="step">
        <div class="step-number">3</div>
        <div class="step-text">If the problem persists, please contact our support team</div>
    </div>
</div>
@endsection

@section('buttons')
<a href="/" class="action-button">Return to Home</a>
<a href="javascript:location.reload()" class="action-button secondary">Refresh Page</a>
@endsection
