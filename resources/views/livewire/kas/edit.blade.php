<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl">Edit Transaksi Kas</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Edit transaksi kas harian (jurnal akan diupdate)
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <form wire:submit="update" class="space-y-6">
            <div>
                <flux:input 
                    wire:model="tanggal_transaksi" 
                    type="date"
                    label="Tanggal Transaksi" 
                    required
                    autofocus
                />
                <flux:error name="tanggal_transaksi" />
            </div>

            <div>
                <flux:select wire:model="unit_usaha_id" label="Unit Usaha" required>
                    <option value="">Pilih Unit Usaha</option>
                    @foreach($unitUsahaList as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="unit_usaha_id" />
            </div>

            <div>
                <flux:select wire:model="akun_kas_id" label="Akun Kas/Bank" required>
                    <option value="">Pilih Akun Kas/Bank</option>
                    @foreach($akunKasList as $akun)
                        <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="akun_kas_id" />
            </div>

            <div>
                <flux:textarea 
                    wire:model="uraian" 
                    label="Uraian" 
                    placeholder="Jelaskan transaksi ini..."
                    rows="3"
                    required
                />
                <flux:error name="uraian" />
            </div>

            <div>
                <flux:select wire:model.live="jenis_transaksi" label="Jenis Transaksi" required>
                    <option value="">Pilih Jenis</option>
                    <option value="masuk">Kas Masuk</option>
                    <option value="keluar">Kas Keluar</option>
                </flux:select>
                <flux:error name="jenis_transaksi" />
            </div>

            <div>
                <flux:input 
                    wire:model="jumlah" 
                    type="number"
                    label="Jumlah (Rp)" 
                    placeholder="0"
                    min="0"
                    step="0.01"
                    required
                />
                <flux:error name="jumlah" />
            </div>

            <div>
                <flux:select wire:model="akun_lawan_id" label="Akun Lawan" required>
                    <option value="">Pilih Akun</option>
                    @foreach($akunLawanList as $tipe => $akunList)
                        <optgroup label="{{ ucfirst($tipe) }}">
                            @foreach($akunList as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </flux:select>
                <flux:text class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                    @if($jenis_transaksi === 'masuk')
                        Pilih akun pendapatan atau akun aset lainnya
                    @else
                        Pilih akun beban atau akun kewajiban
                    @endif
                </flux:text>
                <flux:error name="akun_lawan_id" />
            </div>

            <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                <div class="flex">
                    <flux:icon.information-circle class="size-5 text-blue-600 dark:text-blue-400" />
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Catatan</h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <p>Jurnal terkait akan otomatis diupdate setelah menyimpan perubahan ini.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button 
                    wire:navigate
                    href="{{ route('kas.index') }}" 
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
