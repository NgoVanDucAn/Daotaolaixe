<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Tạo các permissions
        $addUsersPermission = Permission::create(['name' => 'add users']);
        $detailUsersPermission = Permission::create(['name' => 'detail user']);
        $editUsersPermission = Permission::create(['name' => 'edit users']);
        $deleteUsersPermission = Permission::create(['name' => 'delete users']);

        $addRoleOfUsersPermission = Permission::create(['name' => 'add role of users']);
        $detailRoleOfUsersPermission = Permission::create(['name' => 'detail role of users']);
        $updateRoleOfUsersPermission = Permission::create(['name' => 'update role of users']);
        $deleteRoleOfUsersPermission = Permission::create(['name' => 'delete role of users']);

        $addPermissionOfRolesPermission = Permission::create(['name' => 'add permission of roles']);
        $detailPermissionOfRolesPermission = Permission::create(['name' => 'detail permission of roles']);
        $updatePermissionOfRolesPermission = Permission::create(['name' => 'update permission of roles']);
        $deletePermissionOfRolesPermission = Permission::create(['name' => 'delete permission of roles']);



        // Tạo các roles
        $adminRole = Role::create(['name' => 'admin']);
        $salespersonRole = Role::create(['name' => 'salesperson']);
        $instructorRole = Role::create(['name' => 'instructor']);

        // Gán permissions cho role admin
        $adminRole->givePermissionTo([$addUsersPermission, $detailUsersPermission, $editUsersPermission, $deleteUsersPermission, $addRoleOfUsersPermission, $detailRoleOfUsersPermission, $updateRoleOfUsersPermission, $deleteRoleOfUsersPermission, $addPermissionOfRolesPermission, $detailPermissionOfRolesPermission, $updatePermissionOfRolesPermission, $deletePermissionOfRolesPermission]);
        $salespersonRole->givePermissionTo([$addRoleOfUsersPermission, $detailRoleOfUsersPermission, $updateRoleOfUsersPermission, $deleteRoleOfUsersPermission]);
        $instructorRole->givePermissionTo([$addPermissionOfRolesPermission, $detailPermissionOfRolesPermission, $updatePermissionOfRolesPermission, $deletePermissionOfRolesPermission]);

        // Tạo các user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole('admin');

        $salespersonUser = User::create([
            'name' => 'Salesperson User',
            'email' => 'salesperson@example.com',
            'password' => bcrypt('password'),
        ]);
        $salespersonUser->assignRole('salesperson');

        $instructorUser = User::create([
            'name' => 'Instructor User',
            'email' => 'instructor@example.com',
            'password' => bcrypt('password'),
        ]);
        $instructorUser->assignRole('instructor');
    }
}
