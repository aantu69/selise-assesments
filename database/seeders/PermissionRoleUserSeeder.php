<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Language;
use Illuminate\Support\Str;
use App\Traits\ConversionTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class PermissionRoleUserSeeder extends Seeder
{
    use ConversionTrait;

    public function run()
    {
        $admin_permissions = [
            'Permission Show', 'Permission Create', 'Permission Edit', 'Permission Delete',
            'User Role Show', 'User Role Create', 'User Role Edit', 'User Role Delete',
            'Admin Role Show', 'Admin Role Create', 'Admin Role Edit', 'Admin Role Delete',
            'User Show', 'User Create', 'User Edit', 'User Delete',
            'User Profile Show', 'User Profile Create', 'User Profile Edit', 'User Profile Delete',
            'Admin Show', 'Admin Create', 'Admin Edit', 'Admin Delete',
            'Admin Profile Show', 'Admin Profile Create', 'Admin Profile Edit', 'Admin Profile Delete',
            'Change Password',
        ];
        $user_permissions = [
            'User Profile Show', 'User Profile Create', 'User Profile Edit', 'User Profile Delete',
            'Change Password',
        ];

        foreach ($admin_permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        $admin_roles = [
            'Super Admin',
            'Admin'
        ];

        foreach ($admin_roles as $role) {
            Role::create([
                'name' => $role,
                'guard_name' => 'admin'
            ]);
        }

        for ($i = 1; $i <= 29; $i++) {
            DB::table('role_has_permissions')->insert([
                ['permission_id' => $i, 'role_id' => 1],
                ['permission_id' => $i, 'role_id' => 2]
            ]);
        }

        $user_permissions = [
            'user_profile_show', 'user_profile_create', 'user_profile_edit', 'user_profile_delete',
            'change_password',
        ];

        foreach ($user_permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $user_roles = [
            'User'
        ];

        foreach ($user_roles as $role) {
            Role::create([
                'name' => $role,
                'guard_name' => 'web'
            ]);
        }

        for ($i = 1; $i <= 5; $i++) {
            DB::table('role_has_permissions')->insert(
                ['permission_id' => $i, 'role_id' => 3]
            );
        }

        $admin = Admin::create([
            'name' => 'Sohrab Hossan',
            'email' => 'aantu69@gmail.com',
            'password' => Hash::make('S187580884t@'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'approved' => 1,
            'active' => 1,
        ]);

        $admin->profile()->create([
            'name' => 'Sohrab Hossan',
            'phone' => '01670233170',
        ]);


        DB::table('model_has_roles')->insert([
            ['model_id' => $admin->id, 'role_id' => 1, 'model_type' => 'App\Models\Admin']
        ]);
    }
}
