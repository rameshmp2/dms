<?php
// app/Repositories/Contracts/DocumentRepositoryInterface.php

namespace App\Repositories\Contracts;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DocumentRepositoryInterface
{
    public function all(): Collection;
    
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    
    public function find(int $id): ?Document;
    
    public function create(array $data): Document;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function findByUser(int $userId): Collection;
    
    public function search(string $keyword): Collection;
    
    public function findByCategory(int $categoryId): Collection;
    
    public function findByStatus(string $status): Collection;
}