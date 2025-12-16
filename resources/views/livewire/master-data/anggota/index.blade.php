<div class="flex h-full w-full flex-1 flex-col gap-6" 
     x-data="{ 
         showSuccess: false, 
         showError: false, 
         successMessage: '', 
         errorMessage: '' 
     }"
     x-init="
         $wire.on('success', (event) => {
             successMessage = event.message || 'Berhasil!';
             showSuccess = true;
             setTimeout(() => showSuccess = false, 3000);
         });
         $wire.on('error', (event) => {
             errorMessage = event.message || 'Terjadi kesalahan!';
             showError = true;
             setTimeout(() => showError = false, 5000);
         });
     ">
    <!-- Success Notification -->
    <div x-show="showSuccess" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-sm">
        <flux:callout variant="success" icon="check-circle">
            <span x-text="successMessage"></span>
        </flux:callout>
    </div>

    <!-- Error Notification -->
    <div x-show="showError" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-sm">
        <flux:callout variant="danger" icon="x-circle">
            <span x-text="errorMessage"></span>
        </flux:callout>
    </div>

    <div>
        <flux:heading size="xl">Master Anggota</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Kelola data anggota USP/UED-SP
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-1 flex-col gap-4 sm:flex-row">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari nama atau alamat..."
                    class="w-full sm:w-64"
                />
                <flux:select wire:model.live="kelompokFilter" class="w-full sm:w-48">
                    <option value="">Semua Kelompok</option>
                    @foreach($kelompok as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelompok }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="statusFilter" class="w-full sm:w-48">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </flux:select>
            </div>
            @if(auth()->user()->isAdminDesa() || auth()->user()->isSuperAdmin())
                <flux:button 
                    wire:navigate 
                    href="{{ route('anggota.create') }}" 
                    variant="primary"
                >
                    Tambah Anggota
                </flux:button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-4 py-3 text-left text-sm font-semibold">No</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Kelompok</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Alamat</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Tanggal Gabung</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $item)
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-900">
                            <td class="px-4 py-3 text-sm">{{ $anggota->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="font-medium">{{ $item->nama }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <flux:badge>{{ $item->kelompok->nama_kelompok ?? '-' }}</flux:badge>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="max-w-xs truncate text-zinc-600 dark:text-zinc-400">
                                    {{ $item->alamat ?? '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->tanggal_gabung ? $item->tanggal_gabung->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <flux:badge :variant="$item->status === 'aktif' ? 'success' : 'danger'">
                                    {{ ucfirst($item->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if(auth()->user()->isAdminDesa() || auth()->user()->isSuperAdmin())
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button 
                                            wire:navigate
                                            :href="route('anggota.edit', $item->id)"
                                            variant="ghost"
                                            size="sm"
                                        >
                                            <flux:icon.pencil class="size-4" />
                                        </flux:button>
                                        <flux:button 
                                            wire:click="delete({{ $item->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus anggota ini?"
                                            variant="ghost"
                                            size="sm"
                                            class="text-red-600 hover:text-red-700 dark:text-red-400"
                                        >
                                            <flux:icon.trash class="size-4" />
                                        </flux:button>
                                    </div>
                                @else
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-zinc-600 dark:text-zinc-400">
                                Tidak ada data anggota ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($anggota->hasPages())
            <div class="mt-4">
                {{ $anggota->links() }}
            </div>
        @endif
    </flux:card>
</div>

