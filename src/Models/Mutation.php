<?php

namespace WebFresh\ResourceBalance\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Mutation extends Model
{
    use LogsActivity;

    protected $table = 'wfrb_mutations';
    protected $guarded = ['id'];
}
