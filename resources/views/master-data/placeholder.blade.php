<x-layouts.app :title="$module">
    <div class="flex h-full w-full flex-1 flex-col items-center justify-center gap-6 rounded-xl">
        <div class="max-w-md text-center">
            <flux:heading size="xl" class="mb-4">{{ $module }}</flux:heading>
            <p class="mb-6 text-zinc-600 dark:text-zinc-400">
                {{ $description }}
            </p>
            
            <flux:card class="p-6">
                <div class="space-y-4">
                    <div>
                        <flux:heading size="sm" class="mb-2">Status Pengembangan</flux:heading>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            Modul ini akan dikembangkan di fase selanjutnya dari proyek SIPKUD.
                        </p>
                    </div>
                    
                    <div>
                        <flux:heading size="sm" class="mb-2">Modul yang Akan Datang</flux:heading>
                        <ul class="list-disc list-inside space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                            <li>Pinjaman</li>
                            <li>Kas</li>
                            <li>Jurnal (Akuntansi)</li>
                            <li>Aset</li>
                            <li>Pelaporan</li>
                        </ul>
                    </div>
                </div>
            </flux:card>

            <div class="mt-6">
                <flux:button :href="route('dashboard')" variant="primary" wire:navigate>
                    Kembali ke Dashboard
                </flux:button>
            </div>
        </div>
    </div>
</x-layouts.app>

