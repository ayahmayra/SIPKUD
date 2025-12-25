<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header -->
                <div class="mb-6 flex justify-between items-start">
                    <div>
                        <flux:heading size="xl">Master Pengumuman</flux:heading>
                        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
                            Kelola pengumuman untuk ditampilkan di dashboard
                        </flux:heading>
                    </div>
                    <flux:button wire:click="create" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Pengumuman
                    </flux:button>
                </div>

                <!-- Flash Message -->
                @if (session()->has('message'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <flux:input 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Cari judul atau isi..."
                            type="search"
                        />
                    </div>
                    <div>
                        <flux:select wire:model.live="tipeFilter">
                            <option value="">Semua Tipe</option>
                            <option value="info">Info</option>
                            <option value="peringatan">Peringatan</option>
                            <option value="penting">Penting</option>
                        </flux:select>
                    </div>
                    <div>
                        <flux:select wire:model.live="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </flux:select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengumuman as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($item->isi, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($item->tipe === 'penting') bg-red-100 text-red-800
                                            @elseif($item->tipe === 'peringatan') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst($item->tipe) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($item->prioritas === 'tinggi') bg-orange-100 text-orange-800
                                            @elseif($item->prioritas === 'sedang') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($item->prioritas) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button 
                                            wire:click="toggleStatus({{ $item->id }})"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $item->aktif ? 'bg-green-600' : 'bg-gray-200' }}">
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $item->aktif ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $item->creator->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="edit({{ $item->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="delete({{ $item->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus pengumuman ini?"
                                            class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada pengumuman ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $pengumuman->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-[9999] overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
                    <!-- Modal Header -->
                    <div class="bg-white px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $editingId ? 'Edit Pengumuman' : 'Buat Pengumuman Baru' }}
                        </h3>
                    </div>

                    <!-- Modal Body -->
                    <form wire:submit="save">
                        <div class="bg-white px-6 py-4 max-h-[70vh] overflow-y-auto">
                            <!-- Judul -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pengumuman <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    wire:model="judul"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Masukkan judul pengumuman">
                                @error('judul') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Isi -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Pengumuman <span class="text-red-500">*</span></label>
                                <textarea 
                                    wire:model="isi"
                                    rows="5"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Masukkan isi pengumuman"></textarea>
                                @error('isi') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Tipe -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
                                    <select 
                                        wire:model="tipe"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="info">Info</option>
                                        <option value="peringatan">Peringatan</option>
                                        <option value="penting">Penting</option>
                                    </select>
                                    @error('tipe') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Prioritas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Prioritas <span class="text-red-500">*</span></label>
                                    <select 
                                        wire:model="prioritas"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="rendah">Rendah</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="tinggi">Tinggi</option>
                                    </select>
                                    @error('prioritas') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Status Aktif -->
                            <div class="mb-4 p-4 bg-gray-50 rounded-md">
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        wire:model="aktif"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-4 w-4">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Aktifkan pengumuman</span>
                                </label>
                                <p class="mt-2 text-xs text-gray-500">Jika dicentang, pengumuman akan ditampilkan di dashboard semua pengguna.</p>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ $editingId ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
