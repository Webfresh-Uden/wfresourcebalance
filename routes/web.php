<?php

use Illuminate\Support\Facades\Route;
use WebFresh\UserManager\Livewire\Teams;
use WebFresh\UserManager\Livewire\Users;
use WebFresh\UserManager\Livewire\Roles;
use WebFresh\UserManager\Livewire\Permissions;
use WebFresh\UserManager\Livewire\UserSettings;
use WebFresh\UserManager\Livewire\PermissionsMatrix;
use WebFresh\UserManager\Livewire\PermissionGroups;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['web', 'auth'],
], function () {

});
