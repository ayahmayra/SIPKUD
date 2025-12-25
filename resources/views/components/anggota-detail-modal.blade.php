@props(['show' => false, 'anggota' => null, 'pinjaman' => []])

@if($show && $anggota)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetailModal"></div>

    <!-- Center modal -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <!-- Modal panel -->
        <div class="relative w-full max-w-4xl transform overflow-hidden rounded-lg bg-white shadow-xl transition-all">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                <!-- Header -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Detail Anggota
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Informasi lengkap dan riwayat pinjaman anggota
                        </p>
                    </div>
                    <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Data Anggota -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Informasi Anggota</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600">NIK</label>
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->nik }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Nama Lengkap</label>
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->nama }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Jenis Kelamin</label>
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Nomor HP</label>
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->nomor_hp ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Kelompok</label>
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->kelompok->nama_kelompok ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Desa</label>
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->desa->nama_desa ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Kecamatan</label>
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->desa->kecamatan->nama_kecamatan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Alamat</label>
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pinjaman -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Riwayat Pinjaman ({{ count($pinjaman) }})</h4>
                    @if(count($pinjaman) > 0)
                        <div class="overflow-x-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">No. Pinjaman</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jangka</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jasa</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Terbayar</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Sisa</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pinjaman as $p)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 text-sm text-gray-900">{{ $p['nomor_pinjaman'] }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($p['tanggal_pinjaman'])->format('d/m/Y') }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right text-gray-900">
                                                Rp {{ number_format($p['jumlah_pinjaman'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-center text-gray-900">
                                                {{ $p['jangka_waktu'] }} bulan
                                            </td>
                                            <td class="px-3 py-2 text-sm text-center text-gray-900">
                                                {{ $p['persentase_jasa'] }}%
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right text-gray-900">
                                                Rp {{ number_format($p['total_pokok_dibayar'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right font-semibold text-gray-900">
                                                Rp {{ number_format($p['sisa_pinjaman'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $p['status_pinjaman'] === 'aktif' ? 'bg-green-100 text-green-800' : 
                                                       ($p['status_pinjaman'] === 'lunas' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($p['status_pinjaman']) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 sticky bottom-0">
                                    <tr class="font-semibold">
                                        <td colspan="2" class="px-3 py-2 text-sm text-right text-gray-900">Total:</td>
                                        <td class="px-3 py-2 text-sm text-right text-gray-900">
                                            Rp {{ number_format(collect($pinjaman)->sum('jumlah_pinjaman'), 0, ',', '.') }}
                                        </td>
                                        <td colspan="2"></td>
                                        <td class="px-3 py-2 text-sm text-right text-gray-900">
                                            Rp {{ number_format(collect($pinjaman)->sum('total_pokok_dibayar'), 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-right text-gray-900">
                                            Rp {{ number_format(collect($pinjaman)->sum('sisa_pinjaman'), 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm">Belum ada riwayat pinjaman</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                <button wire:click="closeDetailModal" type="button" 
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Tutup
                </button>
                <button wire:click="exportAnggotaPdf" type="button" 
                    class="mt-3 w-full inline-flex justify-center items-center gap-2 rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:mr-3 sm:w-auto sm:text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>
    </div>
</div>
@endif
