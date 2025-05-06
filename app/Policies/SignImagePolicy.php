<?php

namespace App\Policies;

use App\Models\SignImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SignImagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->hasPermissionTo('View Sign Image')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SignImage $signImage): bool
    {
        if($user->hasPermissionTo('View Sign Image')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SignImage $signImage): bool
    {
        if($user->hasPermissionTo('Edit Sign Image')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SignImage $signImage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SignImage $signImage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SignImage $signImage): bool
    {
        return false;
    }
}
