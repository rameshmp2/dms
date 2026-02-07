<?php
// app/Models/Document.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'version',
        'category_id',
        'uploaded_by',
        'status',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'version' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)
                    ->orderBy('version_number', 'desc');
    }

    public function permissions()
    {
        return $this->hasMany(DocumentPermission::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class)
                    ->orderBy('created_at', 'desc');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'document_tag')
                    ->withTimestamps();
    }

    // Accessor for formatted file size
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Scope for searching
    public function scopeSearch($query, $keyword)
    {
        return $query->where('title', 'LIKE', "%{$keyword}%")
                     ->orWhere('description', 'LIKE', "%{$keyword}%");
    }

    // Scope for filtering by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for filtering by category
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}