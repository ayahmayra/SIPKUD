<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;

class LppUedExport
{
    protected Collection $laporan;
    protected ?int $bulan;
    protected ?int $tahun;
    protected ?string $kecamatanNama;
    protected ?string $desaNama;

    public function __construct(
        Collection $laporan,
        ?int $bulan = null,
        ?int $tahun = null,
        ?string $kecamatanNama = null,
        ?string $desaNama = null
    ) {
        $this->laporan = $laporan;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kecamatanNama = $kecamatanNama;
        $this->desaNama = $desaNama;
    }

    public function export(string $filePath): void
    {
        $writer = new Writer();
        $writer->openToFile($filePath);

        // Header style
        $headerStyle = new Style();
        $headerStyle->setFontBold();
        $headerStyle->setFontSize(12);
        $headerStyle->setBackgroundColor(Color::rgb(79, 129, 189));
        $headerStyle->setFontColor(Color::WHITE);

        // Title style
        $titleStyle = new Style();
        $titleStyle->setFontBold();
        $titleStyle->setFontSize(14);

        $writer->addRow(Row::fromValues(['LAPORAN LPP UED'], $titleStyle));
        
        // Periode
        $periode = '';
        if ($this->bulan && $this->tahun) {
            $periode = \Carbon\Carbon::create($this->tahun, $this->bulan, 1)->translatedFormat('F Y');
        } elseif ($this->tahun) {
            $periode = "Tahun {$this->tahun}";
        }
        
        if ($periode) {
            $writer->addRow(Row::fromValues(["Periode: {$periode}"]));
        }
        
        if ($this->kecamatanNama) {
            $writer->addRow(Row::fromValues(["Kecamatan: {$this->kecamatanNama}"]));
        }
        
        if ($this->desaNama) {
            $writer->addRow(Row::fromValues(["Desa: {$this->desaNama}"]));
        }
        
        $writer->addRow(Row::fromValues([''])); // Empty row

        // Table headers
        $headers = [
            'No',
            'NIK',
            'Nama Anggota',
            'Nomor Pinjaman',
            'Jumlah Pinjaman',
            'Total Angsuran Pokok',
            'Total Jasa',
            'Sisa Pinjaman',
            'Status'
        ];
        $writer->addRow(Row::fromValues($headers, $headerStyle));

        // Data rows
        $no = 1;
        $totalJumlahPinjaman = 0;
        $totalAngsuranPokok = 0;
        $totalJasa = 0;
        $totalSisaPinjaman = 0;
        
        foreach ($this->laporan as $item) {
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

            $writer->addRow(Row::fromValues([
                $no++,
                $item['nomor_anggota'],
                $item['nama_anggota'],
                $item['nomor_pinjaman'],
                number_format($item['jumlah_pinjaman'], 0, ',', '.'),
                number_format($item['total_angsuran_pokok'], 0, ',', '.'),
                number_format($item['total_jasa'], 0, ',', '.'),
                number_format($item['sisa_pinjaman'], 0, ',', '.'),
                $statusLabel
            ]));
        }

        // Total row
        $totalStyle = new Style();
        $totalStyle->setFontBold();
        $totalStyle->setBackgroundColor(Color::rgb(217, 217, 217));

        $writer->addRow(Row::fromValues([
            '',
            '',
            '',
            'TOTAL',
            number_format($totalJumlahPinjaman, 0, ',', '.'),
            number_format($totalAngsuranPokok, 0, ',', '.'),
            number_format($totalJasa, 0, ',', '.'),
            number_format($totalSisaPinjaman, 0, ',', '.'),
            ''
        ], $totalStyle));

        $writer->close();
    }
}

