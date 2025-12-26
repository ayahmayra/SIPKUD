<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Delete account') }}</flux:heading>
        <flux:subheading>{{ __('Delete your account and all of its resources') }}</flux:subheading>
    </div>

    @if(session('delete-error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            {{ session('delete-error') }}
        </div>
    @endif

    @if(!auth()->user()->isSuperAdmin())
        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-gray-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-800">Fitur Terbatas</p>
                    <p class="text-sm text-gray-600 mt-1">Anda tidak dapat menghapus akun sendiri. Hubungi Super Admin jika perlu menghapus akun Anda.</p>
                </div>
            </div>
        </div>
    @else
        <flux:modal.trigger name="confirm-user-deletion">
            <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                {{ __('Delete account') }}
            </flux:button>
        </flux:modal.trigger>

        <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
            <form method="POST" wire:submit="deleteUser" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Are you sure you want to delete your account?') }}</flux:heading>

                    <flux:subheading>
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </flux:subheading>
                </div>

                <flux:input wire:model="password" :label="__('Password')" type="password" />

                <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                    <flux:modal.close>
                        <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button variant="danger" type="submit">{{ __('Delete account') }}</flux:button>
                </div>
            </form>
        </flux:modal>
    @endif
</section>
