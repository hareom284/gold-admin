@extends('frontend::layouts.master')
@section('content')
<div class="section-spacing-bottom bg-white d-flex justify-content-center align-items-center flex-column p-5">
    {!! $qrCode !!}
</div>
<script>
    let interval = setInterval(() => {
        fetch("{{route('check.payment.status',['subscriptionTransaction'=>$orderId])}}")
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if(data.data.paymentTxnStatus == 200){
                    clearInterval(interval);
                    window.location.href = `{{route('subscription.success')}}?orderId=${data.data.orderId}&amount=${data.data.amount}&transactionId=${data.data.posTransactionId}&billNo=${data.data.billNo}&customerName=${data.data.customerName}&customerPhone=${data.data.customerPhone}`;
                }else if(data.data.paymentTxnStatus == 500){
                    clearInterval(interval);
                    window.location.href = `{{route('subscription.fail')}}?orderId=${data.data.orderId}&amount=${data.data.amount}&transactionId=${data.data.posTransactionId}&billNo=${data.data.billNo}&customerName=${data.data.customerName}&customerPhone=${data.data.customerPhone}`;
                }
            })
            .catch(error => console.error('Error:', error));
    }, 1000);
</script>
@endsection
