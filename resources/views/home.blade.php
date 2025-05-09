@extends('layouts.app')

@section('content')
    <!-- Payment Success Modal -->
    <x-payment-success-modal :show="request()->has('payment_success')" />

    <!-- Rest of the home page content -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(request()->has('payment_success'))
        <script>
            // Dispatch event to show modal
            window.dispatchEvent(new CustomEvent('payment-success'));
        </script>
    @endif
@endsection
