@extends('layouts.app')
@section('tittle', 'KudaBesiRent | Payment')
@section('content')
    <x-detail-payment :rental="$rental" :penalty="$penalty ?? 0" />
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');

        function openMidtransSnap() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {

                    window.location.href = "{{ route('payment.success', $rental->id) }}";
                },
                onPending: function(result) {
                    alert("Instruksi pembayaran telah dibuat. Silakan selesaikan pembayaran Anda.");
                    window.location.reload();
                },
                onError: function(result) {

                    window.location.href = "{{ route('payment.failed', $rental->id) }}";
                },
                onClose: function() {
                    console.log('User menutup halaman pembayaran sebelum selesai.');
                }
            });
        }


        document.addEventListener("DOMContentLoaded", function() {

        });

        if (payButton) {
            payButton.addEventListener('click', function() {
                openMidtransSnap();
            });
        }
    </script>
@endsection
