<?php
// app/Models/DocumentVersion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id',
        'version_number',
        'file_name',
        'file_path',
        'file_size',
        'uploaded_by',
        'notes',
    ];

    // Only use created_at (no updated_at for versions)
    public const UPDATED_AT = null;

    protected $casts = [
        'file_size' => 'integer',
        'version_number' => 'integer',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}