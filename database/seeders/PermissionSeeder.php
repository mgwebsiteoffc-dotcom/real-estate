<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Lead Management
            ['name' => 'leads.view', 'module' => 'leads', 'action' => 'view'],
            ['name' => 'leads.create', 'module' => 'leads', 'action' => 'create'],
            ['name' => 'leads.edit', 'module' => 'leads', 'action' => 'edit'],
            ['name' => 'leads.delete', 'module' => 'leads', 'action' => 'delete'],
            ['name' => 'leads.assign', 'module' => 'leads', 'action' => 'assign'],
            
            // Property Management
            ['name' => 'properties.view', 'module' => 'properties', 'action' => 'view'],
            ['name' => 'properties.create', 'module' => 'properties', 'action' => 'create'],
            ['name' => 'properties.edit', 'module' => 'properties', 'action' => 'edit'],
            ['name' => 'properties.delete', 'module' => 'properties', 'action' => 'delete'],
            
            // Project Management
            ['name' => 'projects.view', 'module' => 'projects', 'action' => 'view'],
            ['name' => 'projects.create', 'module' => 'projects', 'action' => 'create'],
            ['name' => 'projects.edit', 'module' => 'projects', 'action' => 'edit'],
            ['name' => 'projects.delete', 'module' => 'projects', 'action' => 'delete'],
            
            // Task Management
            ['name' => 'tasks.view', 'module' => 'tasks', 'action' => 'view'],
            ['name' => 'tasks.create', 'module' => 'tasks', 'action' => 'create'],
            ['name' => 'tasks.edit', 'module' => 'tasks', 'action' => 'edit'],
            ['name' => 'tasks.delete', 'module' => 'tasks', 'action' => 'delete'],
            
            // User Management
            ['name' => 'users.view', 'module' => 'users', 'action' => 'view'],
            ['name' => 'users.create', 'module' => 'users', 'action' => 'create'],
            ['name' => 'users.edit', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'users.delete', 'module' => 'users', 'action' => 'delete'],
            
            // Role Management
            ['name' => 'roles.view', 'module' => 'roles', 'action' => 'view'],
            ['name' => 'roles.create', 'module' => 'roles', 'action' => 'create'],
            ['name' => 'roles.edit', 'module' => 'roles', 'action' => 'edit'],
            ['name' => 'roles.delete', 'module' => 'roles', 'action' => 'delete'],
            
            // Company Management
            ['name' => 'companies.view', 'module' => 'companies', 'action' => 'view'],
            ['name' => 'companies.create', 'module' => 'companies', 'action' => 'create'],
            ['name' => 'companies.edit', 'module' => 'companies', 'action' => 'edit'],
            ['name' => 'companies.delete', 'module' => 'companies', 'action' => 'delete'],
            
            // Reports
            ['name' => 'reports.view', 'module' => 'reports', 'action' => 'view'],
            ['name' => 'reports.export', 'module' => 'reports', 'action' => 'export'],
            
            // Settings
            ['name' => 'settings.view', 'module' => 'settings', 'action' => 'view'],
            ['name' => 'settings.edit', 'module' => 'settings', 'action' => 'edit'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name']],
                array_merge($permission, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}