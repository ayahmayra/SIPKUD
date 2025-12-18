<?php

namespace App\Livewire\Angsuran;

use App\Models\AngsuranPinjaman;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Master Angsuran'])]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $pinjamanFilter = null;
    public ?int $kecamatanFilter = null;
    public ?int $desaFilter = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'pinjamanFilter' => ['except' => null],
        'kecamatanFilter' => ['except' => null],
        'desaFilter' => ['except' => null],
    ];

    public function mount(): void
    {
        // Admin Desa dan Admin Kecamatan bisa melihat angsuran
        // Admin Kecamatan hanya bisa melihat (read-only), tidak bisa create/edit/delete
        Gate::authorize('view_desa_data');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPinjamanFilter(): void
    {
        $this->resetPage();
    }

    public function updatingKecamatanFilter(): void
    {
        $this->resetPage();
        $this->reset('desaFilter');
    }

    public function updatingDesaFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $angsuranId): void
    {
        // Hanya Admin Desa yang bisa menghapus angsuran
        $user = Auth::user();
        if (!$user || !$user->isAdminDesa()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus angsuran.');
        }
        
        $angsuran = AngsuranPinjaman::findOrFail($angsuranId);
        $angsuran->delete();
        
        $this->dispatch('success', message: 'Angsuran berhasil dihapus.');
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = AngsuranPinjaman::with(['pinjaman.anggota', 'pinjaman.desa.kecamatan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('pinjaman', function ($q) {
                        $q->where('nomor_pinjaman', 'like', '%' . $this->search . '%')
                            ->orWhereHas('anggota', function ($q) {
                                $q->where('nama', 'like', '%' . $this->search . '%');
                            });
                    });
                });
            })
            ->when($this->pinjamanFilter, function ($query) {
                $query->where('pinjaman_id', $this->pinjamanFilter);
            })
            ->when($this->kecamatanFilter, function ($query) {
                $query->whereHas('pinjaman.desa', function ($q) {
                    $q->where('kecamatan_id', $this->kecamatanFilter);
                });
            })
            ->when($this->desaFilter, function ($query) {
                $query->whereHas('pinjaman', function ($q) {
                    $q->where('desa_id', $this->desaFilter);
                });
            })
            ->orderBy('tanggal_bayar', 'desc')
            ->orderBy('angsuran_ke', 'desc');

        // Get pinjaman for filter
        $pinjamanQuery = Pinjaman::with('anggota');
        if ($user && $user->desa_id) {
            $pinjamanQuery->where('desa_id', $user->desa_id);
        }
        if ($this->desaFilter) {
            $pinjamanQuery->where('desa_id', $this->desaFilter);
        } elseif ($this->kecamatanFilter) {
            $pinjamanQuery->whereHas('desa', function ($q) {
                $q->where('kecamatan_id', $this->kecamatanFilter);
            });
        }
        $pinjaman = $pinjamanQuery->orderBy('tanggal_pinjaman', 'desc')->get();

        // Get kecamatan and desa for filters
        $kecamatan = collect();
        $desa = collect();
        
        if ($user && $user->isSuperAdmin()) {
            $kecamatan = \App\Models\Kecamatan::aktif()->orderBy('nama_kecamatan')->get();
            if ($this->kecamatanFilter) {
                $desa = \App\Models\Desa::where('kecamatan_id', $this->kecamatanFilter)
                    ->aktif()
                    ->orderBy('nama_desa')
                    ->get();
            }
        } elseif ($user && $user->isAdminKecamatan()) {
            $desa = \App\Models\Desa::where('kecamatan_id', $user->kecamatan_id)
                ->aktif()
                ->orderBy('nama_desa')
                ->get();
        }

        return view('livewire.angsuran.index', [
            'angsuran' => $query->paginate(10),
            'pinjaman' => $pinjaman,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
        ]);
    }
}
