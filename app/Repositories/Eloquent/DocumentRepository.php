<?php
// app/Repositories/Eloquent/DocumentRepository.php

namespace App\Repositories\Eloquent;

use App\Models\Document;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentRepository implements DocumentRepositoryInterface
{
    protected $model;

    public function __construct(Document $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->with(['category', 'uploader'])->get();
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with(['category', 'uploader']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'LIKE', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(int $id): ?Document
    {
        return $this->model->with(['category', 'uploader', 'versions'])->find($id);
    }

    public function create(array $data): Document
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $document = $this->find($id);
        
        if (!$document) {
            return false;
        }

        return $document->update($data);
    }

    public function delete(int $id): bool
    {
        $document = $this->find($id);
        
        if (!$document) {
            return false;
        }

        return $document->delete();
    }

    public function findByUser(int $userId): Collection
    {
        return $this->model
            ->where('uploaded_by', $userId)
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function search(string $keyword): Collection
    {
        return $this->model
            ->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description', 'LIKE', '%' . $keyword . '%')
            ->with(['category', 'uploader'])
            ->get();
    }

    public function findByCategory(int $categoryId): Collection
    {
        return $this->model
            ->where('category_id', $categoryId)
            ->with(['uploader'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findByStatus(string $status): Collection
    {
        return $this->model
            ->where('status', $status)
            ->with(['category', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}