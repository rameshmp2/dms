<?php
// app/DataObjects/DocumentData.php

namespace App\DataObjects;

class DocumentData
{
    private $title;
    private $description;
    private $categoryId;
    private $status;
    private $uploadedBy;

    public function __construct(
        string $title,
        ?string $description,
        ?int $categoryId,
        string $status,
        int $uploadedBy
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->status = $status;
        $this->uploadedBy = $uploadedBy;
    }

    public static function fromRequest(array $data, int $userId): self
    {
        return new self(
            $data['title'],
            $data['description'] ?? null,
            $data['category_id'] ?? null,
            $data['status'] ?? 'draft',
            $userId
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUploadedBy(): int
    {
        return $this->uploadedBy;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->categoryId,
            'status' => $this->status,
            'uploaded_by' => $this->uploadedBy,
        ];
    }
}