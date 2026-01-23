<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl">Tambah Unit Usaha</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Tambah unit usaha baru BUM Desa
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <form wire:submit="save" class="space-y-6">
            @if($desas->count() > 1)
            <div x-data="{ 
                search: '', 
                selectedId: @entangle('desa_id'),
                desas: {{ $desas->toJson() }},
                get filteredDesas() {
                    if (!this.search) return this.desas;
                    return this.desas.filter(d => 
                        d.nama_desa.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                selectDesa(id, name) {
                    this.selectedId = id;
                    this.search = name;
                    this.$refs.dropdown.style.display = 'none';
                },
                init() {
                    const selected = this.desas.find(d => d.id === this.selectedId);
                    if (selected) this.search = selected.nama_desa;
                }
            }">
                <label class="block text-sm font-medium mb-1">Desa *</label>
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="search"
                        @focus="$refs.dropdown.style.display = 'block'"
                        @click.away="$refs.dropdown.style.display = 'none'"
                        placeholder="Ketik untuk mencari desa..."
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm"
                        required
                    />
                    <div x-ref="dropdown" style="display: none;" class="absolute z-10 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <template x-for="desa in filteredDesas" :key="desa.id">
                            <div 
                                @click="selectDesa(desa.id, desa.nama_desa)"
                                class="px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 cursor-pointer text-sm"
                                x-text="desa.nama_desa"
                            ></div>
                        </template>
                        <div x-show="filteredDesas.length === 0" class="px-3 py-2 text-sm text-zinc-500">
                            Tidak ada hasil
                        </div>
                    </div>
                </div>
                <flux:error name="desa_id" />
            </div>
            @endif

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
                    Simpan
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
