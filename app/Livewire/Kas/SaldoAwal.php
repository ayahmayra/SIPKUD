<?php

namespace App\Livewire\Kas;

use App\Models\Akun;
use App\Models\TransaksiKas;
use App\Models\UnitUsaha;
use App\Services\AccountingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Saldo Awal Kas'])]
class SaldoAwal extends Component
{
    public $tanggal_saldo_awal;
    public $jumlah_saldo_awal;
    public $keterangan;
    public $akun_kas_id;
    public $akun_lawan_id;
    public $unit_usaha_id;
    
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
            $this->akun_kas_id = $saldoAwal->akun_kas_id;
            $this->akun_lawan_id = $saldoAwal->akun_lawan_id;
            $this->unit_usaha_id = $saldoAwal->unit_usaha_id;
        } else {
            // Default tanggal adalah hari ini
            $this->tanggal_saldo_awal = now()->format('Y-m-d');
            $this->keterangan = 'Saldo awal kas dari sistem manual';
            
            // Default akun kas (ambil akun Kas pertama)
            $akunKas = Akun::where('desa_id', $user->desa_id)
                ->where('nama_akun', 'Kas')
                ->first();
            if ($akunKas) {
                $this->akun_kas_id = $akunKas->id;
            }
            
            // Default akun lawan (ambil akun Modal pertama)
            $akunModal = Akun::where('desa_id', $user->desa_id)
                ->where('tipe_akun', 'ekuitas')
                ->where('nama_akun', 'like', '%Modal%')
                ->first();
            if ($akunModal) {
                $this->akun_lawan_id = $akunModal->id;
            }
        }
    }

    public function save(AccountingService $accountingService)
    {
        Gate::authorize('admin_desa');
        
        $user = Auth::user();
        
        // Validasi periode tidak boleh closed
        $periode = Carbon::parse($this->tanggal_saldo_awal)->format('Y-m');
        if ($accountingService->isPeriodClosed($user->desa_id, $periode, $this->unit_usaha_id)) {
            throw ValidationException::withMessages([
                'tanggal_saldo_awal' => sprintf(
                    'Periode %s sudah dikunci. Saldo awal tidak dapat diubah.',
                    Carbon::createFromFormat('Y-m', $periode)->locale('id')->isoFormat('MMMM YYYY')
                ),
            ]);
        }
        
        $this->validate([
            'tanggal_saldo_awal' => 'required|date',
            'jumlah_saldo_awal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
            'akun_kas_id' => 'required|exists:akun,id',
            'akun_lawan_id' => 'required|exists:akun,id',
        ], [
            'tanggal_saldo_awal.required' => 'Tanggal saldo awal harus diisi',
            'tanggal_saldo_awal.date' => 'Format tanggal tidak valid',
            'jumlah_saldo_awal.required' => 'Jumlah saldo awal harus diisi',
            'jumlah_saldo_awal.numeric' => 'Jumlah saldo awal harus berupa angka',
            'jumlah_saldo_awal.min' => 'Jumlah saldo awal tidak boleh negatif',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
            'akun_kas_id.required' => 'Akun kas harus dipilih',
            'akun_kas_id.exists' => 'Akun kas tidak valid',
            'akun_lawan_id.required' => 'Akun lawan harus dipilih',
            'akun_lawan_id.exists' => 'Akun lawan tidak valid',
        ]);
        
        try {
            DB::transaction(function () use ($accountingService, $user) {
                if ($this->saldoAwalExists) {
                    // Update saldo awal yang sudah ada
                    $saldoAwal = TransaksiKas::find($this->saldoAwalId);
                    
                    // Update transaksi kas
                    $saldoAwal->update([
                        'tanggal_transaksi' => $this->tanggal_saldo_awal,
                        'jumlah' => $this->jumlah_saldo_awal,
                        'uraian' => $this->keterangan,
                        'akun_kas_id' => $this->akun_kas_id,
                        'akun_lawan_id' => $this->akun_lawan_id,
                        'unit_usaha_id' => $this->unit_usaha_id,
                    ]);
                    
                    // Update jurnal jika ada
                    if ($saldoAwal->jurnal) {
                        $accountingService->updateJurnal($saldoAwal->jurnal, [
                            'tanggal_transaksi' => $this->tanggal_saldo_awal,
                            'unit_usaha_id' => $this->unit_usaha_id,
                            'jenis_jurnal' => 'kas_harian',
                            'keterangan' => $this->keterangan,
                            'details' => [
                                [
                                    'akun_id' => $this->akun_kas_id,
                                    'posisi' => 'debit',
                                    'jumlah' => $this->jumlah_saldo_awal,
                                    'keterangan' => 'Saldo awal kas',
                                ],
                                [
                                    'akun_id' => $this->akun_lawan_id,
                                    'posisi' => 'kredit',
                                    'jumlah' => $this->jumlah_saldo_awal,
                                    'keterangan' => 'Saldo awal kas',
                                ],
                            ],
                        ]);
                    } else {
                        // Create jurnal baru jika belum ada
                        $accountingService->createJurnal([
                            'desa_id' => $user->desa_id,
                            'unit_usaha_id' => $this->unit_usaha_id,
                            'tanggal_transaksi' => $this->tanggal_saldo_awal,
                            'jenis_jurnal' => 'kas_harian',
                            'keterangan' => $this->keterangan,
                            'status' => 'posted',
                            'transaksi_kas_id' => $saldoAwal->id,
                            'details' => [
                                [
                                    'akun_id' => $this->akun_kas_id,
                                    'posisi' => 'debit',
                                    'jumlah' => $this->jumlah_saldo_awal,
                                    'keterangan' => 'Saldo awal kas',
                                ],
                                [
                                    'akun_id' => $this->akun_lawan_id,
                                    'posisi' => 'kredit',
                                    'jumlah' => $this->jumlah_saldo_awal,
                                    'keterangan' => 'Saldo awal kas',
                                ],
                            ],
                        ]);
                    }
                    
                    $this->dispatch('success', message: 'Saldo awal berhasil diupdate');
                } else {
                    // Buat saldo awal baru
                    $transaksiKas = TransaksiKas::create([
                        'desa_id' => $user->desa_id,
                        'unit_usaha_id' => $this->unit_usaha_id,
                        'tanggal_transaksi' => $this->tanggal_saldo_awal,
                        'uraian' => $this->keterangan,
                        'jenis_transaksi' => 'saldo_awal',
                        'akun_kas_id' => $this->akun_kas_id,
                        'akun_lawan_id' => $this->akun_lawan_id,
                        'jumlah' => $this->jumlah_saldo_awal,
                    ]);
                    
                    // Auto-create jurnal
                    $accountingService->createJurnal([
                        'desa_id' => $user->desa_id,
                        'unit_usaha_id' => $this->unit_usaha_id,
                        'tanggal_transaksi' => $this->tanggal_saldo_awal,
                        'jenis_jurnal' => 'kas_harian',
                        'keterangan' => $this->keterangan,
                        'status' => 'posted',
                        'transaksi_kas_id' => $transaksiKas->id,
                        'details' => [
                            [
                                'akun_id' => $this->akun_kas_id,
                                'posisi' => 'debit',
                                'jumlah' => $this->jumlah_saldo_awal,
                                'keterangan' => 'Saldo awal kas',
                            ],
                            [
                                'akun_id' => $this->akun_lawan_id,
                                'posisi' => 'kredit',
                                'jumlah' => $this->jumlah_saldo_awal,
                                'keterangan' => 'Saldo awal kas',
                            ],
                        ],
                    ]);
                    
                    $this->dispatch('success', message: 'Saldo awal berhasil disimpan dan jurnal telah dibuat.');
                    $this->saldoAwalExists = true;
                    $this->saldoAwalId = $transaksiKas->id;
                }
            });
        } catch (ValidationException $e) {
            $this->dispatch('error', message: $e->getMessage());
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        $akunKas = Akun::where('desa_id', $user->desa_id)
            ->where('tipe_akun', 'aset')
            ->whereIn('nama_akun', ['Kas', 'Bank', 'Kas Kecil'])
            ->aktif()
            ->orderBy('kode_akun')
            ->get();
        
        $akunLawan = Akun::where('desa_id', $user->desa_id)
            ->where('tipe_akun', 'ekuitas')
            ->aktif()
            ->orderBy('kode_akun')
            ->get();
        
        $unitUsaha = UnitUsaha::where('desa_id', $user->desa_id)
            ->aktif()
            ->orderBy('nama_unit')
            ->get();
        
        return view('livewire.kas.saldo-awal', [
            'akunKas' => $akunKas,
            'akunLawan' => $akunLawan,
            'unitUsaha' => $unitUsaha,
        ]);
    }
}
