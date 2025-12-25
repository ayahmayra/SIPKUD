<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Kas USP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .header .info {
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table th {
            background-color: #4f81bd;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #2d5a8b;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .saldo-awal {
            font-weight: bold;
            background-color: #e8f4ff !important;
        }
        
        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUKU KAS USP</h1>
        @if($periode)
            <div class="info">Periode: {{ $periode }}</div>
        @endif
        @if($kecamatanNama)
            <div class="info">Kecamatan: {{ $kecamatanNama }}</div>
        @endif
        @if($desaNama)
            <div class="info">Desa: {{ $desaNama }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="10%">Tanggal</th>
                <th width="35%">Keterangan</th>
                <th width="15%" class="text-right">Debet</th>
                <th width="15%" class="text-right">Kredit</th>
                <th width="20%" class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr class="saldo-awal">
                <td></td>
                <td></td>
                <td>SALDO AWAL</td>
                <td></td>
                <td></td>
                <td class="text-right">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
            </tr>
            @php
                $no = 1;
                $saldoBerjalan = $saldoAwal;
            @endphp
            @foreach($transaksi as $item)
                @php
                    if ($item->jenis_transaksi === 'masuk') {
                        $saldoBerjalan += $item->jumlah;
                    } else {
                        $saldoBerjalan -= $item->jumlah;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item->tanggal_transaksi->format('d/m/Y') }}</td>
                    <td>{{ $item->uraian ?? '-' }}</td>
                    <td class="text-right">
                        @if($item->jenis_transaksi === 'masuk')
                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if($item->jenis_transaksi === 'keluar')
                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($saldoBerjalan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </div>
</body>
</html>

