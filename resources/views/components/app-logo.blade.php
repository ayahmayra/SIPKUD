@if($pengaturan->logo_instansi ?? null)
    <img src="{{ asset('storage/' . $pengaturan->logo_instansi) }}" alt="{{ $pengaturan->nama_instansi ?? 'SIPKUD' }}" class="h-8 w-auto">
@else
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
        <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
    </div>
@endif
<div class="ms-1 grid flex-1 text-start text-sm">
    <span class="mb-0.5 truncate leading-tight font-semibold">{{ $pengaturan->nama_instansi ?? 'SIPKUD' }}</span>
</div>
