<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl">Edit Pengguna</flux:heading>
        <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
            Edit data pengguna: {{ $user->nama }}
        </flux:heading>
    </div>

    <flux:card class="p-6">
        <form wire:submit="update" class="space-y-6">
            <flux:input 
                wire:model="nama" 
                label="Nama" 
                placeholder="Masukkan nama lengkap"
                required
                autofocus
            />
            <flux:error name="nama" />

            <flux:input 
                wire:model="email" 
                label="Email" 
                type="email"
                placeholder="email@example.com"
                required
            />
            <flux:error name="email" />

            <div>
                <flux:input 
                    wire:model="password" 
                    label="Password Baru (kosongkan jika tidak ingin mengubah)" 
                    type="password"
                    placeholder="Minimal 8 karakter"
                />
                <flux:text class="mt-1 text-xs text-zinc-500">
                    Kosongkan jika tidak ingin mengubah password
                </flux:text>
                <flux:error name="password" />
            </div>

            @if($password)
                <flux:input 
                    wire:model="password_confirmation" 
                    label="Konfirmasi Password Baru" 
                    type="password"
                    placeholder="Ulangi password baru"
                />
                <flux:error name="password_confirmation" />
            @endif

            <flux:select wire:model.live="role" label="Role" required {{ $isAdminKecamatan ? 'disabled' : '' }}>
                @if(!$isAdminKecamatan)
                    <option value="super_admin">Super Admin</option>
                    <option value="admin_kecamatan">Admin Kecamatan</option>
                @endif
                <option value="admin_desa">Admin Desa</option>
                @if(!$isAdminKecamatan)
                    <option value="executive_view">Executive View</option>
                @endif
            </flux:select>
            <flux:error name="role" />
            @if($isAdminKecamatan)
                <flux:text class="text-xs text-zinc-500">
                    Anda hanya dapat mengubah menjadi Admin Desa
                </flux:text>
            @endif

            @if($role !== 'super_admin')
                <flux:select wire:model.live="kecamatan_id" label="Kecamatan" required {{ $isAdminKecamatan ? 'disabled' : '' }}>
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="kecamatan_id" />
                @if($isAdminKecamatan)
                    <flux:text class="text-xs text-zinc-500">
                        Kecamatan sudah ditetapkan berdasarkan akun Anda
                    </flux:text>
                @endif

                @if($kecamatan_id && $role !== 'admin_kecamatan')
                    <flux:select wire:model="desa_id" label="Desa" required>
                        <option value="">Pilih Desa</option>
                        @foreach($desa as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_desa }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="desa_id" />
                @endif
            @endif

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">
                    Perbarui
                </flux:button>
                <flux:button 
                    :href="route('pengguna.index')" 
                    variant="ghost"
                    wire:navigate
                >
                    Batal
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
