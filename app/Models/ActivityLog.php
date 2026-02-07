<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Add this property with all fillable fields
    protected $fillable = [
        'user_id',
        'document_id',
        'action',
        'description',
        'ip_address',
    ];

    // Only use created_at (no updated_at for logs)
    public const UPDATED_AT = null;

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}