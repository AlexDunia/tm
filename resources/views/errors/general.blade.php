@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card error-card mt-5">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">Oops! Something went wrong</h3>
                </div>
                <div class="card-body">
                    <div class="error-container text-center py-4">
                        <div class="error-icon mb-4">
                            <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
                        </div>

                        <h4 class="error-message mb-4">{{ $message ?? 'We encountered an error processing your request.' }}</h4>

                        @if(isset($error_details) && $error_details)
                            <div class="error-details mb-4 p-3 text-left bg-light">
                                <small class="text-muted">Error details:</small>
                                <pre class="mb-0 mt-2">{{ $error_details }}</pre>
                            </div>
                        @endif

                        <div class="error-actions mt-4">
                            <a href="{{ route('home') }}" class="btn btn-primary mr-3">
                                <i class="fas fa-home mr-1"></i> Return Home
                            </a>
                            <button onclick="window.history.back()" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Go Back
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .error-card {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 8px;
        overflow: hidden;
    }

    .error-icon {
        color: #dc3545;
    }

    .error-details {
        border-radius: 6px;
        font-family: monospace;
        overflow-x: auto;
    }

    .error-actions .btn {
        border-radius: 30px;
        padding: 8px 24px;
        font-weight: 500;
    }
</style>
@endsection
