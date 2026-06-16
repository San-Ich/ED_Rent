@extends('layouts.app')
@section('tittle', 'KudaBesiRent | Payment')
@section('content')
    <x-detail-payment :rental="$rental" :penalty="$penalty ?? 0" />
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const payButton = document.getElementById('pay-button');

        if (payButton) {
            payButton.addEventListener('click', function(event) {
                event.preventDefault();
                
                if (typeof window.snap === 'undefined') {
                    alert('Sistem pembayaran sedang memuat, mohon tunggu sebentar.');
                    return;
                }

                window.snap.pay('{{ $snapToken }}', {
                    
                    onSuccess: function(result) {
                        window.top.location.href = "{{ route('payment.success', $rental->id) }}";
                    },
                    
                    onPending: function(result) {
                    
                        if (result.transaction_status === 'expire' || result.transaction_status === 'cancel') {
                            window.top.location.href = "{{ route('payment.failed', $rental->id) }}";
                        } else {
                            alert("Instruksi pembayaran telah dibuat. Silakan selesaikan transaksi Anda.");
                            window.top.location.href = "{{ route('customer.orders') }}";
                        }
                    },
                    
                    onError: function(result) {
                        window.top.location.href = "{{ route('payment.failed', $rental->id) }}";
                    },
                    
                    onClose: function() {
                        window.top.location.href = "{{ route('payment.failed', $rental->id) }}";
                    }
                });
            });
        }
    });
</script>
@endsection
