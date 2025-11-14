<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Create a default company for super admin
        $company = Company::firstOrCreate(
            ['email' => 'admin@realestate.com'],
            [
                'name' => 'Real Estate CRM Admin',
                'phone' => '+91 1234567890',
                'address' => 'Admin Office',
                'status' => 'active',
                'package_id' => 1, // Assuming first package exists
            ]
        );

        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@realestate.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'phone' => '+91 1234567890',
                'role' => 'super_admin',
                'company_id' => $company->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create a test company admin
        $testCompany = Company::firstOrCreate(
            ['email' => 'test@company.com'],
            [
                'name' => 'Test Real Estate Company',
                'phone' => '+91 9876543210',
                'address' => 'Mumbai, India',
                'status' => 'active',
                'package_id' => 2, // Professional package
            ]
        );

        $companyAdmin = User::firstOrCreate(
            ['email' => 'company@test.com'],
            [
                'name' => 'Company Admin',
                'password' => Hash::make('password123'),
                'phone' => '+91 9876543210',
                'role' => 'company_admin',
                'company_id' => $testCompany->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create test agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@test.com'],
            [
                'name' => 'Test Agent',
                'password' => Hash::make('password123'),
                'phone' => '+91 9998887770',
                'role' => 'agent',
                'company_id' => $testCompany->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Super Admin and test users created successfully!');
        $this->command->info('Super Admin: admin@realestate.com / password123');
        $this->command->info('Company Admin: company@test.com / password123');
        $this->command->info('Agent: agent@test.com / password123');
    }
}