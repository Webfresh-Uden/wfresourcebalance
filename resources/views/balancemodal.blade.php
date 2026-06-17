<flux:modal name="resource-balance" class="md:w-96" wire:model.self="showResourceBalanceModal">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Resource balance') }}</flux:heading>
            <flux:text class="mt-2">{{ __('Edit the users resource balance') }}</flux:text>
        </div>
        <div class="flex gap-2">
            <form wire:submit="resourceBalanceAction">
                @foreach(config('wfresourcebalance.resources') as $resourceLabel => $resourceObject)
                    <flux:input type="number" step=".01" name="balance_{{$resourceLabel}}" wire:model.live="balance.balance_{{$resourceLabel}}" label="{{ ucfirst($resourceLabel) }}" placeholder="0.00" class="mb-4" />
                @endforeach
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="button" class="me-4" variant="danger" x-on:click="$wire.showResourceBalanceModal = false">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Edit resource balance') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</flux:modal>
