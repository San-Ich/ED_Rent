
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Struk Pembayaran</title>
    <style>
        body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
        line-height: 1.6;
    }

    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        border-collapse: collapse;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
        font-weight: bold;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
        color: #28a745;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title" style="color: #1e3a8a;">KudaBesiRent</td>
                        <td>
                            Kode Booking: {{ $rental->kode_booking }}<br>
                            Tanggal Cetak: {{ date('d M Y') }}<br>
                            Status: <span class="badge-success">LUNAS / DI SEWA</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <strong>Penyewa:</strong><br>
                            {{ $rental->user->name }}<br>
                            {{ $rental->user->email }}
                        </td>
                        <td>
                            <strong>Penyedia Layanan:</strong><br>
                            KudaBesiRent Admin<br>
                            Garasi Pusat Kota, Indonesia
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Detail Unit Motor</td>
            <td>Harga / Hari</td>
        </tr>

        <tr class="item">
            <td>
                {{ $rental->motor->model }}
                ({{ \Carbon\Carbon::parse($rental->tanggal_rencana_kembali)->diffInDays(\Carbon\Carbon::parse($rental->tanggal_mulai)) }}
                Hari)
            </td>
            <td>Rp {{ number_format($rental->motor->harga_per_hari, 0, ',', '.') }}</td>
        </tr>

        <tr class="total">
            <td></td>
            <td>Total Bayar: Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>
    <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #777;">
        Terima kasih telah mempercayakan perjalanan Anda bersama KudaBesiRent.<br>
        Harap tunjukkan struk digital ini saat pengambilan unit motor di garasi kami.
    </div>
</div>
</body>
</html>
