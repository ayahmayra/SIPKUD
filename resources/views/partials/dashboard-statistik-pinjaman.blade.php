@props(['stats', 'sektorList', 'sektorTotal'])

<div class="grid gap-6 lg:grid-cols-2">
{{-- Data Statistik --}}
<flux:card class="p-6">
    <h3 class="text-lg font-semibold mb-4">Data Statistik</h3>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="flex justify-between items-center p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/50">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Persentase Pengembalian</span>
            <span class="font-semibold">{{ $stats['persen_pengembalian'] }}%</span>
        </div>
        <div class="flex justify-between items-center p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/50">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Persentase Tunggakan</span>
            <span class="font-semibold">{{ $stats['persen_tunggakan'] }}%</span>
        </div>
        <div class="flex justify-between items-center p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/50">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">NPL</span>
            <span class="font-semibold">{{ $stats['npl'] }}%</span>
        </div>
    </div>
    <div class="mt-4 space-y-2 border-t border-zinc-200 dark:border-zinc-700 pt-4">
        <div class="flex justify-between items-center">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Jumlah Peminjam</span>
            <span class="font-semibold">{{ number_format($stats['jumlah_peminjam']) }} orang</span>
        </div>
        <div class="flex justify-between items-center pl-4 text-sm">
            <span class="text-zinc-500 dark:text-zinc-400">Laki-laki</span>
            <span>{{ number_format($stats['peminjam_laki']) }} orang</span>
        </div>
        <div class="flex justify-between items-center pl-4 text-sm">
            <span class="text-zinc-500 dark:text-zinc-400">Perempuan</span>
            <span>{{ number_format($stats['peminjam_perempuan']) }} orang</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Peminjam Lunas</span>
            <span class="font-semibold">{{ number_format($stats['peminjam_lunas']) }} orang</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Peminjam Belum Lunas</span>
            <span class="font-semibold">{{ number_format($stats['peminjam_belum_lunas']) }} orang</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Jumlah Tunggakan</span>
            <span class="font-semibold">Rp {{ number_format($stats['jumlah_tunggakan'], 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Jumlah Penunggak</span>
            <span class="font-semibold">{{ number_format($stats['jumlah_penunggak']) }} orang</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Peminjam Jatuh Tempo</span>
            <span class="font-semibold">{{ number_format($stats['peminjam_jatuh_tempo']) }} orang</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Nilai Jatuh Tempo</span>
            <span class="font-semibold">Rp {{ number_format($stats['nilai_jatuh_tempo'], 0, ',', '.') }}</span>
        </div>
    </div>
</flux:card>

{{-- Peminjaman per Jenis Usaha (Sektor) --}}
<flux:card class="p-6 flex flex-col">
    <h3 class="text-lg font-semibold mb-4">Peminjaman per Jenis Usaha</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800/50">
                    <th class="px-4 py-3 text-left font-semibold">Jenis Usaha</th>
                    <th class="px-4 py-3 text-right font-semibold">Orang</th>
                    <th class="px-4 py-3 text-right font-semibold">Rupiah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sektorList as $row)
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                        <td class="px-4 py-3">{{ $row['nama'] }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($row['orang']) }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($row['rupiah'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-zinc-500">Belum ada data pinjaman per sektor.</td>
                    </tr>
                @endforelse
            </tbody>
            @if(count($sektorList) > 0)
                <tfoot>
                    <tr class="border-t-2 border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-800/50 font-semibold">
                        <td class="px-4 py-3">Jumlah</td>
                        <td class="px-4 py-3 text-right">{{ number_format($sektorTotal['orang']) }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($sektorTotal['rupiah'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</flux:card>
</div>
