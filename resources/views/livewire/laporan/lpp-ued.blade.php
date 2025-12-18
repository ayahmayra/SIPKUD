<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl">Laporan LPP UED</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Laporan Pinjaman dan Angsuran USP/UED-SP (Read-Only)
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-1 flex-col gap-4 sm:flex-row">
                <flux:select wire:model.live="bulan" class="w-full sm:w-48">
                    <option value="">Semua Bulan</option>
                    @foreach($bulanList as $key => $nama)
                        <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="tahun" class="w-full sm:w-48">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </flux:select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-2 py-2 text-left text-xs font-semibold w-12">No</th>
                        <th class="px-2 py-2 text-left text-xs font-semibold min-w-[120px]">Nomor Anggota</th>
                        <th class="px-2 py-2 text-left text-xs font-semibold min-w-[150px]">Nama Anggota</th>
                        <th class="px-2 py-2 text-left text-xs font-semibold min-w-[150px]">Nomor Pinjaman</th>
                        <th class="px-2 py-2 text-right text-xs font-semibold w-32">Jumlah Pinjaman</th>
                        <th class="px-2 py-2 text-right text-xs font-semibold w-32">Total Angsuran Pokok</th>
                        <th class="px-2 py-2 text-right text-xs font-semibold w-32">Total Jasa</th>
                        <th class="px-2 py-2 text-right text-xs font-semibold w-32">Sisa Pinjaman</th>
                        <th class="px-2 py-2 text-left text-xs font-semibold w-24">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $index => $item)
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-900">
                            <td class="px-2 py-2 text-xs">{{ $index + 1 }}</td>
                            <td class="px-2 py-2 text-xs">
                                {{ $item['nomor_anggota'] }}
                            </td>
                            <td class="px-2 py-2">
                                <div class="font-medium text-sm">{{ $item['nama_anggota'] }}</div>
                            </td>
                            <td class="px-2 py-2">
                                <div class="font-medium text-sm">{{ $item['nomor_pinjaman'] }}</div>
                            </td>
                            <td class="px-2 py-2 text-right text-xs">
                                Rp {{ number_format($item['jumlah_pinjaman'], 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2 text-right text-xs">
                                Rp {{ number_format($item['total_angsuran_pokok'], 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2 text-right text-xs">
                                Rp {{ number_format($item['total_jasa'], 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2 text-right text-xs font-semibold">
                                Rp {{ number_format($item['sisa_pinjaman'], 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2">
                                <flux:badge size="sm" :variant="$item['status_pinjaman'] === 'aktif' ? 'success' : 'primary'">
                                    {{ ucfirst($item['status_pinjaman']) }}
                                </flux:badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-2 py-8 text-center text-xs text-zinc-600 dark:text-zinc-400">
                                Tidak ada data laporan ditemukan untuk periode yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($laporan->count() > 0)
                    <tfoot>
                        <tr class="border-t-2 border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900">
                            <td colspan="4" class="px-2 py-2 text-xs font-semibold text-right">
                                TOTAL:
                            </td>
                            <td class="px-2 py-2 text-right text-xs font-semibold">
                                Rp {{ number_format($laporan->sum('jumlah_pinjaman'), 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2 text-right text-xs font-semibold">
                                Rp {{ number_format($laporan->sum('total_angsuran_pokok'), 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2 text-right text-xs font-semibold">
                                Rp {{ number_format($laporan->sum('total_jasa'), 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2 text-right text-xs font-semibold">
                                Rp {{ number_format($laporan->sum('sisa_pinjaman'), 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </flux:card>
</div>
