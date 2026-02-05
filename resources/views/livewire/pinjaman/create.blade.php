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
        <flux:heading size="xl">Tambah Pinjaman</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Tambah pinjaman baru untuk anggota
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <form wire:submit="save" class="space-y-6">
            <flux:select wire:model="anggota_id" label="Anggota" required>
                <option value="">Pilih Anggota</option>
                @foreach($anggota as $a)
                    <option value="{{ $a->id }}">{{ $a->nama }} @if($a->nik) ({{ $a->nik }}) @endif</option>
                @endforeach
            </flux:select>
            <flux:error name="anggota_id" />

            <div>
                <flux:select wire:model="sektor_usaha_id" label="Sektor Usaha">
                    <option value="">— Pilih Sektor Usaha (opsional) —</option>
                    @foreach($sektorUsaha as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="sektor_usaha_id" />
                @if(!$show_new_sektor)
                    <flux:button type="button" wire:click="$set('show_new_sektor', true)" variant="ghost" size="sm" class="mt-1">
                        + Tambah sektor usaha baru
                    </flux:button>
                @else
                    <div class="mt-2 flex flex-wrap items-end gap-2 rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800/50">
                        <flux:input wire:model="new_sektor_nama" placeholder="Nama sektor (mis. Pertanian)" class="min-w-[200px]" />
                        <flux:button type="button" wire:click="addSektorUsaha" variant="primary" size="sm">Tambah</flux:button>
                        <flux:button type="button" wire:click="$set('show_new_sektor', false); $set('new_sektor_nama', '')" variant="ghost" size="sm">Batal</flux:button>
                    </div>
                @endif
            </div>

            <flux:input 
                wire:model="tanggal_pinjaman" 
                type="date"
                label="Tanggal Pinjaman" 
                required
            />
            <flux:error name="tanggal_pinjaman" />

            <flux:input 
                wire:model="jumlah_pinjaman" 
                label="Jumlah Pinjaman (Rp)" 
                type="number"
                step="0.01"
                min="1"
                placeholder="Masukkan jumlah pinjaman"
                required
            />
            <flux:error name="jumlah_pinjaman" />

            <flux:input 
                wire:model="jangka_waktu_bulan" 
                label="Jangka Waktu (Bulan)" 
                type="number"
                min="1"
                placeholder="Masukkan jangka waktu dalam bulan"
                required
            />
            <flux:error name="jangka_waktu_bulan" />

            <flux:input 
                wire:model="jasa_persen" 
                label="Jasa (%)" 
                type="number"
                step="0.01"
                min="0"
                max="100"
                placeholder="Masukkan persentase jasa"
                required
            />
            <flux:error name="jasa_persen" />

            <flux:select wire:model="status_pinjaman" label="Status Pinjaman" required>
                <option value="aktif">Aktif</option>
                <option value="lunas">Lunas</option>
            </flux:select>
            <flux:error name="status_pinjaman" />

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">
                    Simpan
                </flux:button>
                <flux:button 
                    wire:navigate
                    href="{{ route('pinjaman.index') }}"
                    variant="ghost"
                >
                    Batal
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
