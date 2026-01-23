<div class="flex h-full w-full flex-1 flex-col gap-6" x-data="{ showErrors: @entangle('showValidationErrors') }">
    <div>
        <flux:heading size="xl">Tambah Jurnal Memorial</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Buat jurnal memorial untuk transaksi non-kas (double-entry)
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Header Jurnal -->
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <flux:input 
                        wire:model="tanggal_transaksi" 
                        type="date"
                        label="Tanggal Jurnal" 
                        required
                        autofocus
                    />
                    <flux:error name="tanggal_transaksi" />
                </div>

                <div>
                    <flux:select wire:model="unit_usaha_id" label="Unit Usaha">
                        <option value="">Pilih Unit Usaha</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="unit_usaha_id" />
                </div>
            </div>

            <div>
                <flux:textarea 
                    wire:model="keterangan" 
                    label="Keterangan Jurnal" 
                    placeholder="Jelaskan jurnal ini..."
                    rows="2"
                    required
                />
                <flux:error name="keterangan" />
            </div>

            <!-- Detail Jurnal -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <flux:heading size="lg">Detail Jurnal</flux:heading>
                    <flux:button 
                        type="button"
                        wire:click="addRow"
                        variant="ghost"
                        size="sm"
                    >
                        <flux:icon.plus class="size-4" /> Tambah Baris
                    </flux:button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="px-2 py-2 text-left text-sm font-semibold">Akun</th>
                            <th class="px-2 py-2 text-left text-sm font-semibold">Posisi</th>
                            <th class="px-2 py-2 text-right text-sm font-semibold">Jumlah</th>
                            <th class="px-2 py-2 text-left text-sm font-semibold">Keterangan</th>
                            <th class="px-2 py-2 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $index => $detail)
                                <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                    <td class="px-2 py-2">
                                        <flux:select wire:model="details.{{ $index }}.akun_id" required>
                                            <option value="">Pilih Akun</option>
                                            @foreach($akunList as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                            @endforeach
                                        </flux:select>
                                        <flux:error name="details.{{ $index }}.akun_id" />
                                    </td>
                                    <td class="px-2 py-2">
                                        <flux:select wire:model="details.{{ $index }}.posisi" required class="w-24">
                                            <option value="debit">Debit</option>
                                            <option value="kredit">Kredit</option>
                                        </flux:select>
                                    </td>
                                    <td class="px-2 py-2">
                                        <flux:input 
                                            wire:model.live="details.{{ $index }}.jumlah" 
                                            type="number"
                                            placeholder="0"
                                            min="0"
                                            step="0.01"
                                        />
                                        <flux:error name="details.{{ $index }}.jumlah" />
                                    </td>
                                    <td class="px-2 py-2">
                                        <flux:input 
                                            wire:model="details.{{ $index }}.keterangan" 
                                            placeholder="Keterangan detail..."
                                        />
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        @if(count($details) > 2)
                                            <flux:button 
                                                type="button"
                                                wire:click="removeRow({{ $index }})"
                                                variant="ghost"
                                                size="sm"
                                                class="text-red-600 hover:text-red-700"
                                            >
                                                <flux:icon.trash class="size-4" />
                                            </flux:button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-zinc-300 dark:border-zinc-600 font-semibold">
                                <td colspan="2" class="px-2 py-3 text-right">Total Debit:</td>
                                <td class="px-2 py-3 text-right font-mono" colspan="3">
                                    Rp {{ number_format($this->totalDebit, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="border-t border-zinc-200 dark:border-zinc-700 font-semibold">
                                <td colspan="2" class="px-2 py-3 text-right">Total Kredit:</td>
                                <td class="px-2 py-3 text-right font-mono" colspan="3">
                                    Rp {{ number_format($this->totalKredit, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if($this->totalDebit != $this->totalKredit)
                                <tr>
                                    <td colspan="5" class="px-2 py-2">
                                        <flux:callout variant="warning" icon="exclamation-triangle">
                                            <strong>Tidak Balance!</strong> Total Debit dan Kredit harus sama. Selisih: Rp {{ number_format(abs($this->totalDebit - $this->totalKredit), 0, ',', '.') }}
                                        </flux:callout>
                                    </td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                <div class="flex">
                    <flux:icon.information-circle class="size-5 text-blue-600 dark:text-blue-400" />
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Prinsip Double-Entry</h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <ul class="list-inside list-disc space-y-1">
                                <li>Total Debit HARUS sama dengan Total Kredit</li>
                                <li>Minimal 2 baris detail (1 debit dan 1 kredit)</li>
                                <li>Pastikan akun yang dipilih sudah tepat</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button 
                    wire:navigate
                    href="{{ route('memorial.index') }}" 
                    variant="ghost"
                    type="button"
                >
                    Batal
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Simpan
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
