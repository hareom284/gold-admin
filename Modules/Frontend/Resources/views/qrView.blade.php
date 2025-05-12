@extends('frontend::layouts.master')
@section('content')
<div class="section-spacing-bottom bg-white d-flex justify-content-center align-items-center flex-column p-5">
    {!! $qrCode !!}
</div>
<script>
    let interval = setInterval(() => {
        fetch("{{route('check.payment.status',['subscriptionTransaction'=>$transactionId,'plan'=>$planId])}}")
            .then(response => response.json())
            .then(data => {
                console.log(data);
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
@endsection
