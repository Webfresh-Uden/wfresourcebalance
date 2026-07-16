<?php

use Illuminate\Support\Facades\Route;
use WebFresh\ResourceBalance\Livewire\Balance;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['web', 'auth', 'verified'],
], function () {
    Route::livewire('balance', Balance::class)->name('balance.index');
});
