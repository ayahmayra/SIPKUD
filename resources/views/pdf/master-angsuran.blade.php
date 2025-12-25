<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Angsuran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
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
            font-size: 9px;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9px;
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
        
        .total-row {
            font-weight: bold;
            background-color: #e8f4ff !important;
        }
        
        .footer {
            margin-top: 20px;
            font-size: 8px;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA MASTER ANGSURAN PINJAMAN USP/UED-SP</h1>
        @if($kecamatanNama)
            <div class="info">Kecamatan: {{ $kecamatanNama }}</div>
        @endif
        @if($desaNama)
            <div class="info">Desa: {{ $desaNama }}</div>
        @endif
        @if($pinjamanNomor)
            <div class="info">No. Pinjaman: {{ $pinjamanNomor }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="8%" class="text-center">Angsuran Ke</th>
                <th width="13%">No. Pinjaman</th>
                <th width="18%">Nama Anggota</th>
                <th width="10%" class="text-center">Tanggal Bayar</th>
                <th width="12%" class="text-right">Pokok</th>
                <th width="12%" class="text-right">Jasa</th>
                <th width="12%" class="text-right">Total Bayar</th>
                <th width="12%" class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1;
                $totalPokok = 0;
                $totalJasa = 0;
                $totalBayar = 0;
                $totalDenda = 0;
            @endphp
            @forelse($angsuran as $item)
                @php
                    $totalPokok += $item->pokok_dibayar;
                    $totalJasa += $item->jasa_dibayar;
                    $totalBayar += $item->total_dibayar;
                    $totalDenda += $item->denda_dibayar ?? 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ $item->angsuran_ke }}</td>
                    <td>{{ $item->pinjaman->nomor_pinjaman ?? '-' }}</td>
                    <td>{{ $item->pinjaman->anggota->nama ?? '-' }}</td>
                    <td class="text-center">{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                    <td class="text-right">{{ number_format($item->pokok_dibayar, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->jasa_dibayar, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total_dibayar, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->denda_dibayar ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">
                        Tidak ada data angsuran ditemukan.
                    </td>
                </tr>
            @endforelse
            
            @if($angsuran->count() > 0)
                <tr class="total-row">
                    <td colspan="5" class="text-right">TOTAL:</td>
                    <td class="text-right">{{ number_format($totalPokok, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalJasa, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalBayar, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalDenda, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $angsuran->count() }} angsuran | Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </div>
</body>
</html>

