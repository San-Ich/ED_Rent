<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $rental->kode_booking }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #18181b;
            background-color: #ffffff;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            max-width: 750px;
            margin: 40px auto;
            padding: 40px;
            border: 1px solid #e4e4e7;
            background: #ffffff;
        }

        /* Header Setup */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }

        .header-table td {
            vertical-align: top;
        }

        .brand-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            text-transform: uppercase;
            color: #000000;
            margin: 0;
        }

        .meta-text {
            text-align: right;
            font-size: 13px;
            color: #52525b;
            line-height: 1.6;
        }

        .meta-text strong {
            color: #000000;
        }

        .badge-mono {
            background-color: #000000;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-top: 5px;
        }

        
        .divider {
            border-top: 1px solid #18181b;
            margin: 20px 0;
        }

        /* Info Section (Penyewa & Penyedia) */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .info-table td {
            width: 50%;
            vertical-align: top;
            font-size: 14px;
            color: #52525b;
            line-height: 1.6;
        }

        .info-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000000;
            margin-bottom: 6px;
        }

        .info-value-dark {
            color: #000000;
            font-weight: 500;
        }

        /* Rincian Item Finansial & Logistik */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .item-table th {
            background: #f4f4f5;
            color: #000000;
            text-align: left;
            padding: 12px 14px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #18181b;
        }

        .item-table td {
            padding: 16px 14px;
            font-size: 14px;
            border-bottom: 1px solid #e4e4e7;
            vertical-align: middle;
        }

        .text-right {
            text-align: right !important;
        }

        .item-name {
            font-weight: 600;
            color: #000000;
        }

        .item-subtext {
            font-size: 12px;
            color: #71717a;
            margin-top: 4px;
        }

        /* Kalkulasi Total Pembayaran */
        .calculation-table {
            width: 380px;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .calculation-table td {
            padding: 8px 14px;
            font-size: 14px;
            color: #52525b;
        }

        .calculation-table tr.grand-total td {
            padding-top: 15px;
            border-top: 1px solid #e4e4e7;
            font-size: 18px;
            font-weight: 700;
            color: #000000;
        }

        /* Footer Nota */
        .invoice-footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #71717a;
            line-height: 1.6;
            border-top: 1px dashed #e4e4e7;
            padding-top: 25px;
        }
    </style>
</head>

