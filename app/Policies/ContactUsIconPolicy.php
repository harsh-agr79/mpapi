<?php

namespace App\Policies;

use App\Models\ContactUsIcon;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactUsIconPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->hasPermissionTo('View Social Link')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContactUsIcon $contactUsIcon): bool
    {
        if($user->hasPermissionTo('View Social Link')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo('Create Social Link')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContactUsIcon $contactUsIcon): bool
    {
        if($user->hasPermissionTo('Edit Social Link')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContactUsIcon $contactUsIcon): bool
    {
        if($user->hasPermissionTo('Delete Social Link')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContactUsIcon $contactUsIcon): bool
    {
        if($user->hasPermissionTo('Delete Social Link')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContactUsIcon $contactUsIcon): bool
    {
        if($user->hasPermissionTo('Delete Social Link')){
            return true;
        }
        return false;
    }
}
