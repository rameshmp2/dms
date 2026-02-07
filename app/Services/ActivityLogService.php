<?php
// app/Services/ActivityLogService.php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public function log(
        int $userId,
        ?int $documentId,
        string $action,
        string $description
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $userId,
            'document_id' => $documentId,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }

    public function getUserActivities(int $userId, int $limit = 50)
    {
        return ActivityLog::where('user_id', $userId)
            ->with('document')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getDocumentActivities(int $documentId)
    {
        return ActivityLog::where('document_id', $documentId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}