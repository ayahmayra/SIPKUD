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
        <flux:heading size="xl">Edit Kelompok</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Edit data kelompok
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <form wire:submit="update" class="space-y-6">
            <flux:input 
                wire:model="nama_kelompok" 
                label="Nama Kelompok" 
                placeholder="Masukkan nama kelompok"
                required
                autofocus
            />
            <flux:error name="nama_kelompok" />

            <flux:textarea 
                wire:model="keterangan" 
                label="Keterangan" 
                placeholder="Masukkan keterangan (opsional)"
                rows="3"
            />
            <flux:error name="keterangan" />

            <flux:select wire:model="status" label="Status" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </flux:select>
            <flux:error name="status" />

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">
                    Simpan Perubahan
                </flux:button>
                <flux:button 
                    wire:navigate
                    href="{{ route('kelompok.index') }}"
                    variant="ghost"
                >
                    Batal
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>

