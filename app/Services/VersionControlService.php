<?php
// app/Services/VersionControlService.php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;

class VersionControlService
{
    public function createVersion(Document $document, int $userId): DocumentVersion
    {
        return DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => $document->version,
            'file_name' => $document->file_name,
            'file_path' => $document->file_path,
            'file_size' => $document->file_size,
            'uploaded_by' => $userId,
        ]);
    }

    public function getVersions(int $documentId)
    {
        return DocumentVersion::where('document_id', $documentId)
            ->with('uploader')
            ->orderBy('version_number', 'desc')
            ->get();
    }

    public function restoreVersion(int $versionId, int $userId): bool
    {
        $version = DocumentVersion::find($versionId);
        
        if (!$version) {
            return false;
        }

        $document = Document::find($version->document_id);
        
        // Create backup of current version
        $this->createVersion($document, $userId);

        // Restore from version
        $document->update([
            'file_name' => $version->file_name,
            'file_path' => $version->file_path,
            'file_size' => $version->file_size,
            'version' => $document->version + 1,
        ]);

        return true;
    }
}