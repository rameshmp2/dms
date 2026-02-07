<?php
// app/Http/Controllers/DocumentController.php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\DataObjects\DocumentData;
use App\DataObjects\FileUploadData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class DocumentController extends Controller
{
    private $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'category_id' => $request->get('category_id'),
            'search' => $request->get('search'),
        ];

        $documents = $this->documentService->getAllDocuments(15, $filters);
        
        return view('documents.index', compact('documents', 'filters'));
    }

    public function create()
    {
        $categories = app(\App\Repositories\Contracts\CategoryRepositoryInterface::class)->all();
        return view('documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'file' => 'required|file|max:10240', // 10MB
        ]);

        try {
            $documentData = DocumentData::fromRequest($validated, Auth::id());
            $fileData = new FileUploadData($request->file('file'));

            $document = $this->documentService->createDocument($documentData, $fileData);

            return redirect()
                ->route('documents.show', $document->id)
                ->with('success', 'Document uploaded successfully!');
                
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to upload document: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $document = $this->documentService->getDocument($id);
        
        if (!$document) {
            abort(404, 'Document not found');
        }

        return view('documents.show', compact('document'));
    }

    public function edit($id)
    {
        $document = $this->documentService->getDocument($id);
        
        if (!$document) {
            abort(404, 'Document not found');
        }

        $categories = app(\App\Repositories\Contracts\CategoryRepositoryInterface::class)->all();
        
        return view('documents.edit', compact('document', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'file' => 'nullable|file|max:10240',
        ]);

        try {
            $documentData = DocumentData::fromRequest($validated, Auth::id());
            $fileData = $request->hasFile('file') 
                ? new FileUploadData($request->file('file')) 
                : null;

            $this->documentService->updateDocument($id, $documentData, $fileData);

            return redirect()
                ->route('documents.show', $id)
                ->with('success', 'Document updated successfully!');
                
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update document: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->documentService->deleteDocument($id, Auth::id());

            return redirect()
                ->route('documents.index')
                ->with('success', 'Document deleted successfully!');
                
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete document: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $document = $this->documentService->getDocument($id);
        
        if (!$document) {
            abort(404, 'Document not found');
        }

        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $document->file_name);
    }
}