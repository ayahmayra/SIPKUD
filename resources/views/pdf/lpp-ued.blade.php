<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan LPP UED</title>
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
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .status-aktif {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-lunas {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-nunggak {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LPP UED</h1>
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
                <th width="3%" class="text-center">No</th>
                <th width="10%">NIK</th>
                <th width="15%">Nama Anggota</th>
                <th width="12%">No. Pinjaman</th>
                <th width="12%" class="text-right">Jml Pinjaman</th>
                <th width="12%" class="text-right">Total Pokok</th>
                <th width="12%" class="text-right">Total Jasa</th>
                <th width="12%" class="text-right">Sisa Pinjaman</th>
                <th width="12%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalJumlahPinjaman = 0;
                $totalAngsuranPokok = 0;
                $totalJasa = 0;
                $totalSisaPinjaman = 0;
            @endphp
            @foreach($laporan as $item)
                @php
                    $totalJumlahPinjaman += $item['jumlah_pinjaman'];
                    $totalAngsuranPokok += $item['total_angsuran_pokok'];
                    $totalJasa += $item['total_jasa'];
                    $totalSisaPinjaman += $item['sisa_pinjaman'];
                    
                    $statusLabel = match($item['status_pinjaman']) {
                        'aktif' => 'Aktif',
                        'lunas' => 'Lunas',
                        'nunggak' => 'Nunggak',
                        default => $item['status_pinjaman']
                    };
                    
                    $statusClass = match($item['status_pinjaman']) {
                        'aktif' => 'status-aktif',
                        'lunas' => 'status-lunas',
                        'nunggak' => 'status-nunggak',
                        default => ''
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nomor_anggota'] }}</td>
                    <td>{{ $item['nama_anggota'] }}</td>
                    <td>{{ $item['nomor_pinjaman'] }}</td>
                    <td class="text-right">{{ number_format($item['jumlah_pinjaman'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total_angsuran_pokok'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total_jasa'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['sisa_pinjaman'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-center">TOTAL</td>
                <td class="text-right">{{ number_format($totalJumlahPinjaman, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalAngsuranPokok, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalJasa, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalSisaPinjaman, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </div>
</body>
</html>

