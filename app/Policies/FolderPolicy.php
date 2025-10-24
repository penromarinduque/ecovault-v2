<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FolderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Folder $folder): bool
    {
        if($user->isAdmin()) {
            return true;
        }
        if($user->isEncoder() && $user->canEncodeMainFolder($folder->main_folder_id)){ 
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->isAdmin()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Folder $folder): bool
    {
        if($user->isAdmin()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Folder $folder): bool
    {
        if($user->isAdmin()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Folder $folder): bool
    {
        if($user->isAdmin()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Folder $folder): bool
    {
        if($user->isAdmin()) {
            return true;
        }
        return false;
    }

    public function uploadFile(User $user, Folder $folder): bool
    {
        if($user->isAdmin()) {
            return true;
        }
        if($user->isEncoder() && $user->canEncodeMainFolder($folder->main_folder_id)){ 
            return true;
        }
        return false;
    }
}
