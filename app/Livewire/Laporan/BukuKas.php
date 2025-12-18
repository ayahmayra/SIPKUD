<?php

namespace App\Livewire\Laporan;

use App\Models\TransaksiKas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Buku Kas USP'])]
class BukuKas extends Component
{
    public ?int $bulan = null;
    public ?int $tahun = null;

    protected $queryString = [
        'bulan' => ['except' => null],
        'tahun' => ['except' => null],
    ];

    public function mount(): void
    {
        // Admin Desa, Admin Kecamatan, dan Super Admin bisa melihat laporan
        Gate::authorize('view_desa_data');
        
        // Set default bulan dan tahun ke bulan/tahun saat ini
        if (!$this->bulan) {
            $this->bulan = (int) now()->format('m');
        }
        if (!$this->tahun) {
            $this->tahun = (int) now()->format('Y');
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        // Query transaksi kas berdasarkan role
        $query = TransaksiKas::query()
            ->with(['pinjaman.anggota', 'angsuranPinjaman.pinjaman.anggota']);
        
        // Filter berdasarkan role
        if ($user->isAdminDesa()) {
            $query->where('desa_id', $user->desa_id);
        } elseif ($user->isAdminKecamatan()) {
            $query->whereHas('desa', fn($q) => $q->where('kecamatan_id', $user->kecamatan_id));
        }
        // Super Admin bisa lihat semua
        
        // Filter bulan dan tahun
        if ($this->bulan && $this->tahun) {
            $query->whereMonth('tanggal_transaksi', $this->bulan)
                  ->whereYear('tanggal_transaksi', $this->tahun);
        } elseif ($this->tahun) {
            $query->whereYear('tanggal_transaksi', $this->tahun);
        }
        
        // Ambil transaksi dan urutkan berdasarkan tanggal
        $transaksi = $query->orderBy('tanggal_transaksi')
                          ->orderBy('id')
                          ->get();
        
        // Hitung saldo awal (sebelum periode filter)
        $saldoAwalQuery = TransaksiKas::query();
        
        if ($user->isAdminDesa()) {
            $saldoAwalQuery->where('desa_id', $user->desa_id);
        } elseif ($user->isAdminKecamatan()) {
            $saldoAwalQuery->whereHas('desa', fn($q) => $q->where('kecamatan_id', $user->kecamatan_id));
        }
        
        // Hitung saldo sebelum periode yang dipilih
        if ($this->bulan && $this->tahun) {
            $tanggalAwal = now()->setYear($this->tahun)->setMonth($this->bulan)->startOfMonth();
            $saldoAwalQuery->where('tanggal_transaksi', '<', $tanggalAwal);
        } elseif ($this->tahun) {
            $tanggalAwal = now()->setYear($this->tahun)->startOfYear();
            $saldoAwalQuery->where('tanggal_transaksi', '<', $tanggalAwal);
        }
        
        $saldoAwal = $saldoAwalQuery->sum(\DB::raw("CASE WHEN jenis_transaksi = 'masuk' THEN jumlah ELSE -jumlah END"));
        
        // Hitung saldo berjalan untuk setiap transaksi
        $saldoBerjalan = $saldoAwal;
        $dataWithSaldo = $transaksi->map(function ($item) use (&$saldoBerjalan) {
            if ($item->jenis_transaksi === 'masuk') {
                $saldoBerjalan += $item->jumlah;
            } else {
                $saldoBerjalan -= $item->jumlah;
            }
            
            $item->saldo_berjalan = $saldoBerjalan;
            return $item;
        });
        
        // Generate list bulan untuk dropdown
        $bulanList = collect(range(1, 12))->map(function ($m) {
            return [
                'value' => $m,
                'label' => \Carbon\Carbon::create()->month($m)->translatedFormat('F')
            ];
        });
        
        // Generate list tahun (3 tahun terakhir + tahun sekarang + 1 tahun ke depan)
        $tahunSekarang = now()->year;
        $tahunList = range($tahunSekarang - 3, $tahunSekarang + 1);
        
        return view('livewire.laporan.buku-kas', [
            'transaksi' => $dataWithSaldo,
            'saldoAwal' => $saldoAwal,
            'bulanList' => $bulanList,
            'tahunList' => $tahunList,
        ]);
    }
}
