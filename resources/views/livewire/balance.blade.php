<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="z-10 mt-4 mb-4 flex justify-between">
                    <flux:heading size="xl" level="1" class="ms-4">{{ __('Resource Balance') }}</flux:heading>
                    <div class="me-4">
                        <flux:modal.trigger name="resource-transfer">
                            <flux:button>{{ __('Transfer resources') }}</flux:button>
                        </flux:modal.trigger>
                        <flux:modal.trigger name="resource-deposit">
                            <flux:button>{{ __('Deposit') }}</flux:button>
                        </flux:modal.trigger>
                        <flux:modal.trigger name="resource-withdraw">
                            <flux:button>{{ __('Withdraw') }}</flux:button>
                        </flux:modal.trigger>
                    </div>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$mutations" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'order_reference'" :direction="$sortDirection" wire:click="sort('order_reference')">{{ __('Order') }}</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">{{ __('Date') }}</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'invoice_reference'" :direction="$sortDirection" wire:click="sort('invoice_reference')">{{ __('Invoice') }}</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection" wire:click="sort('status')">{{ __('Status') }}</flux:table.column>
                            <flux:table.column>{{ __('Description') }}</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'sealbag_reference'" :direction="$sortDirection" wire:click="sort('sealbag_reference')">{{ __('Sealbag Nr.') }}</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'external_reference'" :direction="$sortDirection" wire:click="sort('external_reference')">{{ __('Reference') }}</flux:table.column>
                            @foreach( $dynamicColumns as $column )
                                <flux:table.column>
                                    @if( config('wfresourcebalance.use_symbol_as_column_header') )
                                        {{ config('wfresourcebalance.resources.' . str_replace('balance_', '', $column) . '.symbol') }}
                                    @else
                                        {{ __(str_replace('balance_', '', $column)) }}
                                    @endif
                                </flux:table.column>
                            @endforeach
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $mutations as $mutation )
                            <flux:table.row wire:key="mutation-{{ $mutation->id }}">
                                <flux:table.cell>{{ $mutation->order_reference }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->created_at }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->invoice_reference }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->status }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->description }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->sealbag_reference }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->external_reference }}</flux:table.cell>
                                @foreach( $dynamicColumns as $column )
                                    <flux:table.cell>{{ $mutation->{$column} }}</flux:table.cell>
                                @endforeach
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </div>
            </div>
        </div>
    </div>

    <flux:modal name="resource-transfer" class="md:w-96" wire:model.self="showResourceTransferModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Transfer resource') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Transfer resource to a different user') }}</flux:text>
            </div>
            <form wire:submit="resourceTransferAction">
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="button" class="me-4" variant="danger" x-on:click="$wire.showResourceTransferModal = false">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Transfer resource') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="resource-deposit" class="md:w-96" wire:model.self="showResourceDepositModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Deposit resource') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Deposit resource into your account') }}</flux:text>
            </div>
            <form wire:submit="resourceDepositAction">
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="button" class="me-4" variant="danger" x-on:click="$wire.showResourceDepositModal = false">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Deposit resource') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="resource-withdraw" class="md:w-96" wire:model.self="showResourceWithdrawModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Withdraw resource') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Withdraw resource from your account') }}</flux:text>
            </div>
            <form wire:submit="resourceWithdrawAction">
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="button" class="me-4" variant="danger" x-on:click="$wire.showResourceWithdrawModal = false">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Withdraw resource') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