<body>

    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="brand-title">KudaBesiRent</h1>
                    <div class="item-subtext">Premium Motorcycle Rental</div>
                </td>
                <td class="meta-text">
                    <strong>KODE BOOKING:</strong> {{ $rental->kode_booking }}<br>
                    <strong>Tanggal Cetak:</strong> {{ date('d M Y') }}<br>
                    <span class="badge-mono">{{ strtoupper($rental->status) }}</span>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="info-table">
            <tr>
                <td>
                    <div class="info-title">Diterbitkan Untuk:</div>
                    <div class="info-value-dark">{{ $rental->user->name }}</div>
                    <div>{{ $rental->user->email }}</div>
                    <div>{{ $rental->user->phone ?? '-' }}</div>
                </td>
                <td class="text-right">
                    <div class="info-title">Metode Distribusi & Jadwal:</div>
                    <div>Ambil: <span
                            class="info-value-dark">{{ \Carbon\Carbon::parse($rental->tanggal_mulai)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div>Rencana Kembali: <span
                            class="info-value-dark">{{ \Carbon\Carbon::parse($rental->tanggal_rencana_kembali)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div>Selesai: <span
                            class="info-value-dark">{{ \Carbon\Carbon::parse($rental->tanggal_pengembalian)->translatedFormat('d F Y') }}</span>
                    </div>
                    @if ($rental->alamat_pengantaran)
                        <div class="item-subtext" style="max-width: 250px; display: inline-block;">
                            <strong>Alamat Kirim:</strong> {{ $rental->alamat_pengantaran }}
                        </div>
                    @else
                        <div class="item-subtext"><strong>Sistem:</strong> Ambil Sendiri di Garasi</div>
                    @endif
                </td>
            </tr>
        </table>

        @php
            $tglMulai = \Carbon\Carbon::parse($rental->tanggal_mulai);
            $tglKembali = \Carbon\Carbon::parse($rental->tanggal_rencana_kembali);
            $tglSelesai = \Carbon\Carbon::parse($rental->tanggal_pengembalian);
            $totalHari = ceil($tglMulai->diffInHours($tglKembali) / 24) ?: 1;

            $hargaDasarMotor = $rental->motor->harga_per_hari ?? 0;
            $subtotalMotor = $hargaDasarMotor * $totalHari;

            $biayaAntar = $rental->alamat_pengantaran ? 75000 : 0;
        @endphp

        <table class="item-table">
            <thead>
                <tr>
                    <th>Deskripsi Sewa</th>
                    <th class="text-right">Durasi</th>
                    <th class="text-right">Harga Harian</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-name">{{ $rental->motor->model }}</div>
                        <div class="item-subtext">Kendaraan Utama Utama</div>
                    </td>
                    <td class="text-right">{{ $totalHari }} Hari</td>
                    <td class="text-right">Rp {{ number_format($hargaDasarMotor, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($subtotalMotor, 0, ',', '.') }}</td>
                </tr>

                @if ($rental->perlengkapan && $rental->perlengkapan->count() > 0)
                    @foreach ($rental->perlengkapan as $item)
                        @php
                            $hargaItem = $item->harga ?? ($item->pivot->harga_per_hari ?? ($item->data_harga ?? 0));
                            if (!$hargaItem && isset($item->nama_perlengkapan)) {
                                $hargaItem = $item->harga_per_hari ?? 0;
                            }
                            $subtotalItem = $hargaItem * $totalHari;
                        @endphp
                        <tr>
                            <td>
                                <div class="item-name"><i class="bi bi-plus-circle-fill" style="font-size: 10px;"></i> +
                                    {{ $item->nama_perlengkapan }}</div>
                                <div class="item-subtext">Perlengkapan Tambahan Ekstra</div>
                            </td>
                            <td class="text-right">{{ $totalHari }} Hari</td>
                            <td class="text-right">Rp {{ number_format($hargaItem, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($subtotalItem, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif

                @if ($biayaAntar > 0)
                    <tr>
                        <td>
                            <div class="item-name">Biaya Antar & Jemput Unit</div>
                            <div class="item-subtext">Pengantaran Langsung ke Alamat Tujuan</div>
                        </td>
                        <td class="text-right">-</td>
                        <td class="text-right"></td>
                        <td class="text-right">Rp {{ number_format($biayaAntar, 0, ',', '.') }}</td>
                    </tr>
                @endif

                @if ($rental->penalty > 0)
                    <tr style="background-color: #fff5f5;">
                        <td>
                            <div class="item-name text-danger" style="color: #dc3545; font-weight: bold;">Biaya Keterlambatan Pengembalian (Denda 1Hari/1Jam + Rp. 50000)</div>
                            <div class="item-subtext">Dikalkulasi otomatis sistem pasca-pengembalian</div>
                        </td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                        <td class="text-right text-danger" style="color: #dc3545; font-weight: bold;">Rp {{ number_format($rental->penalty, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table class="calculation-table">
            <tr>
                <td>Sewa Dasar Unit</td>
                <td class="text-right">Rp {{ number_format($subtotalMotor, 0, ',', '.') }}</td>
            </tr>
            @if ($rental->perlengkapan && $rental->perlengkapan->count() > 0)
                @php
                    $totalAddon =
                        $rental->perlengkapan->sum(function ($i) {
                            return $i->harga_per_hari ?? ($i->harga ?? 0);
                        }) * $totalHari;
                @endphp
                <tr>
                    <td>Total Opsional Perlengkapan</td>
                    <td class="text-right">Rp {{ number_format($totalAddon, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($biayaAntar > 0)
                <tr>
                    <td>Tarif Layanan Pengantaran</td>
                    <td class="text-right">Rp {{ number_format($biayaAntar, 0, ',', '.') }}</td>
                </tr>
            @endif

        
            @if ($rental->penalty > 0)
                <tr>
                    <td style="color: #dc3545; font-weight: 500;">Akumulasi Total Penalty (Denda)</td>
                    <td class="text-right text-danger" style="color: #dc3545; font-weight: 500;">+ Rp {{ number_format($rental->penalty, 0, ',', '.') }}</td>
                </tr>
            @endif

            <tr class="grand-total">
                <td>Total Dibayarkan</td>
                <td class="text-right">Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="invoice-footer">
            Terima kasih telah mempercayakan perjalanan Anda bersama <strong>KudaBesiRent</strong>.<br>
            Salinan nota digital ini diterbitkan secara sah dan otomatis. Harap tunjukkan dokumen berkode QR / Booking
            ini<br>
            pada saat serah terima fisik kendaraan di lokasi operasional kami.
        </div>
    </div>

</body>

</html>
