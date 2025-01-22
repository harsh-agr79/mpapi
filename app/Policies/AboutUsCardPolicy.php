<?php

namespace App\Policies;

use App\Models\AboutUsCard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AboutUsCardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->hasPermissionTo('Edit AboutUs')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AboutUsCard $aboutUsCard): bool
    {
        if($user->hasPermissionTo('Edit AboutUs')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo('Edit AboutUs')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AboutUsCard $aboutUsCard): bool
    {
        if($user->hasPermissionTo('Edit AboutUs')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AboutUsCard $aboutUsCard): bool
    {
        if($user->hasPermissionTo('Edit AboutUs')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AboutUsCard $aboutUsCard): bool
    {
        if($user->hasPermissionTo('Edit AboutUs')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AboutUsCard $aboutUsCard): bool
    {
        if($user->hasPermissionTo('Edit AboutUs')){
            return true;
        }
        return false;
    }
}
