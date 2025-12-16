<?php

namespace App\Livewire\MasterData\Kelompok;

use App\Models\Kelompok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Tambah Kelompok'])]
class Create extends Component
{
    public string $nama_kelompok = '';
    public ?string $keterangan = null;
    public string $status = 'aktif';

    public function mount(): void
    {
        // Hanya Admin Desa yang bisa membuat kelompok
        if (!Auth::user()->isAdminDesa() && !Auth::user()->isSuperAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'nama_kelompok' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'nama_kelompok.required' => 'Nama kelompok wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        // Pastikan hanya admin desa yang bisa membuat kelompok
        if (!Auth::user()->isAdminDesa() && !Auth::user()->isSuperAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk membuat kelompok.');
        }
        
        // Admin kecamatan tidak memiliki desa_id, jadi tidak bisa membuat kelompok
        if (!Auth::user()->desa_id) {
            abort(403, 'Anda tidak memiliki izin untuk membuat kelompok.');
        }
        
        $validated['desa_id'] = Auth::user()->desa_id;
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        Kelompok::create($validated);

        $this->dispatch('success', message: 'Kelompok berhasil ditambahkan.');
        $this->redirect(route('kelompok.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.master-data.kelompok.create');
    }
}

