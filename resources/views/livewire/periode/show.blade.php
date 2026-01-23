<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Detail Neraca Saldo - {{ $periodeName }}</flux:heading>
            <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
                Saldo awal, mutasi, dan saldo akhir per akun
            </flux:heading>
        </div>
        <div class="flex items-center gap-2">
            @if($statusPeriode === 'closed')
                <flux:badge variant="danger">
                    <flux:icon.lock-closed class="size-3 mr-1" />
                    Periode Closed
                </flux:badge>
            @else
                <flux:badge variant="success">
                    <flux:icon.lock-open class="size-3 mr-1" />
                    Periode Open
                </flux:badge>
            @endif
            <flux:button 
                wire:navigate 
                href="{{ route('periode.index') }}" 
                variant="ghost"
            >
                <flux:icon.arrow-left class="size-4 mr-2" />
                Kembali
            </flux:button>
        </div>
    </div>

    <flux:card class="p-6">
        <div class="mb-6">
            <flux:select wire:model.live="unitUsahaId" label="Filter Unit Usaha" class="w-full sm:w-64">
                <option value="">Semua Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                @endforeach
            </flux:select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-zinc-300 dark:border-zinc-600">
                        <th class="px-4 py-3 text-left font-semibold">Kode</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Akun</th>
                        <th class="px-4 py-3 text-right font-semibold">Saldo Awal (D)</th>
                        <th class="px-4 py-3 text-right font-semibold">Saldo Awal (K)</th>
                        <th class="px-4 py-3 text-right font-semibold">Mutasi (D)</th>
                        <th class="px-4 py-3 text-right font-semibold">Mutasi (K)</th>
                        <th class="px-4 py-3 text-right font-semibold">Saldo Akhir (D)</th>
                        <th class="px-4 py-3 text-right font-semibold">Saldo Akhir (K)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $tipeAkun = ['aset' => 'ASET', 'kewajiban' => 'KEWAJIBAN', 'ekuitas' => 'EKUITAS', 'pendapatan' => 'PENDAPATAN', 'beban' => 'BEBAN'];
                    @endphp

                    @foreach($tipeAkun as $key => $label)
                        @if($grouped->has($key))
                            <tr class="bg-zinc-100 dark:bg-zinc-800">
                                <td colspan="8" class="px-4 py-2 font-bold">{{ $label }}</td>
                            </tr>
                            @foreach($grouped[$key] as $item)
                                <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <td class="px-4 py-2 font-mono">{{ $item->akun->kode_akun }}</td>
                                    <td class="px-4 py-2">{{ $item->akun->nama_akun }}</td>
                                    <td class="px-4 py-2 text-right font-mono">
                                        {{ number_format($item->saldo_awal_debit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono">
                                        {{ number_format($item->saldo_awal_kredit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono">
                                        {{ number_format($item->mutasi_debit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono">
                                        {{ number_format($item->mutasi_kredit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono">
                                        {{ number_format($item->saldo_akhir_debit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono">
                                        {{ number_format($item->saldo_akhir_kredit, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach

                    @if($neracaSaldo->isEmpty())
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-zinc-500">
                                Tidak ada data neraca saldo untuk periode ini.
                            </td>
                        </tr>
                    @endif
                </tbody>
                @if($neracaSaldo->isNotEmpty())
                <tfoot>
                    <tr class="border-t-2 border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-800 font-bold">
                        <td colspan="2" class="px-4 py-3 text-right">TOTAL</td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ number_format($totalSaldoAwalDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ number_format($totalSaldoAwalKredit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ number_format($totalMutasiDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ number_format($totalMutasiKredit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ number_format($totalSaldoAkhirDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ number_format($totalSaldoAkhirKredit, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        @if($neracaSaldo->isNotEmpty())
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <h4 class="text-sm font-semibold text-green-900 dark:text-green-100 mb-1">Balance Check</h4>
                <p class="text-xs text-green-800 dark:text-green-200">
                    @if(abs($totalSaldoAkhirDebit - $totalSaldoAkhirKredit) < 0.01)
                        ✓ Neraca saldo balance (Debit = Kredit)
                    @else
                        ⚠ Selisih: Rp {{ number_format(abs($totalSaldoAkhirDebit - $totalSaldoAkhirKredit), 0, ',', '.') }}
                    @endif
                </p>
            </div>

            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-1">Total Mutasi</h4>
                <p class="text-xs text-blue-800 dark:text-blue-200">
                    Debit: Rp {{ number_format($totalMutasiDebit, 0, ',', '.') }} | 
                    Kredit: Rp {{ number_format($totalMutasiKredit, 0, ',', '.') }}
                </p>
            </div>
        </div>
        @endif
    </flux:card>
</div>
