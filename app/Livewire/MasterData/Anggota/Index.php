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

    protected $queryString = [
        'search' => ['except' => ''],
        'kelompokFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
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

    public function delete(int $anggotaId): void
    {
        // Hanya Admin Desa yang bisa menghapus anggota
        $user = Auth::user();
        if (!$user || (!$user->isAdminDesa() && !$user->isSuperAdmin())) {
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
        $query = Anggota::with('kelompok')
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
            ->orderBy('nama');

        $kelompok = Kelompok::where('status', 'aktif')
            ->orderBy('nama_kelompok')
            ->get();

        return view('livewire.master-data.anggota.index', [
            'anggota' => $query->paginate(10),
            'kelompok' => $kelompok,
        ]);
    }
}

