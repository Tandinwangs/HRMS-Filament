<?php

namespace App\Policies;

use App\Models\LeaveApproval;
use Chiiya\FilamentAccessControl\Models\FilamentUser as User;
use App\Models\MasDesignation;
use App\Models\AppliedLeave;

class LeaveApprovalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("LeaveApproval.view");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LeaveApproval $LeaveApproval): bool
    {
            return $user->can("LeaveApproval.view");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('LeaveApproval.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LeaveApproval $LeaveApproval): bool
    {
        return $user->can('LeaveApproval.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LeaveApproval $LeaveApproval): bool
    {
        return $user->can('LeaveApproval.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LeaveApproval $LeaveApproval): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LeaveApproval $LeaveApproval): bool
    {
        //
    }
}