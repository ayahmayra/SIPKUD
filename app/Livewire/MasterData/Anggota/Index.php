<?php

namespace App\Livewire\MasterData\Anggota;

use App\Models\Anggota;
use App\Models\Kelompok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Master Anggota'])]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $kelompokFilter = null;
    public string $statusFilter = '';
    public ?int $kecamatanFilter = null;
    public ?int $desaFilter = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'kelompokFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'kecamatanFilter' => ['except' => null],
        'desaFilter' => ['except' => null],
    ];

    public function mount(): void
    {
        // Admin Desa dan Admin Kecamatan bisa melihat anggota
        // Admin Kecamatan hanya bisa melihat (read-only), tidak bisa create/edit/delete
        Gate::authorize('view_desa_data');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingKelompokFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
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

    public function delete(int $anggotaId): void
    {
        // Hanya Admin Desa yang bisa menghapus anggota
        $user = Auth::user();
        if (!$user || !$user->isAdminDesa()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus anggota.');
        }
        
        $anggota = Anggota::findOrFail($anggotaId);
        
        // Catatan: Di fase selanjutnya, akan ada relasi ke modul Pinjaman
        // Jika anggota memiliki pinjaman aktif, tidak boleh dihapus
        // Untuk sekarang, anggota bisa dihapus karena belum ada relasi ke modul lain
        
        $anggota->delete();
        $this->dispatch('success', message: 'Anggota berhasil dihapus.');
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Anggota::with(['kelompok', 'desa.kecamatan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('alamat', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->kelompokFilter, function ($query) {
                $query->where('kelompok_id', $this->kelompokFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->kecamatanFilter, function ($query) {
                $query->whereHas('desa', function ($q) {
                    $q->where('kecamatan_id', $this->kecamatanFilter);
                });
            })
            ->when($this->desaFilter, function ($query) {
                $query->where('desa_id', $this->desaFilter);
            })
            ->orderBy('nama');

        // Get kelompok for filter
        $kelompokQuery = Kelompok::where('status', 'aktif');
        if ($this->desaFilter) {
            $kelompokQuery->where('desa_id', $this->desaFilter);
        } elseif ($this->kecamatanFilter) {
            $kelompokQuery->whereHas('desa', function ($q) {
                $q->where('kecamatan_id', $this->kecamatanFilter);
            });
        } elseif ($user && $user->isAdminKecamatan()) {
            // Admin kecamatan hanya melihat kelompok di kecamatannya
            $kelompokQuery->whereHas('desa', function ($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        }
        $kelompok = $kelompokQuery->orderBy('nama_kelompok')->get();

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
            // Admin kecamatan bisa filter berdasarkan desa di kecamatannya
            $desa = \App\Models\Desa::where('kecamatan_id', $user->kecamatan_id)
                ->aktif()
                ->orderBy('nama_desa')
                ->get();
        }

        return view('livewire.master-data.anggota.index', [
            'anggota' => $query->paginate(10),
            'kelompok' => $kelompok,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
        ]);
    }
}

