<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    public function run()
    {
        DB::table('packages')->insert([
            [
                'name' => 'Basic',
                'description' => 'Ideal for small teams',
                'price' => 999.00,
                'max_users' => 5,
                'max_projects' => 5,
                'max_leads' => 500,
                'max_properties' => 50,
                'features' => json_encode(['Lead Management', 'Basic Reports']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional',
                'description' => 'For growing businesses',
                'price' => 2999.00,
                'max_users' => 15,
                'max_projects' => 20,
                'max_leads' => 2000,
                'max_properties' => 200,
                'features' => json_encode(['All Basic Features', 'Automation', 'WhatsApp Integration']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Unlimited everything',
                'price' => 9999.00,
                'max_users' => -1, // -1 means unlimited
                'max_projects' => -1,
                'max_leads' => -1,
                'max_properties' => -1,
                'features' => json_encode(['All Features', 'AI Insights', 'Custom Domain', 'Priority Support']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
