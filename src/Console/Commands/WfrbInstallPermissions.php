<?php

namespace WebFresh\ResourceBalance\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use WebFresh\UserManager\Models\PermissionGroup;
use WebFresh\UserManager\Models\Team;
use App;

#[Signature('wfrb:installpermissions')]
#[Description('Command description')]
class WfrbInstallPermissions extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing Webfresh Resource Balance permissions...');

        foreach( config('wfresourcebalance.permissions') as $permissionGroup => $permissionList ) {
            $pg = PermissionGroup::create([
                'name' => $permissionGroup,
            ]);
            $this->info("Permission group $permissionGroup was created");
            foreach ($permissionList as $permission => $guard) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                    'permission_group_id' => $pg->id,
                ]);
                $this->info("Permission $permission was created");
            }
        }

        $this->info('Installation completed, enjoy!');
    }
}
