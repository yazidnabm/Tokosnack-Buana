<!DOCTYPE html>
<html>
<head>
    <title>Laporan Arus Kas</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2, h3 {
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>Laporan Arus Kas</h2>

    <p>
        <strong>Periode :</strong>
        {{ $tanggalMulai }}
        s/d
        {{ $tanggalSelesai }}
    </p>

    <h3>Ringkasan</h3>

    <table>
        <tr>
            <th>Total Kas Masuk</th>
            <th>Total Kas Keluar</th>
            <th>Saldo</th>
        </tr>
        <tr>
            <td class="text-right">
                Rp {{ number_format($totalMasuk, 0, ',', '.') }}
            </td>
            <td class="text-right">
                Rp {{ number_format($totalKeluar, 0, ',', '.') }}
            </td>
            <td class="text-right">
                Rp {{ number_format($saldo, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <h3>Rekap Kas Masuk</h3>

    <table>
        <thead>
            <tr>
                <th>Sumber Pemasukan</th>
                <th>Total Qty</th>
                <th>Total Pemasukan</th>
            </tr>
        </thead>

        <tbody>
            @forelse($kasMasuk as $item)
                <tr>
                    <td>{{ $item->sumber }}</td>

                    <td class="text-center">
                        {{ $item->total_qty }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($item->total_jumlah, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">
                        Tidak ada data kas masuk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Rekap Kas Keluar</h3>

    <table>
        <thead>
            <tr>
                <th>Tujuan Pengeluaran</th>
                <th>Total Pengeluaran</th>
            </tr>
        </thead>

        <tbody>
            @forelse($kasKeluar as $item)
                <tr>
                    <td>{{ $item->tujuan }}</td>

                    <td class="text-right">
                        Rp {{ number_format($item->total_jumlah, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">
                        Tidak ada data kas keluar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>