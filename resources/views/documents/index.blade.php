<!-- resources/views/documents/index.blade.php -->
@extends('layouts.app')

@section('title', 'Documents')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-folder-open"></i> Documents</h2>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Upload Document
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('documents.index') }}" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search documents..." 
                       value="{{ request('search') }}">
            </div>
            
            <div class="form-group mr-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            
            <div class="form-group mr-2">
                <select name="category_id" class="form-control">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary mr-2">
                <i class="fas fa-search"></i> Filter
            </button>
            
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>
    </div>
</div>

<!-- Documents Table -->
<div class="card">
    <div class="card-body">
        @if($documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Title</th>
                            <th width="15%">Category</th>
                            <th width="10%">Size</th>
                            <th width="8%">Version</th>
                            <th width="10%">Status</th>
                            <th width="12%">Uploaded</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                            <tr>
                                <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
                                <td>
                                    <i class="fas fa-file-{{ $document->mime_type == 'application/pdf' ? 'pdf text-danger' : 'alt text-primary' }}"></i>
                                    <a href="{{ route('documents.show', $document->id) }}" class="font-weight-bold">
                                        {{ Str::limit($document->title, 40) }}
                                    </a>
                                    @if($document->description)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($document->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($document->category)
                                        <span class="badge badge-secondary">{{ $document->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                <td>
                                    <span class="badge badge-info">v{{ $document->version }}</span>
                                </td>
                                <td>
                                    @if($document->status == 'published')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Published
                                        </span>
                                    @elseif($document->status == 'draft')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-edit"></i> Draft
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-archive"></i> Archived
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        {{ $document->created_at->format('M d, Y') }}<br>
                                        <span class="text-muted">{{ $document->created_at->diffForHumans() }}</span>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('documents.show', $document->id) }}" 
                                           class="btn btn-info" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('documents.download', $document->id) }}" 
                                           class="btn btn-success" 
                                           title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @can('update', $document)
                                        <a href="{{ route('documents.edit', $document->id) }}" 
                                           class="btn btn-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete', $document)
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="confirmDelete({{ $document->id }})" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                    
                                    <form id="delete-form-{{ $document->id }}" 
                                          action="{{ route('documents.destroy', $document->id) }}" 
                                          method="POST" 
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} documents
                </div>
                <div>
                    {{ $documents->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No documents found</h5>
                <p class="text-muted">Start by uploading your first document</p>
                <a href="{{ route('documents.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Upload Document
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(documentId) {
    if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
        document.getElementById('delete-form-' + documentId).submit();
    }
}
</script>
@endpush