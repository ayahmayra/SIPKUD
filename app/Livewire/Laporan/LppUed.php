<?php

namespace App\Livewire\Laporan;

use App\Models\Pinjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Laporan LPP UED'])]
class LppUed extends Component
{
    public ?int $bulan = null;
    public ?int $tahun = null;

    protected $queryString = [
        'bulan' => ['except' => null],
        'tahun' => ['except' => null],
    ];

    public function mount(): void
    {
        // Admin Desa dan Admin Kecamatan bisa melihat laporan
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
        
        // Query pinjaman dengan aggregasi angsuran
        $query = Pinjaman::with(['anggota', 'angsuran'])
            ->when($this->bulan && $this->tahun, function ($q) {
                // Filter berdasarkan bulan dan tahun pinjaman
                $q->whereYear('tanggal_pinjaman', $this->tahun)
                  ->whereMonth('tanggal_pinjaman', $this->bulan);
            })
            ->when($user && $user->desa_id, function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            })
            ->orderBy('tanggal_pinjaman', 'desc')
            ->orderBy('nomor_pinjaman', 'desc');

        $pinjaman = $query->get();

        // Transform data untuk laporan
        $laporan = $pinjaman->map(function ($p) {
            return [
                'nomor_anggota' => $p->anggota->nik ?? '-',
                'nama_anggota' => $p->anggota->nama,
                'nomor_pinjaman' => $p->nomor_pinjaman,
                'jumlah_pinjaman' => (float) $p->jumlah_pinjaman,
                'total_angsuran_pokok' => $p->total_pokok_dibayar,
                'total_jasa' => $p->total_jasa_dibayar,
                'sisa_pinjaman' => $p->sisa_pinjaman,
                'status_pinjaman' => $p->status_pinjaman,
            ];
        });

        // Generate list bulan dan tahun untuk filter
        $bulanList = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanList[$i] = \Carbon\Carbon::create(null, $i, 1)->locale('id')->translatedFormat('F');
        }

        $tahunList = [];
        $currentYear = (int) now()->format('Y');
        for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
            $tahunList[$i] = $i;
        }

        return view('livewire.laporan.lpp-ued', [
            'laporan' => $laporan,
            'bulanList' => $bulanList,
            'tahunList' => $tahunList,
        ]);
    }
}
