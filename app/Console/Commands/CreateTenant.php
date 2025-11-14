<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {name} {domain}';
    protected $description = 'Create a new tenant';

    public function handle()
    {
        $tenant = Tenant::create([
            'company_name' => $this->argument('name'),
            'company_email' => $this->argument('name') . '@example.com',
        ]);
        
        $tenant->domains()->create([
            'domain' => $this->argument('domain'),
        ]);
        
        $this->info("Tenant '{$this->argument('name')}' created with domain '{$this->argument('domain')}'");
        
        return 0;
    }
}
