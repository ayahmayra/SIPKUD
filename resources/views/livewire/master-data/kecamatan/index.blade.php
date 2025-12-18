<div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Master Kecamatan</flux:heading>
                <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
                    Kelola data kecamatan untuk mengelompokkan desa
                </flux:heading>
            </div>
            <flux:button :href="route('kecamatan.create')" variant="primary" wire:navigate>
                <flux:icon.plus class="size-4" />
                Tambah Kecamatan
            </flux:button>
        </div>

        <flux:card class="p-6">
            <div class="mb-4 flex flex-col gap-4 sm:flex-row">
                <div class="flex-1">
                    <flux:input 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama atau kode kecamatan..."
                        icon="magnifying-glass"
                    />
                </div>
                <div class="w-full sm:w-48">
                    <flux:select wire:model.live="statusFilter" placeholder="Filter Status">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </flux:select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="px-4 py-3 text-left text-sm font-semibold">No</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Kode</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Nama Kecamatan</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Jumlah Desa</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kecamatan as $item)
                            <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-900">
                                <td class="px-4 py-3 text-sm">{{ $kecamatan->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3 text-sm font-mono">{{ $item->kode_kecamatan }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item->nama_kecamatan }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <flux:badge :variant="$item->status === 'aktif' ? 'success' : 'danger'">
                                        {{ ucfirst($item->status) }}
                                    </flux:badge>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $item->desa_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button 
                                            :href="route('kecamatan.edit', $item->id)" 
                                            variant="ghost" 
                                            size="sm"
                                            wire:navigate
                                        >
                                            <flux:icon.pencil class="size-4" />
                                        </flux:button>
                                        <flux:button 
                                            wire:click="delete({{ $item->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus kecamatan ini?"
                                            variant="ghost" 
                                            size="sm"
                                            class="text-red-600 hover:text-red-700 dark:text-red-400"
                                        >
                                            <flux:icon.trash class="size-4" />
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-zinc-600 dark:text-zinc-400">
                                    Tidak ada data kecamatan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kecamatan->hasPages())
                <div class="mt-4">
                    {{ $kecamatan->links() }}
                </div>
            @endif
        </flux:card>
    </div>
</div>
