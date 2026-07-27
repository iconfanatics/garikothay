<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $user): bool
    {
        return $user->can('view_any_review');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $user, Review $review): bool
    {
        return $user->can('view_review');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $user): bool
    {
        return $user->can('create_review');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $user, Review $review): bool
    {
        return $user->can('update_review');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $user, Review $review): bool
    {
        return $user->can('delete_review');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(Admin $user): bool
    {
        return $user->can('delete_any_review');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(Admin $user, Review $review): bool
    {
        return $user->can('force_delete_review');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(Admin $user): bool
    {
        return $user->can('force_delete_any_review');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(Admin $user, Review $review): bool
    {
        return $user->can('restore_review');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(Admin $user): bool
    {
        return $user->can('restore_any_review');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(Admin $user, Review $review): bool
    {
        return $user->can('replicate_review');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(Admin $user): bool
    {
        return $user->can('reorder_review');
    }
}
