<?php

namespace WebFresh\ResourceBalance\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Mutation extends Model
{
    use LogsActivity;

    protected $table = 'wfrb_mutations';
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->useLogName('Webfresh Resource Balance: Mutation')
            ->logAll()
            ->logExcept([
                'created_at',
                'updated_at'
            ]);
    }
}
