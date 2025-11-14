<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Get all permissions
        $allPermissions = Permission::all();
        
        // Lead permissions
        $leadPermissions = Permission::where('module', 'leads')->get();
        
        // Property permissions
        $propertyPermissions = Permission::where('module', 'properties')->get();
        
        // Basic permissions (view only)
        $viewPermissions = Permission::whereIn('action', ['view'])->get();

        // ==========================================
        // SYSTEM ROLES (company_id = null)
        // ==========================================

        // 1. Super Admin Role (All permissions)
        $superAdminRole = Role::create([
            'company_id' => null,
            'name' => 'Super Admin',
            'description' => 'Full system access',
            'is_system_role' => true,
        ]);
        $superAdminRole->permissions()->attach($allPermissions);

        // 2. Company Admin Role (All company-level permissions)
        $companyAdminRole = Role::create([
            'company_id' => null,
            'name' => 'Company Admin',
            'description' => 'Full company access',
            'is_system_role' => true,
        ]);
        $companyAdminRole->permissions()->attach($allPermissions);

        // 3. Manager Role (Most permissions)
        $managerRole = Role::create([
            'company_id' => null,
            'name' => 'Manager',
            'description' => 'Manage leads, properties, and team',
            'is_system_role' => true,
        ]);
        $managerPermissions = Permission::whereIn('module', [
            'leads',
            'properties',
            'projects',
            'tasks',
            'reports'
        ])->get();
        $managerRole->permissions()->attach($managerPermissions);

        // 4. Agent Role (Limited permissions)
        $agentRole = Role::create([
            'company_id' => null,
            'name' => 'Agent',
            'description' => 'Manage own leads and properties',
            'is_system_role' => true,
        ]);
        $agentPermissions = Permission::whereIn('module', [
            'leads',
            'properties',
            'tasks'
        ])->whereIn('action', ['view', 'create', 'edit'])->get();
        $agentRole->permissions()->attach($agentPermissions);

        // 5. Sales Executive Role
        $salesRole = Role::create([
            'company_id' => null,
            'name' => 'Sales Executive',
            'description' => 'Focus on leads and sales',
            'is_system_role' => true,
        ]);
        $salesRole->permissions()->attach($leadPermissions);

        // 6. Property Manager Role
        $propertyManagerRole = Role::create([
            'company_id' => null,
            'name' => 'Property Manager',
            'description' => 'Manage properties and projects',
            'is_system_role' => true,
        ]);
        $propertyManagerPermissions = Permission::whereIn('module', [
            'properties',
            'projects'
        ])->get();
        $propertyManagerRole->permissions()->attach($propertyManagerPermissions);

        // 7. Viewer Role (Read only)
        $viewerRole = Role::create([
            'company_id' => null,
            'name' => 'Viewer',
            'description' => 'View only access',
            'is_system_role' => true,
        ]);
        $viewerRole->permissions()->attach($viewPermissions);
    }
}