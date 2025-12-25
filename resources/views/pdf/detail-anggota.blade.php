<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Anggota</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.6;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4f81bd;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #4f81bd;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #4f81bd;
            margin-bottom: 12px;
            padding: 8px;
            background-color: #e8f4ff;
            border-left: 4px solid #4f81bd;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 35%;
            padding: 8px;
            font-weight: 600;
            color: #555;
            border-bottom: 1px solid #eee;
        }
        
        .info-value {
            display: table-cell;
            width: 65%;
            padding: 8px;
            color: #333;
            border-bottom: 1px solid #eee;
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
            font-size: 10px;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 10px;
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
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
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
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            font-size: 9px;
            color: #666;
            text-align: right;
        }
        
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 12px;
            margin-top: 15px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
        }
        
        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 12px;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL ANGGOTA USP/UED-SP</h1>
        <p>Informasi Lengkap dan Riwayat Pinjaman Anggota</p>
    </div>

    <!-- Informasi Anggota -->
    <div class="section">
        <div class="section-title">INFORMASI ANGGOTA</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">NIK</div>
                <div class="info-value">{{ $anggota->nik }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ $anggota->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jenis Kelamin</div>
                <div class="info-value">{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nomor HP</div>
                <div class="info-value">{{ $anggota->nomor_hp ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kelompok</div>
                <div class="info-value">{{ $anggota->kelompok->nama_kelompok ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Desa</div>
                <div class="info-value">{{ $anggota->desa->nama_desa ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kecamatan</div>
                <div class="info-value">{{ $anggota->desa->kecamatan->nama_kecamatan ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Alamat</div>
                <div class="info-value">{{ $anggota->alamat ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Gabung</div>
                <div class="info-value">{{ $anggota->tanggal_gabung ? $anggota->tanggal_gabung->format('d F Y') : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">{{ ucfirst($anggota->status) }}</div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pinjaman -->
    <div class="section">
        <div class="section-title">RIWAYAT PINJAMAN ({{ count($pinjaman) }})</div>
        
        @if(count($pinjaman) > 0)
            <table>
                <thead>
                    <tr>
                        <th width="15%">No. Pinjaman</th>
                        <th width="10%" class="text-center">Tanggal</th>
                        <th width="15%" class="text-right">Jumlah</th>
                        <th width="8%" class="text-center">Jangka</th>
                        <th width="8%" class="text-center">Jasa</th>
                        <th width="15%" class="text-right">Terbayar</th>
                        <th width="15%" class="text-right">Sisa</th>
                        <th width="14%" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalJumlah = 0;
                        $totalTerbayar = 0;
                        $totalSisa = 0;
                    @endphp
                    @foreach($pinjaman as $p)
                        @php
                            $totalJumlah += $p['jumlah_pinjaman'];
                            $totalTerbayar += $p['total_pokok_dibayar'];
                            $totalSisa += $p['sisa_pinjaman'];
                            
                            $statusLabel = ucfirst($p['status_pinjaman']);
                            $statusClass = match($p['status_pinjaman']) {
                                'aktif' => 'status-aktif',
                                'lunas' => 'status-lunas',
                                'nunggak' => 'status-nunggak',
                                default => ''
                            };
                        @endphp
                        <tr>
                            <td>{{ $p['nomor_pinjaman'] }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($p['tanggal_pinjaman'])->format('d/m/Y') }}</td>
                            <td class="text-right">Rp {{ number_format($p['jumlah_pinjaman'], 0, ',', '.') }}</td>
                            <td class="text-center">{{ $p['jangka_waktu'] }} bln</td>
                            <td class="text-center">{{ $p['persentase_jasa'] }}%</td>
                            <td class="text-right">Rp {{ number_format($p['total_pokok_dibayar'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($p['sisa_pinjaman'], 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2" class="text-right"><strong>TOTAL:</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($totalJumlah, 0, ',', '.') }}</strong></td>
                        <td colspan="2"></td>
                        <td class="text-right"><strong>Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($totalSisa, 0, ',', '.') }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <!-- Summary Box -->
            <div class="summary-box">
                <div class="summary-item">
                    <span>Total Pinjaman yang Pernah Diajukan:</span>
                    <span>{{ count($pinjaman) }} pinjaman</span>
                </div>
                <div class="summary-item">
                    <span>Total Nilai Pinjaman:</span>
                    <span>Rp {{ number_format($totalJumlah, 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Total Sudah Dibayar:</span>
                    <span>Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Total Sisa Pinjaman:</span>
                    <span>Rp {{ number_format($totalSisa, 0, ',', '.') }}</span>
                </div>
            </div>
        @else
            <div class="no-data">
                Belum ada riwayat pinjaman untuk anggota ini
            </div>
        @endif
    </div>

    <div class="footer">
        <strong>Dicetak pada:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>
</body>
</html>

