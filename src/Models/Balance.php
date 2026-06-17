<?php

namespace WebFresh\ResourceBalance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Balance extends Model
{
    protected $table = 'wfrb_balance';

    protected $guarded = ['id'];

    public static function getDynamicColumns(): array
    {
        $columns = Schema::getColumnListing('wfrb_balance');

        $columns = array_filter($columns, function($column) {
            return str_contains($column, 'balance_');
        });

        return $columns;
    }
}
