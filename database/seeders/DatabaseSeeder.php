<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Permission::create(['name' => 'dashboard']);
        // role
        Permission::create(['name' => 'role-index']);
        Permission::create(['name' => 'role-create']);
        Permission::create(['name' => 'role-update']);
        Permission::create(['name' => 'role-delete']);
        // permission
        Permission::create(['name' => 'permission-index']);
        Permission::create(['name' => 'permission-create']);
        Permission::create(['name' => 'permission-update']);
        Permission::create(['name' => 'permission-delete']);
        $superAdmin = Role::create(['name' => 'SUPER-ADMIN']);
        $superAdmin->givePermissionTo([
            'dashboard',
            'role-index',
            'role-create',
            'role-update',
            'role-delete',
            'permission-index',
            'permission-create',
            'permission-update',
            'permission-delete',
        ]);
        $user = User::firstOrCreate(
            ['nid' => '199903182025211027'],
            [
                'name' => 'GUNTUR, A.Md',
                'password' => bcrypt('a'),
                'email' => 'goentursumkid@gmail.com',
                'email_verified_at' => now(),
                'telp' => '085201365883',
                'telp_verified_at' => now(),
            ]
        );
        $user->assignRole($superAdmin);
    }
}
