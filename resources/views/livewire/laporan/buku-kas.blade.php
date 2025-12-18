<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Buku Kas USP</h2>
                </div>

                <!-- Filter Bulan dan Tahun -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                        <select wire:model.live="bulan" id="bulan" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Semua Bulan --</option>
                            @foreach ($bulanList as $item)
                                <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select wire:model.live="tahun" id="tahun" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach ($tahunList as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Saldo Awal -->
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Saldo Awal:</span>
                        <span class="text-lg font-bold text-blue-700">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tabel Buku Kas -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Uraian Transaksi
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kas Masuk
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kas Keluar
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo Kas
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transaksi as $index => $item)
                                <tr class="{{ $item->jenis_transaksi === 'masuk' ? 'bg-green-50' : 'bg-red-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->tanggal_transaksi->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $item->uraian }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $item->jenis_transaksi === 'masuk' ? 'text-green-700 font-semibold' : 'text-gray-400' }}">
                                        @if ($item->jenis_transaksi === 'masuk')
                                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $item->jenis_transaksi === 'keluar' ? 'text-red-700 font-semibold' : 'text-gray-400' }}">
                                        @if ($item->jenis_transaksi === 'keluar')
                                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">
                                        Rp {{ number_format($item->saldo_berjalan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada transaksi pada periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($transaksi->count() > 0)
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-900">
                                        Total
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-bold text-green-700">
                                        Rp {{ number_format($transaksi->where('jenis_transaksi', 'masuk')->sum('jumlah'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-bold text-red-700">
                                        Rp {{ number_format($transaksi->where('jenis_transaksi', 'keluar')->sum('jumlah'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">
                                        Rp {{ number_format($transaksi->last()->saldo_berjalan ?? $saldoAwal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <!-- Keterangan -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Keterangan:</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <span class="font-medium">Kas Keluar</span>: Otomatis tercatat saat pinjaman dibuat (pencairan)</li>
                        <li>• <span class="font-medium">Kas Masuk</span>: Otomatis tercatat dari pembayaran angsuran</li>
                        <li>• <span class="font-medium">Saldo Kas Berjalan</span>: Dihitung otomatis dari transaksi</li>
                        <li>• Buku kas ini bersifat <span class="font-semibold">READ-ONLY</span>, tidak ada input manual</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
