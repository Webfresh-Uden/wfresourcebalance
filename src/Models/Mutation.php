<?php

namespace WebFresh\ResourceBalance\Models;

use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    protected $table = 'wfrb_mutations';
    protected $guarded = ['id'];
}
