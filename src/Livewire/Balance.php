<?php

namespace WebFresh\ResourceBalance\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WebFresh\ResourceBalance\Models\Mutation;
use WebFresh\ResourceBalance\Models\Balance as BalanceModel;
use Illuminate\Support\Facades\Auth;

#[Title('Balance')]
class Balance extends Component
{
    use WithPagination;

    private $userBalance;
    private $userMutations;
    public $balance;

    // Basic resource info
    public $resourceAmount;
    public $resourceType;
    public $description;
    public $sealbagReference = '';
    public $mutationStatus;

    // Max withdraw amount for a resource
    public $withdrawAmount;

    public $id = '';

    #[Validate('string', message: 'Invalid sort field')]
    public string $sortBy = 'created_at';

    #[Validate('string|in:desc,asc', message: 'Invalid sort field')]
    public string $sortDirection = 'desc';

    public bool $showResourceTransferModal = false;
    public bool $showResourceDepositModal = false;
    public bool $showResourceWithdrawModal = false;
    public bool $showUpdateMutationModal = false;

    public function mount()
    {
        $this->userBalance = BalanceModel::where('user_id', auth()->id())->first();
        $this->userMutations = Mutation::where('user_id', auth()->id())->get();

        $this->calculateBalance();
    }

    public function calculateBalance()
    {
        foreach (config('wfresourcebalance.resources') as $resourceName => $resourceConfig) {
            $this->balance[$resourceName] = (float)0;
            if( $this->userBalance ) {
                $this->balance[$resourceName] = (float)$this->userBalance->{'balance_' . $resourceName};
            }
        }
        $dynamicColumns = $this->getDynamicColumns();
        if ($this->userMutations && $this->userMutations->count() > 0)
        {
            foreach ($this->userMutations as $userMutation) {
                foreach ($dynamicColumns as $column) {
                    $colName = str_replace('balance_', '', $column);
                    $this->balance[$colName] += (float)$userMutation->{$column};
                }
            }
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function rules()
    {
        return [
            'resourceAmount' => 'numeric|min:0',
            'withdrawAmount' => 'numeric|min:0',
            'resourceType' => 'required|string',
            'sealbagReference' => 'string',
            'description' => 'string',
        ];
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('wfrb::livewire.balance', [
            'mutations' => Mutation::where('user_id', auth()->id())->paginate(15),
            'dynamicColumns' => $this->getDynamicColumns()
        ]);
    }

    public function getDynamicColumns()
    {
        $columns = DB::connection()->getSchemaBuilder()->getColumnListing('wfrb_mutations');
        $returnColumns = [];
        foreach ($columns as $column)
        {
            if( str_contains($column, 'balance_') )
            {
                $returnColumns[] = $column;
            }
        }
        return $returnColumns;
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFieldData(): void
    {
        $this->id = '';
    }

    // Transfer modal handling
    public function showResourceTransfer($resource_id): void
    {
        $this->resetValues();
        $this->showResourceTransferModal = true;
    }
    public function resourceTransferAction(): void
    {
        // Cleanup and notification
        $this->showResourceTransferModal = false;
        Flux::toast(text: __('Resource transferred'));
    }

    // Deposit modal handling
    public function showDepositResource($resource_id): void
    {
        $this->resetValues();
        $this->showResourceDepositModal = true;
    }
    public function resourceDepositAction(): void
    {
        Mutation::create([
            'user_id' => auth()->id(),
            'description' => $this->description,
            'status'  => 'Deposit requested',
            'external_reference' => $this->sealbagReference,
            'balance_' . $this->resourceType => $this->resourceAmount,
        ]);

        $this->calculateBalance();

        // Cleanup and notification
        $this->showResourceDepositModal = false;
        Flux::toast(text: __('Resource deposit planned'));
    }

    // Withdraw modal handling
    public function showWithdrawResource($resource_id): void
    {
        $this->resetValues();
        $this->showResourceWithdrawModal = true;
    }
    public function resourceWithdrawAction(): void
    {
        Mutation::create([
            'user_id' => auth()->id(),
            'description' => $this->description,
            'status'  => 'Withdraw requested',
            'balance_' . $this->resourceType => -$this->resourceAmount,
        ]);

        $this->calculateBalance();

        // Cleanup and notification
        $this->showResourceWithdrawModal = false;
        Flux::toast(text: __('Withdrawal planned'));
    }

    public function updateMaxWithdrawAmount(): void
    {
        $this->calculateBalance();
        $this->withdrawAmount = $this->resourceAmount = 0;
        if( isset($this->balance['balance_'.$this->resourceType]) ) {
            $this->withdrawAmount = $this->resourceAmount = $this->balance['balance_' . $this->resourceType];
        }
    }

    public function resetValues(): void
    {
        $this->description = '';
        $this->resourceAmount = 0;
        $this->resourceType = '';
        $this->sealbagReference = '';
        $this->withdrawAmount = 0;
    }

    public function showUpdateMutationWindow($mutation_id){
        $mutation = Mutation::find($mutation_id);

        $this->status = $mutation->status;
        $this->resourceType = $mutation->resource_type;

        $dynamicColumns = $this->getDynamicColumns();
        foreach ($dynamicColumns as $column) {
            if( (float)$mutation->{$column} != 0 ) {
                $this->resourceAmount = number_format((float)($mutation->{$column}), 2);
            }
        }

        $this->showUpdateMutationModal = true;
    }

    public function updateMutationAction(): void
    {
        // Cleanup and notification
        $this->showUpdateMutationModal = false;
        Flux::toast(text: __('Mutation updated'));
    }
}
