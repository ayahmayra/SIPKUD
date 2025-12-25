<?php

namespace App\Exports;

use App\Models\TransaksiKas;
use Illuminate\Support\Collection;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class BukuKasExport
{
    protected Collection $transaksi;
    protected float $saldoAwal;
    protected ?int $bulan;
    protected ?int $tahun;
    protected ?string $kecamatanNama;
    protected ?string $desaNama;

    public function __construct(
        Collection $transaksi,
        float $saldoAwal,
        ?int $bulan = null,
        ?int $tahun = null,
        ?string $kecamatanNama = null,
        ?string $desaNama = null
    ) {
        $this->transaksi = $transaksi;
        $this->saldoAwal = $saldoAwal;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kecamatanNama = $kecamatanNama;
        $this->desaNama = $desaNama;
    }

    public function export(string $filePath): void
    {
        $writer = new Writer();
        $writer->openToFile($filePath);

        $writer->addRow(Row::fromValues(['BUKU KAS USP']));
        
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
            'Tanggal',
            'Keterangan',
            'Debet',
            'Kredit',
            'Saldo'
        ];
        $writer->addRow(Row::fromValues($headers));

        // Saldo awal row
        $writer->addRow(Row::fromValues([
            '',
            '',
            'SALDO AWAL',
            '',
            '',
            number_format($this->saldoAwal, 0, ',', '.')
        ]));

        // Data rows
        $saldoBerjalan = $this->saldoAwal;
        $no = 1;
        
        foreach ($this->transaksi as $item) {
            $debet = $item->jenis_transaksi === 'masuk' ? number_format($item->jumlah, 0, ',', '.') : '';
            $kredit = $item->jenis_transaksi === 'keluar' ? number_format($item->jumlah, 0, ',', '.') : '';
            
            if ($item->jenis_transaksi === 'masuk') {
                $saldoBerjalan += $item->jumlah;
            } else {
                $saldoBerjalan -= $item->jumlah;
            }

            $writer->addRow(Row::fromValues([
                $no++,
                $item->tanggal_transaksi->format('d/m/Y'),
                $item->keterangan ?? '-',
                $debet,
                $kredit,
                number_format($saldoBerjalan, 0, ',', '.')
            ]));
        }

        $writer->close();
    }
}

