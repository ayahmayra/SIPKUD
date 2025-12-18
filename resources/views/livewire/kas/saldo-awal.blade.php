<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold mb-2">Saldo Awal Kas</h2>
                    <p class="text-sm text-gray-600">
                        Input saldo kas dari sistem manual (Excel) sebelumnya sebagai saldo awal sistem.
                    </p>
                </div>

                <!-- Alert Info -->
                @if ($saldoAwalExists)
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-semibold text-blue-800">Saldo Awal Sudah Tersimpan</h3>
                                <p class="text-sm text-blue-700 mt-1">
                                    Anda dapat mengubah saldo awal jika ada kesalahan input. Perubahan ini akan mempengaruhi seluruh laporan kas.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-semibold text-yellow-800">Belum Ada Saldo Awal</h3>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Silakan input saldo kas terakhir dari sistem manual (Excel) Anda. Saldo ini akan menjadi titik awal perhitungan kas di sistem baru.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form wire:submit="save">
                    <div class="space-y-6">
                        <!-- Tanggal Saldo Awal -->
                        <div>
                            <label for="tanggal_saldo_awal" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Saldo Awal <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="tanggal_saldo_awal" 
                                wire:model="tanggal_saldo_awal"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tanggal_saldo_awal') border-red-500 @enderror"
                            >
                            @error('tanggal_saldo_awal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Tanggal saat Anda mulai menggunakan sistem baru (biasanya tanggal migrasi dari Excel)
                            </p>
                        </div>

                        <!-- Jumlah Saldo Awal -->
                        <div>
                            <label for="jumlah_saldo_awal" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Saldo Awal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input 
                                    type="number" 
                                    id="jumlah_saldo_awal" 
                                    wire:model="jumlah_saldo_awal"
                                    step="0.01"
                                    class="w-full pl-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('jumlah_saldo_awal') border-red-500 @enderror"
                                    placeholder="0"
                                >
                            </div>
                            @error('jumlah_saldo_awal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Saldo kas terakhir dari sistem manual (Excel). Bisa positif atau negatif.
                            </p>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="keterangan" 
                                wire:model="keterangan"
                                rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('keterangan') border-red-500 @enderror"
                                placeholder="Contoh: Saldo awal kas dari sistem Excel per 31 Desember 2024"
                            ></textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Penting -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">⚠️ Penting:</h4>
                            <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                                <li>Saldo awal hanya perlu diinput <strong>sekali</strong> saat pertama kali migrasi dari sistem manual</li>
                                <li>Pastikan jumlah sesuai dengan saldo terakhir di sistem Excel Anda</li>
                                <li>Saldo awal akan digunakan sebagai dasar perhitungan semua laporan kas</li>
                                <li>Jika salah input, Anda bisa mengubahnya melalui form ini</li>
                            </ul>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex items-center gap-4">
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors"
                            >
                                {{ $saldoAwalExists ? 'Update Saldo Awal' : 'Simpan Saldo Awal' }}
                            </button>
                            
                            <a 
                                href="{{ route('laporan.buku-kas') }}" 
                                wire:navigate
                                class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
                            >
                                Kembali ke Buku Kas
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
