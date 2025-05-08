<?php

use Illuminate\Database\Migrations\Migration;

use App\Helpers\SeederHelper;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            [
                'name' => 'book',
                'sub_permission' => [ 
                   'book-list', 'book-add', 'book-edit', 'book-delete'
                ],
            ],
        ];

        SeederHelper::seedPermissions($permissions);
        $sub_permission = array_merge(...array_column($permissions, 'sub_permission'));
        $new_permission = [ 'category' ];

        $roles = [
            [
                'name' => 'admin',
                'permissions' => array_merge(  $sub_permission, $new_permission  )
            ],
        ];

        SeederHelper::seedRoles($roles);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
