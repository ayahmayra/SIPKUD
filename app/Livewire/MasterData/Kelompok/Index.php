<?php

namespace App\Livewire\MasterData\Kelompok;

use App\Models\Kelompok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Master Kelompok'])]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount(): void
    {
        // Admin Desa dan Admin Kecamatan bisa melihat kelompok
        // Admin Kecamatan hanya bisa melihat (read-only), tidak bisa create/edit/delete
        Gate::authorize('view_desa_data');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $kelompokId): void
    {
        // Hanya Admin Desa yang bisa menghapus kelompok
        $user = Auth::user();
        if (!$user || (!$user->isAdminDesa() && !$user->isSuperAdmin())) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus kelompok.');
        }
        
        $kelompok = Kelompok::findOrFail($kelompokId);
        
        // Check if kelompok has anggota
        $anggotaCount = $kelompok->anggota()->count();
        if ($anggotaCount > 0) {
            $this->dispatch('error', message: "Tidak dapat menghapus kelompok yang memiliki {$anggotaCount} anggota.");
            return;
        }

        $kelompok->delete();
        $this->dispatch('success', message: 'Kelompok berhasil dihapus.');
    }

    public function render()
    {
        $query = Kelompok::withCount('anggota')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_kelompok', 'like', '%' . $this->search . '%')
                        ->orWhere('keterangan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('nama_kelompok');

        return view('livewire.master-data.kelompok.index', [
            'kelompok' => $query->paginate(10),
        ]);
    }
}

