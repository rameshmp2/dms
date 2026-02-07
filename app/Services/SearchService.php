<?php
// app/Services/SearchService.php

namespace App\Services;

use App\Repositories\Contracts\DocumentRepositoryInterface;

class SearchService
{
    private $documentRepository;

    public function __construct(DocumentRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    public function advancedSearch(array $criteria)
    {
        // Implement Elasticsearch or full-text search
        // For now, basic search
        return $this->documentRepository->search($criteria['keyword'] ?? '');
    }
}