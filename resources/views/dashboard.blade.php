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

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Total Kecamatan -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Kecamatan</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Kecamatan::where('status', 'aktif')->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Kecamatan aktif di sistem</p>
                </div>

                <!-- Total Desa -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Desa</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Desa::where('status', 'aktif')->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Desa aktif di sistem</p>
                </div>

                <!-- Total Kelompok -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Kelompok</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Kelompok::count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Kelompok terdaftar</p>
                </div>

                <!-- Total Anggota -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Anggota</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Anggota::count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Anggota terdaftar</p>
                </div>
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
    @elseif(auth()->user()->isAdminKecamatan())
        {{-- Admin Kecamatan Dashboard --}}
        <div class="flex h-full w-full flex-1 flex-col gap-6">
            <div>
                <flux:heading size="xl">Dashboard Admin Kecamatan {{ auth()->user()->kecamatan->nama_kecamatan ?? 'Kecamatan' }}</flux:heading>
                <flux:heading size="sm" class="mt-2 text-zinc-600 dark:text-zinc-400">
                    Sistem Informasi Pelaporan Keuangan USP Desa
                </flux:heading>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Total Desa -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Desa</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Desa::where('kecamatan_id', auth()->user()->kecamatan_id)->where('status', 'aktif')->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Desa aktif di kecamatan</p>
                </div>

                <!-- Total Kelompok -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Kelompok</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Kelompok::whereHas('desa', function($q) { $q->where('kecamatan_id', auth()->user()->kecamatan_id); })->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Kelompok terdaftar</p>
                </div>

                <!-- Total Anggota -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Anggota</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Anggota::whereHas('desa', function($q) { $q->where('kecamatan_id', auth()->user()->kecamatan_id); })->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Anggota terdaftar</p>
                </div>

                <!-- Total Admin Desa -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Admin Desa</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\User::where('kecamatan_id', auth()->user()->kecamatan_id)->where('role', 'admin_desa')->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Admin desa aktif</p>
                </div>
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

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Total Kelompok -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Kelompok</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Kelompok::where('desa_id', auth()->user()->desa_id)->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Kelompok terdaftar</p>
                </div>

                <!-- Total Anggota -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Total Anggota</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Anggota::where('desa_id', auth()->user()->desa_id)->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Anggota terdaftar</p>
                </div>

                <!-- Kelompok Aktif -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Kelompok Aktif</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Kelompok::where('desa_id', auth()->user()->desa_id)->where('status', 'aktif')->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Kelompok dengan status aktif</p>
                </div>

                <!-- Anggota Aktif -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium opacity-90">Anggota Aktif</h3>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold">{{ \App\Models\Anggota::where('desa_id', auth()->user()->desa_id)->where('status', 'aktif')->count() }}</p>
                    <p class="text-xs opacity-80 mt-1">Anggota dengan status aktif</p>
                </div>
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
