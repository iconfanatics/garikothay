<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(\Illuminate\Foundation\Auth\User $user): bool
    {
        return $user->can('view_any_invoice');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(\Illuminate\Foundation\Auth\User $user, Invoice $invoice): bool
    {
        return $user->can('view_invoice');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(\Illuminate\Foundation\Auth\User $user): bool
    {
        return $user->can('create_invoice');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(\Illuminate\Foundation\Auth\User $user, Invoice $invoice): bool
    {
        return $user->can('update_invoice');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(\Illuminate\Foundation\Auth\User $user, Invoice $invoice): bool
    {
        return $user->can('delete_invoice');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(\Illuminate\Foundation\Auth\User $user): bool
    {
        return $user->can('delete_any_invoice');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(\Illuminate\Foundation\Auth\User $user, Invoice $invoice): bool
    {
        return $user->can('force_delete_invoice');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(\Illuminate\Foundation\Auth\User $user): bool
    {
        return $user->can('force_delete_any_invoice');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(\Illuminate\Foundation\Auth\User $user, Invoice $invoice): bool
    {
        return $user->can('restore_invoice');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(\Illuminate\Foundation\Auth\User $user): bool
    {
        return $user->can('restore_any_invoice');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(\Illuminate\Foundation\Auth\User $user, Invoice $invoice): bool
    {
        return $user->can('replicate_invoice');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(\Illuminate\Foundation\Auth\User $user): bool
    {
        return $user->can('reorder_invoice');
    }
}
