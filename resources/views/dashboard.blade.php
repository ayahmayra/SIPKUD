<x-layouts.app :title="__('Dashboard')">
    @if(auth()->user()->isSuperAdmin())
        {{-- Super Admin Dashboard --}}
        <div class="flex h-full w-full flex-1 flex-col gap-6">
            <div>
                <flux:heading size="xl">Dashboard Super Admin PMD</flux:heading>
                <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
                    Sistem Informasi Pelaporan Keuangan USP Desa
                </flux:heading>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <flux:card class="p-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Total Kecamatan</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Kecamatan::count() }}</p>
                    </div>
                </flux:card>

                <flux:card class="p-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Total Desa</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Desa::count() }}</p>
                    </div>
                </flux:card>

                <flux:card class="p-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Total Pengguna</p>
                        <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
                    </div>
                </flux:card>

                <flux:card class="p-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Desa Aktif</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Desa::where('status', 'aktif')->count() }}</p>
                    </div>
                </flux:card>
            </div>

            <flux:card>
                <div class="p-6">
                    <flux:heading size="lg" class="mb-4">Catatan Pengembangan</flux:heading>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        Modul-modul berikut akan dikembangkan di fase selanjutnya:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                        <li>Pinjaman</li>
                        <li>Kas</li>
                        <li>Jurnal (Akuntansi)</li>
                        <li>Aset</li>
                        <li>Pelaporan</li>
                    </ul>
                </div>
            </flux:card>
        </div>
    @else
        {{-- Admin Desa / Executive View Dashboard --}}
        <div class="flex h-full w-full flex-1 flex-col gap-6">
            <div>
                <flux:heading size="xl">Dashboard {{ auth()->user()->desa->nama_desa ?? 'Desa' }}</flux:heading>
                <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
                    @if(auth()->user()->isExecutiveView())
                        Mode Baca Saja (Executive View)
                    @else
                        Sistem Informasi Pelaporan Keuangan USP Desa
                    @endif
                </flux:heading>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <flux:card class="p-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Total Kelompok</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Kelompok::where('desa_id', auth()->user()->desa_id)->count() }}</p>
                    </div>
                </flux:card>

                <flux:card class="p-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Total Anggota</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Anggota::where('desa_id', auth()->user()->desa_id)->count() }}</p>
                    </div>
                </flux:card>

                <flux:card class="p-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Total Akun</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Akun::where('desa_id', auth()->user()->desa_id)->count() }}</p>
                    </div>
                </flux:card>

                <flux:card class="p-6">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Anggota Aktif</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Anggota::where('desa_id', auth()->user()->desa_id)->where('status', 'aktif')->count() }}</p>
                    </div>
                </flux:card>
            </div>

            <flux:card>
                <div class="p-6">
                    <flux:heading size="lg" class="mb-4">Catatan Pengembangan</flux:heading>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        Modul-modul berikut akan dikembangkan di fase selanjutnya:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                        <li>Pinjaman</li>
                        <li>Kas</li>
                        <li>Jurnal (Akuntansi)</li>
                        <li>Aset</li>
                        <li>Pelaporan</li>
                    </ul>
                </div>
            </flux:card>
        </div>
    @endif
</x-layouts.app>
