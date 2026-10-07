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
                <div class="z-10 mt-4 mb-4 flex justify-between">
                    @foreach( $balance as $resource => $amount )
                        <div class="block flex-1 p-4 text-center">
                            <flux:heading size="lg" level="3">{{ __( ucfirst($resource) ) }}</flux:heading>
                            <flux:text>{{ number_format($amount, 2) }}</flux:text>
                        </div>
                    @endforeach
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$mutations" class="z-10 table-fixed">
                        <flux:table.columns>
                            @can('Edit balance lines')
                                <flux:table.column>{{ __('Actions') }}</flux:table.column>
                            @endcan
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
                                        {{ __( config('wfresourcebalance.resources.' . str_replace('balance_', '', $column) . '.symbol') ) }}
                                    @else
                                        {{ __(str_replace('balance_', '', $column)) }}
                                    @endif
                                </flux:table.column>
                            @endforeach
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $mutations as $mutation )
                            <flux:table.row wire:key="mutation-{{ $mutation->id }}">
                                @can('Edit balance lines')
                                    <flux:table.cell>
                                        <flux:tooltip content="{{ __('Update mutation') }}">
                                            <flux:icon.pencil-square class="cursor-pointer text-gray-500 inline-block me-4" wire:click="showUpdateMutationWindow({{ $mutation->id }})" />
                                        </flux:tooltip>
                                    </flux:table.cell>
                                @endcan
                                <flux:table.cell>{{ $mutation->order_reference }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->created_at }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->invoice_reference }}</flux:table.cell>
                                <flux:table.cell>{{ __($mutation->status) }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->description }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->sealbag_reference }}</flux:table.cell>
                                <flux:table.cell>{{ $mutation->external_reference }}</flux:table.cell>
                                @foreach( $dynamicColumns as $column )
                                    <flux:table.cell>
                                        @if( (float)$mutation->{$column} != 0 )
                                            {{ $mutation->{$column} }}
                                        @endif
                                    </flux:table.cell>
                                @endforeach
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </div>
            </div>
        </div>
    </div>

    <flux:modal name="resource-transfer" wire:model.self="showResourceTransferModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Transfer resource') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Transfer resource to a different user') }}</flux:text>
            </div>
            <form wire:submit="resourceTransferAction">
                <flux:select wire:change="updateMaxWithdrawAmount" wire:model.live="resourceType" size="sm" label="{{ __('Material') }}" placeholder="{{ __('Select material') }}" class="mb-4">
                    @foreach(config('wfresourcebalance.resources') as $resourceLabel => $resourceObject)
                        <flux:select.option value="{{ $resourceLabel }}" wire:key="{{ $resourceObject['symbol'] }}">{{ __( ucfirst($resourceLabel) ) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input max="{{ $withdrawAmount }}" wire:model="resourceAmount" label="{{ __('Amount') }}" placeholder="{{ __('Enter amount') }}" class="mb-4" />
                <div class="flex">
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
                <flux:select wire:model.live="resourceType" size="sm" label="{{ __('Material') }}" placeholder="{{ __('Select material') }}" class="mb-4">
                    @foreach(config('wfresourcebalance.resources') as $resourceLabel => $resourceObject)
                        <flux:select.option value="{{ $resourceLabel }}" wire:key="{{ $resourceObject['symbol'] }}">{{ __(ucfirst($resourceLabel)) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="resourceAmount" label="{{ __('Amount') }}" placeholder="{{ __('Enter amount') }}" class="mb-4" />
                <flux:textarea wire:model="description" label="{{ __('Description') }}" placeholder="{{ __('Enter description') }}" class="mb-4" />
                <flux:input wire:model="sealbagReference" label="{{ __('Sealbag reference') }}" placeholder="{{ __('Enter sealbag reference') }}" class="mb-4" />
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
                <flux:select wire:change="updateMaxWithdrawAmount" wire:model.live="resourceType" size="sm" label="{{ __('Material') }}" placeholder="{{ __('Select material') }}" class="mb-4">
                    @foreach(config('wfresourcebalance.resources') as $resourceLabel => $resourceObject)
                        <flux:select.option value="{{ $resourceLabel }}" wire:key="{{ $resourceObject['symbol'] }}">{{ __(ucfirst($resourceLabel)) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input max="{{ $withdrawAmount }}" wire:model="resourceAmount" label="{{ __('Amount') }}" placeholder="{{ __('Enter amount') }}" class="mb-4" />
                <flux:textarea wire:model="description" label="{{ __('Description') }}" placeholder="{{ __('Enter description') }}" class="mb-4" />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="button" class="me-4" variant="danger" x-on:click="$wire.showResourceWithdrawModal = false">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Withdraw resource') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="mutation-update" class="md:w-96" wire:model.self="showUpdateMutationModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Update mutation') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Update your mutation') }}</flux:text>
            </div>
            <form wire:submit="updateMutationAction">
                <flux:select wire:model.live="mutationStatus" size="sm" label="{{ __('Status') }}" placeholder="{{ __('Select status') }}" class="mb-4">
                    @foreach(config('wfresourcebalance.status') as $statusIndex => $statusName)
                        <flux:select.option value="{{ $statusName }}" wire:key="{{ $statusIndex }}">{{ __($statusName) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="resourceType" size="sm" label="{{ __('Material') }}" placeholder="{{ __('Select material') }}" class="mb-4">
                    @foreach(config('wfresourcebalance.resources') as $resourceLabel => $resourceObject)
                        <flux:select.option value="{{ $resourceLabel }}" wire:key="{{ $resourceObject['symbol'] }}">{{ ucfirst($resourceLabel) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="resourceAmount" label="{{ __('Amount') }}" placeholder="{{ __('Enter amount') }}" class="mb-4" />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="button" class="me-4" variant="danger" x-on:click="$wire.showUpdateMutationModal = false">{{ __('Cancel') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Update mutation') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
