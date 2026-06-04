<?php

namespace WebFresh\ResourceBalance\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WebFresh\ResourceBalance\Models\Mutation;

#[Title('Balance')]
class Balance extends Component
{
    use WithPagination;

    private $mutations;

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public function mount(): void
    {
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->mutations = Mutation::where('user_id', auth()->id())->paginate(15);

        return view('wfrb::livewire.balance', [
            'mutations' => $this->mutations,
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
}
