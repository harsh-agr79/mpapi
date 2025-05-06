<?php

namespace App\Policies;

use App\Models\ContactUsData;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactUsDataPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->hasPermissionTo('View Contact')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContactUsData $contactUsData): bool
    {
        if($user->hasPermissionTo('View Contact')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo('Create Contact')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContactUsData $contactUsData): bool
    {
        if($user->hasPermissionTo('Edit Contact')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContactUsData $contactUsData): bool
    {
        if($user->hasPermissionTo('Delete Contact')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContactUsData $contactUsData): bool
    {
        if($user->hasPermissionTo('Delete Contact')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContactUsData $contactUsData): bool
    {
        if($user->hasPermissionTo('Delete Contact')){
            return true;
        }
        return false;
    }
}
