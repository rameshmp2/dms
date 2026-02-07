<?php
// app/Services/DocumentService.php

namespace App\Services;

use App\DataObjects\DocumentData;
use App\DataObjects\FileUploadData;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentService
{
    private $documentRepository;
    private $fileStorageService;
    private $versionControlService;
    private $activityLogService;

    public function __construct(
        DocumentRepositoryInterface $documentRepository,
        FileStorageService $fileStorageService,
        VersionControlService $versionControlService,
        ActivityLogService $activityLogService
    ) {
        $this->documentRepository = $documentRepository;
        $this->fileStorageService = $fileStorageService;
        $this->versionControlService = $versionControlService;
        $this->activityLogService = $activityLogService;
    }

    public function getAllDocuments(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->documentRepository->paginate($perPage, $filters);
    }

    public function getDocument(int $id): ?Document
    {
        return $this->documentRepository->find($id);
    }

    public function createDocument(
        DocumentData $documentData,
        FileUploadData $fileData
    ): Document {
        DB::beginTransaction();
        
        try {
            // Store file
            $filePath = $this->fileStorageService->store($fileData);
            $fileData->setFilePath($filePath);

            // Prepare document data
            $data = array_merge($documentData->toArray(), [
                'file_name' => $fileData->getFileName(),
                'file_path' => $filePath,
                'file_size' => $fileData->getFileSize(),
                'mime_type' => $fileData->getMimeType(),
                'version' => 1,
            ]);

            // Create document
            $document = $this->documentRepository->create($data);

            // Log activity
            $this->activityLogService->log(
                $documentData->getUploadedBy(),
                $document->id,
                'document_created',
                "Document '{$document->title}' was created"
            );

            DB::commit();
            
            return $document;
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Document creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateDocument(
        int $id,
        DocumentData $documentData,
        ?FileUploadData $fileData = null
    ): bool {
        DB::beginTransaction();
        
        try {
            $document = $this->getDocument($id);
            
            if (!$document) {
                throw new Exception('Document not found');
            }

            $updateData = $documentData->toArray();

            // If new file is uploaded, handle version control
            if ($fileData) {
                // Create version backup
                $this->versionControlService->createVersion(
                    $document,
                    $documentData->getUploadedBy()
                );

                // Delete old file
                $this->fileStorageService->delete($document->file_path);

                // Store new file
                $filePath = $this->fileStorageService->store($fileData);
                
                $updateData = array_merge($updateData, [
                    'file_name' => $fileData->getFileName(),
                    'file_path' => $filePath,
                    'file_size' => $fileData->getFileSize(),
                    'mime_type' => $fileData->getMimeType(),
                    'version' => $document->version + 1,
                ]);
            }

            $updated = $this->documentRepository->update($id, $updateData);

            // Log activity
            $this->activityLogService->log(
                $documentData->getUploadedBy(),
                $id,
                'document_updated',
                "Document '{$document->title}' was updated"
            );

            DB::commit();
            
            return $updated;
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Document update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteDocument(int $id, int $userId): bool
    {
        DB::beginTransaction();
        
        try {
            $document = $this->getDocument($id);
            
            if (!$document) {
                throw new Exception('Document not found');
            }

            // Delete file from storage
            $this->fileStorageService->delete($document->file_path);

            // Delete all versions
            foreach ($document->versions as $version) {
                $this->fileStorageService->delete($version->file_path);
            }

            // Log activity before deletion
            $this->activityLogService->log(
                $userId,
                $id,
                'document_deleted',
                "Document '{$document->title}' was deleted"
            );

            // Delete document record
            $deleted = $this->documentRepository->delete($id);

            DB::commit();
            
            return $deleted;
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Document deletion failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function searchDocuments(string $keyword): Collection
    {
        return $this->documentRepository->search($keyword);
    }

    public function getUserDocuments(int $userId): Collection
    {
        return $this->documentRepository->findByUser($userId);
    }
}