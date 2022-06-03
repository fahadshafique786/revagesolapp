<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
    $user=  User::create([
            'name' => 'Admin',
            'user_name' => 'Admin',
            'email'=>'admin@test.com',
            'password'=>bcrypt('admin123'),

        ]);
        Permission::create(
            [
            'name'=>'view_dashboard',
                ],
            [
            'name'=>'manage_dasboard',
            ],
            [
                'name'=>'view_users',
            ],
            [
                'name'=>'manage_users',
            ],
            [
                'name'=>'view_roles',
            ],
            [
                'name'=>'manage_roles',
            ],
            [
                'name'=>'view_permissions',
            ],
            [
                'name'=>'manage_permissions',
            ],
            [
                'name'=>'view_sports',
            ],
            [
                'name'=>'manage_sports',
            ],
            [
                'name'=>'view_leagues',
            ],
            [
                'name'=>'manage_leagues',
            ]
        );
        $role = Role::create(['name' => 'super-admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

    }
}
