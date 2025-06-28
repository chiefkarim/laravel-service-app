<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['resource' => 'service-requests', 'operation' => 'create'],
            ['resource' => 'service-requests', 'operation' => 'read'],
            ['resource' => 'service-requests', 'operation' => 'update'],
            ['resource' => 'service-requests', 'operation' => 'delete'],
            ['resource' => 'users', 'operation' => 'create'],
            ['resource' => 'users', 'operation' => 'read'],
            ['resource' => 'users', 'operation' => 'update'],
            ['resource' => 'users', 'operation' => 'delete'],
            ['resource' => 'permissions', 'operation' => 'read'],
            ['resource' => 'permissions', 'operation' => 'create'],
            ['resource' => 'permissions', 'operation' => 'update'],
            ['resource' => 'permissions', 'operation' => 'delete'],
            ['resource' => 'services', 'operation' => 'create'],
            ['resource' => 'services', 'operation' => 'read'],
            ['resource' => 'services', 'operation' => 'update'],
            ['resource' => 'services', 'operation' => 'delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
}
