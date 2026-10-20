<?php

namespace WebFresh\ResourceBalance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Balance extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->useLogName('Webfresh Resource Balance: Balance')
            ->logAll()
            ->logExcept([
                'created_at',
                'updated_at'
            ]);
    }
}
