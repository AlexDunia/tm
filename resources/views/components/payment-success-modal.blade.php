@props(['show' => false])

<div x-data="{ show: @js($show) }" 
     x-show="show" 
     x-on:payment-success.window="show = true"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Success icon -->
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Payment Successful!
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Thank you for your purchase. Your transaction was successful.
                            </p>
                            @if(session('success_data'))
                                <div class="mt-4 space-y-2">
                                    <p class="text-sm font-medium text-gray-700">
                                        Reference: {{ session('success_data.reference') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Amount: ₦{{ number_format(session('success_data.amount'), 2) }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Event: {{ session('success_data.ticket_data.event') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Quantity: {{ session('success_data.ticket_data.quantity') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        x-on:click="show = false"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Continue Shopping
                </button>
                <a href="{{ route('orders') }}" 
                   class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    View Orders
                </a>
            </div>
        </div>
    </div>
</div> 