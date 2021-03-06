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
            'user_name' => 'superadmin786',
            'email'=>'superadmin@test.com',
            'password'=>bcrypt('admin123'),

        ]);
        Permission::create(
            [
            'name'=>'view-dashboard',
            ],
            [
            'name'=>'manage-dashboard',
            ],
            [
                'name'=>'view-users',
            ],
            [
                'name'=>'manage-users',
            ],
            [
                'name'=>'view-roles',
            ],
            [
                'name'=>'manage-roles',
            ],
            [
                'name'=>'view-permissions',
            ],
            [
                'name'=>'manage-permissions',
            ],
            [
                'name'=>'view-sports',
            ],
            [
                'name'=>'manage-sports',
            ],
            [
                'name'=>'view-leagues',
            ],
            [
                'name'=>'manage-leagues',
            ],
            [
                'name'=>'view-teams',
            ],
            [
                'name'=>'manage-teams',
            ],
            [
                'name'=>'view-schedules',
            ],
            [
                'name'=>'manage-schedules',
            ],
            [
                'name'=>'view-servers',
            ],
            [
                'name'=>'manage-servers',
            ],
            [
                'name'=>'view-applications',
            ],
            [
                'name'=>'manage-applications',
            ],
            [
                'name'=>'view-sponsor_ads',
            ],
            [
                'name'=>'manage-sponsor_ads',
            ],
            [
                'name'=>'view-admob_ads',
            ],
            [
                'name'=>'manage-admob_ads',
            ],
            [
                'name'=>'view-app_credentials',
            ],
            [
                'name'=>'manage-app_credentials',
            ]
        );

        $role = Role::create(['name' => 'super-admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

    }
}
