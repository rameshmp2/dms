<?php
// app/DataObjects/FileUploadData.php

namespace App\DataObjects;

use Illuminate\Http\UploadedFile;

class FileUploadData
{
    private $file;
    private $fileName;
    private $fileSize;
    private $mimeType;
    private $filePath;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
        $this->fileName = $file->getClientOriginalName();
        $this->fileSize = $file->getSize();
        $this->mimeType = $file->getMimeType();
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setFilePath(string $path): void
    {
        $this->filePath = $path;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function generateUniqueFileName(): string
    {
        $extension = $this->file->getClientOriginalExtension();
        return uniqid() . '_' . time() . '.' . $extension;
    }
}