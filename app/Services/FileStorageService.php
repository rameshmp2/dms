<?php
// app/Services/FileStorageService.php

namespace App\Services;

use App\DataObjects\FileUploadData;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    private $storagePath = 'documents';

    public function store(FileUploadData $fileData): string
    {
        $uniqueName = $fileData->generateUniqueFileName();
        $path = $this->storagePath . '/' . date('Y/m');
        
        $fileData->getFile()->storeAs($path, $uniqueName, 'public');
        
        return $path . '/' . $uniqueName;
    }

    public function delete(string $filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }
        
        return false;
    }

    public function getFullPath(string $filePath): string
    {
        return Storage::disk('public')->path($filePath);
    }

    public function getUrl(string $filePath): string
    {
        return Storage::disk('public')->url($filePath);
    }

    public function exists(string $filePath): bool
    {
        return Storage::disk('public')->exists($filePath);
    }
}