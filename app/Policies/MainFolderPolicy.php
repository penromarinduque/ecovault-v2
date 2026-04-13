<?php

namespace App\Policies;

use App\Models\MainFolder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MainFolderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MainFolder $mainFolder): bool
    {
        return $user->isAdmin() || ($user->isEncoder() && $user->canEncodeMainFolder($mainFolder->id)) || $user->isViewer();
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
    public function update(User $user, MainFolder $mainFolder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MainFolder $mainFolder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MainFolder $mainFolder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MainFolder $mainFolder): bool
    {
        return false;
    }
}
