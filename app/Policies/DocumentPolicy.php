<?php
// app/Policies/DocumentPolicy.php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any documents.
     */
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view list
    }

    /**
     * Determine if the user can view the document.
     */
    public function view(User $user, Document $document)
    {
        // Admin can view all
        if ($user->role === 'admin') {
            return true;
        }

        // Owner can view
        if ($document->uploaded_by === $user->id) {
            return true;
        }

        // Check permissions
        return $document->permissions()
            ->where('user_id', $user->id)
            ->whereIn('permission', ['view', 'edit', 'delete'])
            ->exists();
    }

    /**
     * Determine if the user can create documents.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'manager', 'user']);
    }

    /**
     * Determine if the user can update the document.
     */
    public function update(User $user, Document $document)
    {
        // Admin can update all
        if ($user->role === 'admin') {
            return true;
        }

        // Owner can update
        if ($document->uploaded_by === $user->id) {
            return true;
        }

        // Check edit permission
        return $document->permissions()
            ->where('user_id', $user->id)
            ->whereIn('permission', ['edit', 'delete'])
            ->exists();
    }

    /**
     * Determine if the user can delete the document.
     */
    public function delete(User $user, Document $document)
    {
        // Only admin and owner can delete
        if ($user->role === 'admin') {
            return true;
        }

        if ($document->uploaded_by === $user->id) {
            return true;
        }

        // Check delete permission
        return $document->permissions()
            ->where('user_id', $user->id)
            ->where('permission', 'delete')
            ->exists();
    }
}