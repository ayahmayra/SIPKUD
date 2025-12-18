<div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Edit Desa</flux:heading>
            <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
                Edit data desa: {{ $desa->nama_desa }}
            </flux:heading>
        </div>

        <flux:card class="p-6">
            <form wire:submit="update" class="space-y-6">
                <flux:select wire:model="kecamatan_id" label="Kecamatan" required>
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="kecamatan_id" />

                <flux:input 
                    wire:model="nama_desa" 
                    label="Nama Desa" 
                    placeholder="Masukkan nama desa"
                    required
                    autofocus
                />
                <flux:error name="nama_desa" />

                <flux:input 
                    wire:model="kode_desa" 
                    label="Kode Desa" 
                    placeholder="Contoh: DES001"
                    required
                />
                <flux:error name="kode_desa" />

                <flux:select wire:model="status" label="Status" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </flux:select>
                <flux:error name="status" />

                <div class="flex items-center gap-4">
                    <flux:button type="submit" variant="primary">
                        Perbarui
                    </flux:button>
                    <flux:button 
                        :href="route('desa.index')" 
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
