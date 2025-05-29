@extends('frontend::layouts.master')
@section('content')
<div class="container py-5">
    <div class="card shadow rounded-4">
        <div class="card-body text-center">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h2 class="mt-3">Payment Successful</h2>
                <p class="text-muted">Thank you for your payment. Your transaction was processed successfully.</p>
            </div>

            <div class="row justify-content-center text-start">
                <div class="col-md-6">
                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Order ID</strong> <span>{{ $orderId }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Amount</strong> <span>{{ number_format($amount, 2) }} Ks</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Transaction ID</strong> <span>{{ $transactionId }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>PaymentTxnID</strong> <span>{{ $paymentTxnID }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Bill No</strong> <span>{{ $billNo }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Customer Name</strong> <span>{{ $customerName }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Customer Phone</strong> <span>{{ $customerPhone }}</span>
                        </li>
                    </ul>

                    <div class="text-center">
                        <a href="{{ route('home') }}" class="btn btn-primary px-4">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

