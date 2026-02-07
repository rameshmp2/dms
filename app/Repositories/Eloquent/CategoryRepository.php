<?php
// app/Repositories/Eloquent/CategoryRepository.php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->with('parent')->get();
    }

    public function find(int $id): ?Category
    {
        return $this->model->with(['parent', 'children'])->find($id);
    }

    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $category = $this->find($id);
        
        if (!$category) {
            return false;
        }

        return $category->update($data);
    }

    public function delete(int $id): bool
    {
        $category = $this->find($id);
        
        if (!$category) {
            return false;
        }

        return $category->delete();
    }

    public function getTree(): Collection
    {
        return $this->model
            ->whereNull('parent_id')
            ->with('children')
            ->get();
    }
}