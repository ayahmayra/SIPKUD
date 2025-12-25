<?php

namespace App\Exports;

use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;

class LaporanAkhirUspExport
{
    protected array $data;
    protected ?int $bulan;
    protected ?int $tahun;
    protected ?string $kecamatanNama;
    protected ?string $desaNama;

    public function __construct(
        array $data,
        ?int $bulan = null,
        ?int $tahun = null,
        ?string $kecamatanNama = null,
        ?string $desaNama = null
    ) {
        $this->data = $data;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kecamatanNama = $kecamatanNama;
        $this->desaNama = $desaNama;
    }

    public function export(string $filePath): void
    {
        $writer = new Writer();
        $writer->openToFile($filePath);

        // Title style
        $titleStyle = new Style();
        $titleStyle->setFontBold();
        $titleStyle->setFontSize(14);

        // Header style
        $headerStyle = new Style();
        $headerStyle->setFontBold();
        $headerStyle->setFontSize(11);
        $headerStyle->setBackgroundColor(Color::rgb(79, 129, 189));
        $headerStyle->setFontColor(Color::WHITE);

        // Subtitle style
        $subtitleStyle = new Style();
        $subtitleStyle->setFontBold();

        $writer->addRow(Row::fromValues(['LAPORAN AKHIR USP'], $titleStyle));
        
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

        // Data sections
        $writer->addRow(Row::fromValues(['PENDAPATAN'], $subtitleStyle));
        $writer->addRow(Row::fromValues([
            'Pendapatan Jasa',
            'Rp ' . number_format($this->data['totalPendapatanJasa'], 0, ',', '.')
        ]));
        $writer->addRow(Row::fromValues([
            'Pendapatan Denda',
            'Rp ' . number_format($this->data['totalPendapatanDenda'], 0, ',', '.')
        ]));
        $writer->addRow(Row::fromValues([
            'Total Pendapatan',
            'Rp ' . number_format($this->data['totalPendapatan'], 0, ',', '.')
        ], $subtitleStyle));
        
        $writer->addRow(Row::fromValues([''])); // Empty row
        
        $writer->addRow(Row::fromValues(['SISA HASIL USAHA (SHU)'], $subtitleStyle));
        $writer->addRow(Row::fromValues([
            "SHU ({$this->data['persentaseShu']}% dari Total Pendapatan)",
            'Rp ' . number_format($this->data['totalShu'], 0, ',', '.')
        ]));
        
        $writer->addRow(Row::fromValues([''])); // Empty row
        
        $writer->addRow(Row::fromValues(['PINJAMAN'], $subtitleStyle));
        $writer->addRow(Row::fromValues([
            'Total Pinjaman Tersalurkan',
            'Rp ' . number_format($this->data['totalPinjamanTersalurkan'], 0, ',', '.')
        ]));
        $writer->addRow(Row::fromValues([
            'Total Pokok Terbayar',
            'Rp ' . number_format($this->data['totalPokokTerbayar'], 0, ',', '.')
        ]));
        $writer->addRow(Row::fromValues([
            'Jumlah Pinjaman Aktif',
            $this->data['jumlahPinjamanAktif'] . ' pinjaman'
        ]));
        $writer->addRow(Row::fromValues([
            'Total Sisa Pinjaman',
            'Rp ' . number_format($this->data['totalSisaPinjaman'], 0, ',', '.')
        ], $subtitleStyle));

        $writer->close();
    }
}

