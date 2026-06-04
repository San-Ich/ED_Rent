@extends('layouts.app')
@section('tittle', 'KudaBesiRent | Payment')
@section('content')
    <x-detail-payment :rental="$rental" :penalty="$penalty ?? 0" />
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert("Pembayaran Berhasil!");
                    window.location.href = "{{ route('customer.orders') }}";
                },
                onPending: function(result) {
                    alert("Menunggu penyelesaian pembayaran Anda.");
                    window.location.href = "{{ route('customer.orders') }}";
                },
                onError: function(result) {
                    alert("Terjadi kegagalan sistem pembayaran.");
                },
                onClose: function() {
                    alert('Anda menutup halaman pembayaran sebelum selesai.');
                }
            });
        });
    </script>
@endsection
