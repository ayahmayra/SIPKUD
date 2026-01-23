<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl">Laporan Laba Rugi</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Income Statement - Pendapatan dan Beban per periode
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
        <div class="mb-6 grid gap-4 md:grid-cols-6">
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
            <div>
                <flux:select wire:model.live="mode" label="Mode">
                    <option value="bulanan">Bulanan (Mutasi)</option>
                    <option value="kumulatif">Kumulatif (Saldo Akhir)</option>
                </flux:select>
            </div>
            <div class="flex items-end">
                <flux:button 
                    wire:click="exportPdf"
                    variant="ghost"
                    class="w-full"
                >
                    <flux:icon.arrow-down-tray class="size-4" /> Export PDF
                </flux:button>
            </div>
        </div>

        @if(isset($periode))
        <div class="mb-4 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
            <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                Periode: {{ $periode }} | Mode: {{ $mode === 'bulanan' ? 'Bulanan (Mutasi Bulan Berjalan)' : 'Kumulatif (Saldo Akhir)' }}
            </p>
            <p class="text-xs text-blue-800 dark:text-blue-200 mt-1">
                @if($mode === 'bulanan')
                    Menampilkan mutasi pendapatan dan beban untuk bulan {{ $periode }} saja.
                @else
                    Menampilkan saldo akhir kumulatif pendapatan dan beban sampai periode {{ $periode }}.
                @endif
            </p>
        </div>
        @endif

        @if($data)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody>
                        <!-- Pendapatan -->
                        <tr class="border-b-2 border-zinc-300 bg-zinc-100 dark:border-zinc-600 dark:bg-zinc-800">
                            <td colspan="2" class="px-4 py-3 text-sm font-bold">PENDAPATAN</td>
                        </tr>
                        @foreach($data['pendapatan'] as $row)
                            <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                <td class="px-4 py-2 pl-8 text-sm">{{ $row['kode_akun'] }} - {{ $row['nama_akun'] }}</td>
                                <td class="px-4 py-2 text-right font-mono text-sm">
                                    Rp {{ number_format($row['jumlah'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="border-b-2 border-zinc-300 bg-zinc-50 font-semibold dark:border-zinc-600 dark:bg-zinc-900">
                            <td class="px-4 py-3 text-right text-sm">Total Pendapatan</td>
                            <td class="px-4 py-3 text-right font-mono text-sm">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </td>
                        </tr>

                        <!-- Beban -->
                        <tr class="border-b-2 border-zinc-300 bg-zinc-100 dark:border-zinc-600 dark:bg-zinc-800">
                            <td colspan="2" class="px-4 py-3 text-sm font-bold">BEBAN</td>
                        </tr>
                        @foreach($data['beban'] as $row)
                            <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                <td class="px-4 py-2 pl-8 text-sm">{{ $row['kode_akun'] }} - {{ $row['nama_akun'] }}</td>
                                <td class="px-4 py-2 text-right font-mono text-sm">
                                    Rp {{ number_format($row['jumlah'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="border-b-2 border-zinc-300 bg-zinc-50 font-semibold dark:border-zinc-600 dark:bg-zinc-900">
                            <td class="px-4 py-3 text-right text-sm">Total Beban</td>
                            <td class="px-4 py-3 text-right font-mono text-sm">
                                Rp {{ number_format($totalBeban, 0, ',', '.') }}
                            </td>
                        </tr>

                        <!-- Laba/Rugi -->
                        <tr class="border-t-4 border-zinc-400 bg-zinc-200 dark:border-zinc-500 dark:bg-zinc-700">
                            <td class="px-4 py-4 text-right text-base font-bold">
                                {{ ($labaBersih ?? 0) >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono text-base font-bold" 
                                :class="{ 'text-green-600 dark:text-green-400': {{ ($labaBersih ?? 0) >= 0 }}, 'text-red-600 dark:text-red-400': {{ ($labaBersih ?? 0) < 0 }} }">
                                Rp {{ number_format(abs($labaBersih ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center text-sm text-zinc-600 dark:text-zinc-400">
                <flux:icon.document-magnifying-glass class="mx-auto size-12 text-zinc-400" />
                <p class="mt-4">Tidak ada data untuk periode ini</p>
            </div>
        @endif
    </flux:card>
</div>
