<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Automation;
use App\Models\Lead; // for example
use Carbon\Carbon;

class ProcessAutomations extends Command
{
    protected $signature = 'automations:process';

    protected $description = 'Process automations and trigger actions';

    public function handle()
    {
        $automations = Automation::where('active', true)->get();

        foreach ($automations as $automation) {
            // Example processing lead_created trigger
            if ($automation->trigger_event === 'lead_created') {
                // Find new leads created since last run
                $lastRun = cache()->get('automations_last_run', now()->subHour());
                $newLeads = Lead::where('created_at', '>', $lastRun)->get();

                foreach ($newLeads as $lead) {
                    $this->performAction($automation, $lead);
                }
            }
            // Add more trigger types handling here e.g. task_due, etc.
        }

        cache()->forever('automations_last_run', now());
    }

    protected function performAction($automation, $modelInstance)
{
    $company = $automation->company;

    switch ($automation->action_type) {
        case 'send_whatsapp':
            if ($company->whatsapp_enabled && $company->whatsapp_api_token) {
                $whatsapp = new \App\Services\WhatsAppService($company->whatsapp_api_token);
                
                $details = $automation->action_details;
                $phone = $modelInstance->phone ?? null;
                $message = $details['message'] ?? 'Hello from CRM';

                if ($phone) {
                    $whatsapp->sendTextMessage($phone, $message);
                }
            }
            break;

        case 'send_email':
            // Email logic here
            break;

        case 'create_task':
            // Task creation logic
            break;
    }
}

}
