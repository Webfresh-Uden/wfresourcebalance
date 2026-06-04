<?php

namespace WebFresh\ResourceBalance\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use WebFresh\UserManager\Models\PermissionGroup;

class ResourceBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionGroup = PermissionGroup::create([
            'name' => 'Resource balance'
        ]);
        DB::table('permissions')->insert([
            'name' => 'View resource balance',
            'guard_name' => 'web',
            'permission_group_id' => $permissionGroup->id
        ]);
        DB::table('permissions')->insert([
            'name' => 'Buy resources',
            'guard_name' => 'web',
            'permission_group_id' => $permissionGroup->id
        ]);
        DB::table('permissions')->insert([
            'name' => 'Sell resources',
            'guard_name' => 'web',
            'permission_group_id' => $permissionGroup->id
        ]);
        DB::table('permissions')->insert([
            'name' => 'Transfer resources',
            'guard_name' => 'web',
            'permission_group_id' => $permissionGroup->id
        ]);

    }
}
