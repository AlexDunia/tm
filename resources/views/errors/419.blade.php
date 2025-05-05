@extends('errors.minimal')

@section('title', 'Session Expired')
@section('code', '419')
@section('icon', 'fas fa-clock')
@section('message', 'Session expired. Please log in again.')

@section('content')
<script>
    // Redirect after 3 seconds
    setTimeout(function() {
        window.location.href = '/login';
    }, 3000);
</script>
<p>Redirecting to login...</p>
@endsection

@section('buttons')
<a href="/login" class="action-button">Log In Now</a>
<a href="/" class="action-button secondary">Return to Home</a>
@endsection
