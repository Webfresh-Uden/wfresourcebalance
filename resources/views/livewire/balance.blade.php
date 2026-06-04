<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">Resource Balance</flux:heading>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$mutations" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'order_reference'" :direction="$sortDirection" wire:click="sort('order_reference')">Order</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Date</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'invoice_reference'" :direction="$sortDirection" wire:click="sort('invoice_reference')">Invoice</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection" wire:click="sort('status')">Status</flux:table.column>
                            <flux:table.column>Description</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'sealbag_reference'" :direction="$sortDirection" wire:click="sort('sealbag_reference')">Sealbag Nr.</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'external_reference'" :direction="$sortDirection" wire:click="sort('external_reference')">Reference</flux:table.column>
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
</div>
