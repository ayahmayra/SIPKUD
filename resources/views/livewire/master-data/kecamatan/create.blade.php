<div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Tambah Kecamatan</flux:heading>
            <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
                Tambah data kecamatan baru
            </flux:heading>
        </div>

        <flux:card class="p-6">
            <form wire:submit="save" class="space-y-6">
                <flux:input 
                    wire:model="nama_kecamatan" 
                    label="Nama Kecamatan" 
                    placeholder="Masukkan nama kecamatan"
                    required
                    autofocus
                />
                <flux:error name="nama_kecamatan" />

                <flux:input 
                    wire:model="kode_kecamatan" 
                    label="Kode Kecamatan" 
                    placeholder="Contoh: KEC001"
                    required
                />
                <flux:error name="kode_kecamatan" />

                <flux:select wire:model="status" label="Status" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </flux:select>
                <flux:error name="status" />

                <div class="flex items-center gap-4">
                    <flux:button type="submit" variant="primary">
                        Simpan
                    </flux:button>
                    <flux:button 
                        :href="route('kecamatan.index')" 
                        variant="ghost"
                        wire:navigate
                    >
                        Batal
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
