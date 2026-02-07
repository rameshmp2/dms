<?php
// app/Http/Middleware/CheckDocumentOwnership.php

namespace App\Http\Middleware;

use Closure;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class CheckDocumentOwnership
{
    public function handle($request, Closure $next)
    {
        $documentId = $request->route('document');
        $document = Document::find($documentId);

        if (!$document) {
            abort(404, 'Document not found');
        }

        $user = Auth::user();

        // Admin can access all documents
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if user is owner
        if ($document->uploaded_by === $user->id) {
            return $next($request);
        }

        // Check if user has permission
        $permission = $document->permissions()
            ->where('user_id', $user->id)
            ->first();

        if ($permission) {
            // Store permission in request for controller use
            $request->merge(['user_permission' => $permission->permission]);
            return $next($request);
        }

        abort(403, 'You do not have permission to access this document');
    }
}