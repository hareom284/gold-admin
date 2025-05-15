@extends('frontend::layouts.master')
@section('content')
<div class="payment-qr-container bg-light min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                {{-- Payment Header --}}
                <div class="text-center mb-5">
                    {{-- <h1 class="fw-bold mb-3 text-primary">{{ __('GoldChannel Myanmar Payment') }}</h1> --}}
                    <p class="mb-3 text-black">{{ __('Scan QR Code to Complete Subscription') }}</p>

                </div>

                {{-- QR Code Container --}}
                <div class="qr-card bg-white p-4 rounded-4 shadow-lg mb-4">
                    <div class="text-center mb-3">
                        <h4 class="fw-semibold mb-3">{{ __('Mobile Payment QR Code') }}</h4>
                        <div class="qr-wrapper bg-white p-3 rounded-3 border">
                            {!! $qrCode !!}
                        </div>
                    </div>

                    {{-- Payment Apps Logos --}}
                    <div class="payment-providers d-flex justify-content-center gap-3 my-4">
                        <img src="{{ asset('images/wave-money-logo.png') }}" alt="Wave Money" class="payment-logo" style="height: 40px">
                        <img src="{{ asset('images/kbz-pay-logo.png') }}" alt="KBZ Pay" class="payment-logo" style="height: 40px">
                        <img src="{{ asset('images/cb-pay-logo.png') }}" alt="CB Pay" class="payment-logo" style="height: 40px">
                    </div>

                    {{-- Payment Instructions --}}
                    <div class="payment-steps mb-4">
                        <h5 class="fw-semibold mb-3">{{ __('How to Pay:') }}</h5>
                        <div class="list-group">
                            <div class="list-group-item border-0 py-2">
                                1. {{ __('Open your mobile banking/payment app') }}
                            </div>
                            <div class="list-group-item border-0 py-2">
                                2. {{ __('Tap "Scan QR Code" in the app') }}
                            </div>
                            <div class="list-group-item border-0 py-2">
                                3. {{ __('Align QR code within scanner frame') }}
                            </div>
                            <div class="list-group-item border-0 py-2">
                                4. {{ __('Confirm payment details and authenticate') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Details --}}
                <div class="payment-details-card bg-white p-4 rounded-4 shadow-sm">
                    <h5 class="fw-semibold mb-3">{{ __('Transaction Details') }}</h5>
                    <dl class="row mb-0">
                        <dt class="col-6">{{ __('Subscription Plan:') }}</dt>
                        <dd class="col-6 text-end">GoldChannel Premium</dd>
                        
                        <dt class="col-6">{{ __('Amount:') }}</dt>
                        <dd class="col-6 text-end">MMK {{ number_format(5000, 0) }}</dd>
                        
                        <dt class="col-6">{{ __('Reference ID:') }}</dt>
                        <dd class="col-6 text-end">GC{{ $transactionId }}</dd>
                        
                        <dt class="col-6">{{ __('Valid Until:') }}</dt>
                        <dd class="col-6 text-end">10 Oct 2026</dd>
                    </dl>
                </div>

                {{-- Manual Payment Fallback --}}
                <div class="mt-4 text-center">
                    <p class="text-muted small mb-2">{{ __('Having trouble scanning?') }}</p>
                    <button class="btn btn-link text-primary" data-bs-toggle="modal" data-bs-target="#manualPaymentModal">
                        {{ __('View Manual Payment Options') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Manual Payment Modal --}}
<div class="modal fade" id="manualPaymentModal" tabindex="-1" aria-labelledby="manualPaymentModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Manual Payment Instructions') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h6 class="fw-semibold">Wave Money</h6>
                    <p class="mb-1">09XXX XXX XXX (GoldChannel Myanmar)</p>
                    <p class="text-muted small">Reference: GC{{ $transactionId }}</p>
                </div>
                <div class="mb-4">
                    <h6 class="fw-semibold">KBZ Pay</h6>
                    <p class="mb-1">09XXX XXX XXX (GoldChannel Services)</p>
                    <p class="text-muted small">Reference: GC{{ $transactionId }}</p>
                </div>
                <div class="mb-4">
                    <h6 class="fw-semibold">CB Bank</h6>
                    <p class="mb-1">Account: 123 456 789</p>
                    <p class="text-muted small">Branch: Yangon Main Branch</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Existing payment status check script remains the same
    let interval = setInterval(() => {
        fetch("{{route('check.payment.status',['subscriptionTransaction'=>$transactionId])}}")
            .then(response => response.json())
            .then(data => {
                if ([200, 500].includes(data.data.paymentTxnStatus)) {
                    clearInterval(interval);
                    const baseUrl = data.data.paymentTxnStatus === 200
                        ? `{{ route('subscription.success') }}`
                        : `{{ route('subscription.fail') }}`;

                    const queryParams = new URLSearchParams({
                        orderId: data.data.orderId,
                        amount: data.data.amount,
                        transactionId: data.data.posTransactionId,
                        billNo: data.data.billNo,
                        customerName: data.data.customerName,
                        customerPhone: data.data.customerPhone,
                        paymentTxnID: data.data.paymentTxnID,
                    });

                    window.location.href = `${baseUrl}?${queryParams.toString()}`;
                }
            })
            .catch(error => console.error('Error:', error));
    }, 1000);
</script>

<style>
    .payment-qr-container {
        padding: 2rem 0;
    }
    
    .qr-card {
        border: 1px solid #e0e0e0;
    }
    
    .payment-logo {
        filter: grayscale(100%);
        opacity: 0.7;
        transition: all 0.3s ease;
    }
    
    .payment-logo:hover {
        filter: grayscale(0);
        opacity: 1;
    }
    
    .qr-wrapper {
        max-width: 280px;
        margin: 0 auto;
    }
    
    @media (max-width: 768px) {
        .payment-details-card dt, 
        .payment-details-card dd {
            font-size: 0.9rem;
        }
        
        .qr-wrapper {
            max-width: 220px;
        }
        
        .payment-logo {
            height: 35px !important;
        }
    }
</style>
@endsection