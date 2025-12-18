<?php

namespace App\Livewire\Kas;

use App\Models\TransaksiKas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Saldo Awal Kas'])]
class SaldoAwal extends Component
{
    public $tanggal_saldo_awal;
    public $jumlah_saldo_awal;
    public $keterangan;
    
    public $saldoAwalExists = false;
    public $saldoAwalId = null;

    public function mount(): void
    {
        // Hanya Admin Desa yang bisa input saldo awal
        Gate::authorize('admin_desa');
        
        $user = Auth::user();
        
        // Cek apakah sudah ada saldo awal untuk desa ini
        $saldoAwal = TransaksiKas::where('desa_id', $user->desa_id)
                                  ->where('jenis_transaksi', 'saldo_awal')
                                  ->first();
        
        if ($saldoAwal) {
            $this->saldoAwalExists = true;
            $this->saldoAwalId = $saldoAwal->id;
            $this->tanggal_saldo_awal = $saldoAwal->tanggal_transaksi->format('Y-m-d');
            $this->jumlah_saldo_awal = $saldoAwal->jumlah;
            $this->keterangan = $saldoAwal->uraian;
        } else {
            // Default tanggal adalah hari ini
            $this->tanggal_saldo_awal = now()->format('Y-m-d');
            $this->keterangan = 'Saldo awal kas dari sistem manual';
        }
    }

    public function save()
    {
        Gate::authorize('admin_desa');
        
        $this->validate([
            'tanggal_saldo_awal' => 'required|date',
            'jumlah_saldo_awal' => 'required|numeric',
            'keterangan' => 'required|string|max:255',
        ], [
            'tanggal_saldo_awal.required' => 'Tanggal saldo awal harus diisi',
            'tanggal_saldo_awal.date' => 'Format tanggal tidak valid',
            'jumlah_saldo_awal.required' => 'Jumlah saldo awal harus diisi',
            'jumlah_saldo_awal.numeric' => 'Jumlah saldo awal harus berupa angka',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
        ]);
        
        $user = Auth::user();
        
        if ($this->saldoAwalExists) {
            // Update saldo awal yang sudah ada
            $saldoAwal = TransaksiKas::find($this->saldoAwalId);
            $saldoAwal->update([
                'tanggal_transaksi' => $this->tanggal_saldo_awal,
                'jumlah' => $this->jumlah_saldo_awal,
                'uraian' => $this->keterangan,
            ]);
            
            $this->dispatch('success', message: 'Saldo awal berhasil diupdate');
        } else {
            // Buat saldo awal baru
            TransaksiKas::create([
                'desa_id' => $user->desa_id,
                'tanggal_transaksi' => $this->tanggal_saldo_awal,
                'uraian' => $this->keterangan,
                'jenis_transaksi' => 'saldo_awal',
                'jumlah' => $this->jumlah_saldo_awal,
            ]);
            
            $this->dispatch('success', message: 'Saldo awal berhasil disimpan');
            $this->saldoAwalExists = true;
        }
    }

    public function render()
    {
        return view('livewire.kas.saldo-awal');
    }
}
