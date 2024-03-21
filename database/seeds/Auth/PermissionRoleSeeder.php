<?php

use App\Models\Auth\Role;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleSeeder.
 */
class PermissionRoleSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();
        $this->truncate('permission_role');

        // Assign permission to executive role
        $executivePermission = [1, 3, 4, 5, 6, 7, 8, 16, 20,
            24, 25, 26, 27, // CMS Pages
            41, 42, 43, 44, // Link
            45, 46, 47, 48, // Domain
        ];
        Role::find(2)->permissions()->sync($executivePermission);

        $this->enableForeignKeys();
    }
}
