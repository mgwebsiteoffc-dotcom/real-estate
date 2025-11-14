<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $currentUser, User $user)
    {
        return $currentUser->company_id === $user->company_id;
    }

    public function update(User $currentUser, User $user)
    {
        return $currentUser->company_id === $user->company_id && 
               in_array($currentUser->role, ['super_admin', 'company_admin']);
    }

    public function delete(User $currentUser, User $user)
    {
        return $currentUser->company_id === $user->company_id && 
               in_array($currentUser->role, ['super_admin', 'company_admin']) &&
               $currentUser->id !== $user->id; // Can't delete yourself
    }
}
