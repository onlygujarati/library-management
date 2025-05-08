<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Helpers\SeederHelper;
use Carbon\Carbon;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            [
                'name' => 'role',
                'sub_permission' => [ 'role-add', 'role-list'],
            ],
            [
                'name' => 'permission',
                'sub_permission' => [ 
                    'permission-add', 'permission-list',
                ],
            ],
            [
                'name' => 'user',
                'sub_permission' => [ 
                   'user-list', 'user-add', 'user-edit', 'user-delete',
                ],
            ],
        ];

        SeederHelper::seedPermissions($permissions);

        $roles = [
            [
                'name' => 'admin',
                'status' => 1,
                'permissions' => ['role', 'role-add', 'role-list', 'permission', 'permission-add', 'permission-list', 'user', 'user-list', 'user-add', 'user-edit', 'user-delete', 'user-show']
            ],
            [
                'name' => 'user',
                'status' => 1,
                'permissions' => []
            ]
        ];

        SeederHelper::seedRoles($roles);

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
                'email_verified_at' => NULL,
                'user_type' => 'admin',
                'status' => 'active',
                'remember_token' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'name' => 'Test User',
                'email' => 'demo@user.com',
                'password' => bcrypt('12345678'),
                'email_verified_at' => NULL,
                'user_type' => 'user',
                'status' => 'active',
                'remember_token' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
        ];

        foreach ($users as $value) {
            $user = User::create($value);
            $user->assignRole($value['user_type']);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
