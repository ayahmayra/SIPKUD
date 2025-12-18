<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $pengaturan = \App\Models\Pengaturan::getSettings();
            $title = $pengaturan->base_title ?? ($pengaturan->nama_instansi . ' - SIPKUD');
        @endphp

        <title>{{ $title }}</title>

        @if($pengaturan->favicon)
            <link rel="icon" href="{{ asset('storage/' . $pengaturan->favicon) }}" type="image/x-icon">
        @endif

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen antialiased">
        {{-- Background Gradient --}}
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-zinc-900 dark:via-blue-950 dark:to-indigo-950"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(120,119,198,0.15),transparent_50%)] dark:bg-[radial-gradient(circle_at_30%_20%,rgba(99,102,241,0.15),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_80%,rgba(139,92,246,0.15),transparent_50%)] dark:bg-[radial-gradient(circle_at_70%_80%,rgba(139,92,246,0.15),transparent_50%)]"></div>
            {{-- Animated gradient orbs --}}
            <div class="absolute top-0 -left-4 h-72 w-72 rounded-full bg-blue-300 opacity-20 blur-3xl dark:bg-blue-500 dark:opacity-10 animate-pulse"></div>
            <div class="absolute bottom-0 -right-4 h-72 w-72 rounded-full bg-purple-300 opacity-20 blur-3xl dark:bg-purple-500 dark:opacity-10 animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-96 w-96 rounded-full bg-indigo-300 opacity-10 blur-3xl dark:bg-indigo-500 dark:opacity-5 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="w-full max-w-md space-y-8 text-center">
                {{-- Logo --}}
                <div class="flex justify-center">
                    @if($pengaturan->logo_instansi)
                        <div class="relative">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-400/20 to-purple-400/20 blur-xl"></div>
                            <img 
                                src="{{ asset('storage/' . $pengaturan->logo_instansi) }}" 
                                alt="{{ $pengaturan->nama_instansi }}"
                                class="relative h-20 w-auto object-contain drop-shadow-lg"
                            >
                        </div>
                    @else
                        <div class="relative">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-blue-400/20 to-purple-400/20 blur-xl"></div>
                            <div class="relative flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 shadow-lg">
                                <svg class="h-12 w-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Nama Sistem --}}
                <div class="space-y-2">
                    <h1 class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-4xl font-bold tracking-tight text-transparent dark:from-blue-400 dark:via-indigo-400 dark:to-purple-400 sm:text-5xl">
                        SIPKUD
                    </h1>
                    <p class="text-lg font-medium text-zinc-700 dark:text-zinc-300">
                        {{ $pengaturan->nama_instansi }}
                    </p>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-4">
                    <p class="text-base text-zinc-600 dark:text-zinc-300 leading-relaxed font-medium">
                        Sistem Informasi Pelaporan Keuangan USP Desa
                    </p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto leading-relaxed">
                        Platform terintegrasi untuk mengelola dan memantau aktivitas keuangan Unit Simpan Pinjam (USP) dan Unit Ekonomi Desa (UED-SP) di seluruh desa.
                    </p>
                </div>

                {{-- Tombol Login --}}
                <div class="pt-6">
                    @auth
                        <a 
                            href="{{ route('dashboard') }}"
                            class="group relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        >
                            <span class="relative z-10">Masuk ke Dashboard</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-700 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        </a>
                    @else
                        <a 
                            href="{{ route('login') }}"
                            class="group relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        >
                            <span class="relative z-10">Masuk</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-700 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        </a>
                    @endauth
                </div>

                {{-- Footer Info --}}
                @if($pengaturan->nama_daerah)
                    <div class="pt-8 border-t border-zinc-200/50 dark:border-zinc-700/50">
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                            {{ $pengaturan->nama_daerah }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
