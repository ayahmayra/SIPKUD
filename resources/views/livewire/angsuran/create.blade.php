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
        <flux:heading size="xl">Tambah Angsuran</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Catat pembayaran angsuran pinjaman
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <form wire:submit="save" class="space-y-6">
            <flux:select wire:model.live="pinjaman_id" label="Pinjaman" required>
                <option value="">Pilih Pinjaman</option>
                @foreach($pinjaman as $p)
                    @php
                        $sisaPinjaman = $p->sisa_pinjaman;
                    @endphp
                    <option value="{{ $p->id }}">
                        {{ $p->nomor_pinjaman }} - {{ $p->anggota->nama }} 
                        (Sisa: Rp {{ number_format($sisaPinjaman, 0, ',', '.') }})
                    </option>
                @endforeach
            </flux:select>
            <flux:error name="pinjaman_id" />

            <flux:input 
                wire:model="tanggal_bayar" 
                type="date"
                label="Tanggal Bayar" 
                required
            />
            <flux:error name="tanggal_bayar" />

            <flux:input 
                wire:model="angsuran_ke" 
                label="Angsuran Ke" 
                type="number"
                min="1"
                placeholder="Masukkan angsuran ke berapa"
                required
            />
            <flux:error name="angsuran_ke" />

            <flux:input 
                wire:model.live="pokok_dibayar" 
                label="Pokok Dibayar (Rp)" 
                type="number"
                step="0.01"
                min="0"
                placeholder="Masukkan jumlah pokok yang dibayar"
                required
            />
            <flux:error name="pokok_dibayar" />

            <flux:input 
                wire:model.live="jasa_dibayar" 
                label="Jasa Dibayar (Rp)" 
                type="number"
                step="0.01"
                min="0"
                placeholder="Masukkan jumlah jasa yang dibayar"
                required
            />
            <flux:error name="jasa_dibayar" />

            <flux:input 
                wire:model.live="denda_dibayar" 
                label="Denda Dibayar (Rp)" 
                type="number"
                step="0.01"
                min="0"
                placeholder="Masukkan jumlah denda yang dibayar"
                required
            />
            <flux:error name="denda_dibayar" />

            <flux:input 
                wire:model="total_dibayar" 
                label="Total Dibayar (Rp)" 
                type="number"
                step="0.01"
                disabled
                readonly
            />
            <flux:text class="text-xs text-zinc-500">
                Total dibayar dihitung otomatis dari pokok + jasa + denda
            </flux:text>

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">
                    Simpan
                </flux:button>
                <flux:button 
                    wire:navigate
                    href="{{ route('angsuran.index') }}"
                    variant="ghost"
                >
                    Batal
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
