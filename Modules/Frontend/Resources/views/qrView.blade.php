@extends('frontend::layouts.master')
@section('content')
@php

use Carbon\Carbon;

@endphp

<div class="payment-qr-container bg-light min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                {{-- Payment Header --}}
                <div class="text-center mb-5">
                    {{-- <h1 class="fw-bold mb-3 text-primary">{{ __('GoldChannel Myanmar Payment') }}</h1> --}}
                    <p class="mb-3 text-black">{{ __('frontend.scan_mmqr') }}</p>

                </div>

                {{-- QR Code Container --}}
                <div class="qr-card bg-white p-4 rounded-4 shadow-lg mb-4">
                    <div class="text-center mb-3">
                        <h4 class="fw-bold mb-3 text-black">{{ __('frontend.mobile_qr_code') }}</h4>
                        <div class="qr-wrapper  bg-white p-3 rounded-3 border">
                            {{-- {!! $qrCode !!} --}}
                            {!! str_replace('<img ', '<img class="w-full h-auto" ', $qrCode) !!}
                        </div>
                    </div>

                    {{-- Payment Apps Logos --}}
                    <div class="payment-providers d-flex justify-content-center gap-3 my-4">
                        <img src="{{ asset('default-image/wave.png') }}" alt="Wave Money" class="payment-logo" style="height: 40px">
                        <img src="{{ asset('default-image/k-pay.png') }}" alt="KBZ Pay" class="payment-logo" style="height: 40px">
                        <img src="{{ asset('default-image/cb.png') }}" alt="CB Pay" class="payment-logo" style="height: 40px">
                    </div>

                    {{-- Payment Instructions --}}
                    <div class="payment-steps mb-4">
                        <h5 class="fw-semibold mb-3">{{ __('How to Pay:') }}</h5>
                        <div class="list-group">
                            <div class="list-group-item border-0 py-2">
                                1. {{__('frontend.pay_step_1')}}
                            </div>
                            <div class="list-group-item border-0 py-2">
                                2. {{__('frontend.pay_step_2')}}
                            </div>
                            <div class="list-group-item border-0 py-2">
                                3. {{__('frontend.pay_step_3')}}
                            </div>
                            {{-- <div class="list-group-item border-0 py-2">
                                4. {{__('frontend.pay_step_4')}}
                            </div> --}}
                        </div>
                    </div>
                </div>

                {{-- Payment Details --}}
                <div class="payment-details-card bg-white p-4 rounded-4 shadow-sm">
                    <h5 class="fw-semibold mb-3 text-black">{{ __('Transaction Details') }}</h5>
                    <dl class="row mb-0 text-black">
                        <dt class="col-6">Plan</dt>
                        <dd class="col-6 text-end">GoldChannel {{$sub_transaction->plan->name}}</dd>

                        <dt class="col-6">{{ __('frontend.amount') }}</dt>
                        <dd class="col-6 text-end">MMK {{ number_format($sub_transaction->amount, 0) }}</dd>

                        <dt class="col-6">{{ __('frontend.ref_id') }}</dt>
                        <dd class="col-6 text-end">GC{{ $sub_transaction->order_id }}</dd>

                        <dt class="col-6">{{ __('frontend.valid') }}</dt>
                        <dd class="col-6 fs-6">{{ Carbon::now()->addDays($sub_transaction->plan->duration_value) }}</dd>

                    </dl>
                </div>

                {{-- Manual Payment Fallback --}}
                {{-- <div class="mt-4 text-center">
                    <p class="text-muted small mb-2">{{ __('Having trouble scanning?') }}</p>
                    <button class="btn btn-link text-primary" data-bs-toggle="modal" data-bs-target="#manualPaymentModal">
                        {{ __('View Manual Payment Options') }}
                    </button>
                </div> --}}
            </div>
        </div>
    </div>
</div>
<script>
    // Existing payment status check script remains the same
    let interval = setInterval(() => {
        fetch("{{route('check.payment.status',['subscriptionTransaction'=>$transaction])}}")
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
