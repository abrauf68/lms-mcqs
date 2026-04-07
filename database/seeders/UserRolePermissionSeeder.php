<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view archived user']);
        Permission::create(['name' => 'create archived user']);
        Permission::create(['name' => 'update archived user']);
        Permission::create(['name' => 'delete archived user']);

        Permission::create(['name' => 'view setting']);
        Permission::create(['name' => 'create setting']);
        Permission::create(['name' => 'update setting']);
        Permission::create(['name' => 'delete setting']);

        Permission::create(['name' => 'view product']);
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'update product']);
        Permission::create(['name' => 'delete product']);

        Permission::create(['name' => 'view domain']);
        Permission::create(['name' => 'create domain']);
        Permission::create(['name' => 'update domain']);
        Permission::create(['name' => 'delete domain']);

        Permission::create(['name' => 'view process group']);
        Permission::create(['name' => 'create process group']);
        Permission::create(['name' => 'update process group']);
        Permission::create(['name' => 'delete process group']);

        Permission::create(['name' => 'view approach']);
        Permission::create(['name' => 'create approach']);
        Permission::create(['name' => 'update approach']);
        Permission::create(['name' => 'delete approach']);

        Permission::create(['name' => 'view topic']);
        Permission::create(['name' => 'create topic']);
        Permission::create(['name' => 'update topic']);
        Permission::create(['name' => 'delete topic']);

        Permission::create(['name' => 'view question']);
        Permission::create(['name' => 'create question']);
        Permission::create(['name' => 'update question']);
        Permission::create(['name' => 'delete question']);

        Permission::create(['name' => 'view exam']);
        Permission::create(['name' => 'create exam']);
        Permission::create(['name' => 'update exam']);
        Permission::create(['name' => 'delete exam']);

        Permission::create(['name' => 'view contact']);
        Permission::create(['name' => 'create contact']);
        Permission::create(['name' => 'update contact']);
        Permission::create(['name' => 'delete contact']);

        Permission::create(['name' => 'view pricing']);
        Permission::create(['name' => 'create pricing']);
        Permission::create(['name' => 'update pricing']);
        Permission::create(['name' => 'delete pricing']);


        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // give all permissions to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // give permissions to admin role.
        $adminRole->givePermissionTo(['view role']);
        $adminRole->givePermissionTo(['view permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user']);


        // Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'admin@gmail.com',
                ], [
                    'name' => 'Super Admin',
                    'email' => 'admin@gmail.com',
                    'username' => 'superadmin',
                    'password' => Hash::make ('12345678'),
                    'email_verified_at' => now(),
                ]);

        $superAdminUser->assignRole($superAdminRole);

        $superAdminProfile = $superAdminUser->profile()->firstOrCreate([
            'user_id' => $superAdminUser->id,
        ], [
            'user_id' => $superAdminUser->id,
            'first_name' => $superAdminUser->name,
        ]);
    }
}
