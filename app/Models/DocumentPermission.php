<?php
// app/Models/DocumentPermission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentPermission extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'permission',
        'granted_by',
    ];

    // Only use created_at (no updated_at for permissions)
    public const UPDATED_AT = null;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }
}