<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl">Edit Unit Usaha</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Edit data unit usaha BUM Desa
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <form wire:submit="update" class="space-y-6">
            <div>
                <flux:input 
                    wire:model="kode_unit" 
                    label="Kode Unit" 
                    placeholder="Contoh: USP, UMUM"
                    required
                    autofocus
                />
                <flux:text class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                    Kode unit harus unik untuk desa ini
                </flux:text>
                <flux:error name="kode_unit" />
            </div>

            <div>
                <flux:input 
                    wire:model="nama_unit" 
                    label="Nama Unit" 
                    placeholder="Masukkan nama unit usaha"
                    required
                />
                <flux:error name="nama_unit" />
            </div>

            <div>
                <flux:select wire:model="status" label="Status" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </flux:select>
                <flux:error name="status" />
            </div>

            <div class="flex justify-end gap-3">
                <flux:button 
                    wire:navigate
                    href="{{ route('unit-usaha.index') }}" 
                    variant="ghost"
                    type="button"
                >
                    Batal
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Update
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
