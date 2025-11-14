<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function view(User $user, Lead $lead)
    {
        return $user->company_id === $lead->company_id;
    }

    public function update(User $user, Lead $lead)
    {
        return $user->company_id === $lead->company_id;
    }

    public function delete(User $user, Lead $lead)
    {
        return $user->company_id === $lead->company_id && 
               ($user->role === 'super_admin' || $user->role === 'company_admin');
    }
}
