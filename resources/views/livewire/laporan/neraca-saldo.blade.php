<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl">Neraca Saldo</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Trial Balance - Saldo semua akun per periode
        </flux:heading>
    </div>

    @if(isset($error))
        <flux:card class="p-6 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-red-800 dark:text-red-200">Akses Ditolak</p>
                    <p class="text-red-700 dark:text-red-300">{{ $error }}</p>
                </div>
            </div>
        </flux:card>
    @endif

    <flux:card class="p-6">
        <div class="mb-6 grid gap-4 md:grid-cols-5">
            @if($desas->count() > 1)
            <div x-data="{ 
                search: '', 
                selectedId: @entangle('selectedDesaId'),
                desas: {{ $desas->toJson() }},
                get filteredDesas() {
                    if (!this.search) return this.desas;
                    return this.desas.filter(d => 
                        d.nama_desa.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                selectDesa(id, name) {
                    this.selectedId = id;
                    this.search = name;
                    this.$refs.dropdown.style.display = 'none';
                },
                init() {
                    const selected = this.desas.find(d => d.id === this.selectedId);
                    if (selected) this.search = selected.nama_desa;
                }
            }">
                <label class="block text-sm font-medium mb-1">Desa *</label>
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="search"
                        @focus="$refs.dropdown.style.display = 'block'"
                        @click.away="$refs.dropdown.style.display = 'none'"
                        placeholder="Ketik untuk mencari desa..."
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm"
                    />
                    <div x-ref="dropdown" style="display: none;" class="absolute z-10 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <template x-for="desa in filteredDesas" :key="desa.id">
                            <div 
                                @click="selectDesa(desa.id, desa.nama_desa)"
                                class="px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 cursor-pointer text-sm"
                                x-text="desa.nama_desa"
                            ></div>
                        </template>
                        <div x-show="filteredDesas.length === 0" class="px-3 py-2 text-sm text-zinc-500">
                            Tidak ada hasil
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div>
                <flux:select wire:model.live="unitUsahaId" label="Unit Usaha">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                    @endforeach
                </flux:select>
            </div>
            <div>
                <flux:select wire:model.live="bulan" label="Bulan">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                    @endfor
                </flux:select>
            </div>
            <div>
                <flux:select wire:model.live="tahun" label="Tahun">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </flux:select>
            </div>
            <div class="flex items-end">
                <flux:button 
                    wire:click="export"
                    variant="ghost"
                    class="w-full"
                >
                    <flux:icon.arrow-down-tray class="size-4" /> Export Excel
                </flux:button>
            </div>
        </div>

        @if(isset($error))
            <flux:card class="p-6 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20">
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-red-800 dark:text-red-200">Error</p>
                        <p class="text-red-700 dark:text-red-300">{{ $error }}</p>
                    </div>
                </div>
            </flux:card>
        @elseif($data && count($data) > 0)
            <div class="mb-4 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                    Periode: {{ $periode ?? 'Belum dipilih' }}
                </p>
                <p class="text-xs text-blue-800 dark:text-blue-200 mt-1">
                    Saldo Awal = Saldo Akhir bulan sebelumnya | Saldo Akhir = Saldo Awal + Mutasi
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800">
                            <th rowspan="2" class="px-3 py-3 text-left font-semibold border-r border-zinc-300 dark:border-zinc-600">Kode</th>
                            <th rowspan="2" class="px-3 py-3 text-left font-semibold border-r border-zinc-300 dark:border-zinc-600">Nama Akun</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold border-b border-zinc-300 dark:border-zinc-600">Saldo Awal</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold border-b border-zinc-300 dark:border-zinc-600">Mutasi Bulan Berjalan</th>
                            <th colspan="2" class="px-3 py-2 text-center font-semibold border-b border-zinc-300 dark:border-zinc-600">Saldo Akhir</th>
                        </tr>
                        <tr class="border-b-2 border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800">
                            <th class="px-3 py-2 text-right font-semibold">Debit</th>
                            <th class="px-3 py-2 text-right font-semibold border-r border-zinc-300 dark:border-zinc-600">Kredit</th>
                            <th class="px-3 py-2 text-right font-semibold">Debit</th>
                            <th class="px-3 py-2 text-right font-semibold border-r border-zinc-300 dark:border-zinc-600">Kredit</th>
                            <th class="px-3 py-2 text-right font-semibold">Debit</th>
                            <th class="px-3 py-2 text-right font-semibold">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $tipeAkun = ['aset' => 'ASET', 'kewajiban' => 'KEWAJIBAN', 'ekuitas' => 'EKUITAS', 'pendapatan' => 'PENDAPATAN', 'beban' => 'BEBAN'];
                            $grouped = collect($data)->groupBy('tipe_akun');
                        @endphp

                        @foreach($tipeAkun as $key => $label)
                            @if($grouped->has($key))
                                <tr class="bg-zinc-100 dark:bg-zinc-800 font-bold">
                                    <td colspan="8" class="px-3 py-2">{{ $label }}</td>
                                </tr>
                                @foreach($grouped[$key] as $row)
                                    <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                        <td class="px-3 py-2 font-mono text-xs">{{ $row['kode_akun'] }}</td>
                                        <td class="px-3 py-2">{{ $row['nama_akun'] }}</td>
                                        <td class="px-3 py-2 text-right font-mono">
                                            {{ $row['saldo_awal_debit'] > 0 ? 'Rp ' . number_format($row['saldo_awal_debit'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-right font-mono border-r border-zinc-200 dark:border-zinc-700">
                                            {{ $row['saldo_awal_kredit'] > 0 ? 'Rp ' . number_format($row['saldo_awal_kredit'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-right font-mono">
                                            {{ $row['mutasi_debit'] > 0 ? 'Rp ' . number_format($row['mutasi_debit'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-right font-mono border-r border-zinc-200 dark:border-zinc-700">
                                            {{ $row['mutasi_kredit'] > 0 ? 'Rp ' . number_format($row['mutasi_kredit'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-right font-mono">
                                            {{ $row['saldo_akhir_debit'] > 0 ? 'Rp ' . number_format($row['saldo_akhir_debit'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-right font-mono">
                                            {{ $row['saldo_akhir_kredit'] > 0 ? 'Rp ' . number_format($row['saldo_akhir_kredit'], 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-800 font-bold">
                            <td colspan="2" class="px-3 py-3 text-right">TOTAL</td>
                            <td class="px-3 py-3 text-right font-mono">
                                Rp {{ number_format($totalSaldoAwalDebit ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-right font-mono border-r border-zinc-300 dark:border-zinc-600">
                                Rp {{ number_format($totalSaldoAwalKredit ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-right font-mono">
                                Rp {{ number_format($totalMutasiDebit ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-right font-mono border-r border-zinc-300 dark:border-zinc-600">
                                Rp {{ number_format($totalMutasiKredit ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-right font-mono">
                                Rp {{ number_format($totalSaldoAkhirDebit ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-right font-mono">
                                Rp {{ number_format($totalSaldoAkhirKredit ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                    <h4 class="text-sm font-semibold text-green-900 dark:text-green-100 mb-1">Balance Check</h4>
                    <p class="text-xs text-green-800 dark:text-green-200">
                        @if(abs(($totalSaldoAkhirDebit ?? 0) - ($totalSaldoAkhirKredit ?? 0)) < 0.01)
                            ✓ Neraca saldo balance (Total Debit = Total Kredit)
                        @else
                            ⚠ Selisih: Rp {{ number_format(abs(($totalSaldoAkhirDebit ?? 0) - ($totalSaldoAkhirKredit ?? 0)), 0, ',', '.') }}
                        @endif
                    </p>
                </div>

                <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-1">Total Mutasi</h4>
                    <p class="text-xs text-blue-800 dark:text-blue-200">
                        Debit: Rp {{ number_format($totalMutasiDebit ?? 0, 0, ',', '.') }} | 
                        Kredit: Rp {{ number_format($totalMutasiKredit ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        @else
            <div class="py-12 text-center text-sm text-zinc-600 dark:text-zinc-400">
                <flux:icon.document-magnifying-glass class="mx-auto size-12 text-zinc-400" />
                <p class="mt-4">Tidak ada data untuk periode ini</p>
                <p class="mt-2 text-xs">Pastikan periode sudah dipilih dan ada transaksi yang sudah di-post</p>
            </div>
        @endif
    </flux:card>
</div>
