<!-- resources/views/documents/show.blade.php -->
@extends('layouts.app')

@section('title', $document->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Document Details Card -->
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-file-alt"></i> {{ $document->title }}
                </h4>
                <span class="badge badge-light">
                    Version {{ $document->version }}
                </span>
            </div>
            <div class="card-body">
                <!-- Status Badge -->
                <div class="mb-3">
                    @if($document->status == 'published')
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-check-circle"></i> Published
                        </span>
                    @elseif($document->status == 'draft')
                        <span class="badge badge-warning badge-lg">
                            <i class="fas fa-edit"></i> Draft
                        </span>
                    @else
                        <span class="badge badge-secondary badge-lg">
                            <i class="fas fa-archive"></i> Archived
                        </span>
                    @endif
                </div>

                <!-- Description -->
                @if($document->description)
                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="text-muted">{{ $document->description }}</p>
                </div>
                @endif

                <!-- Document Info Table -->
                <h5>Document Information</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">File Name</th>
                            <td>{{ $document->file_name }}</td>
                        </tr>
                        <tr>
                            <th>File Size</th>
                            <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                        </tr>
                        <tr>
                            <th>File Type</th>
                            <td>
                                @php
                                    $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                @endphp
                                <span class="badge badge-info">{{ strtoupper($extension) }}</span>
                                <small class="text-muted">({{ $document->mime_type }})</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>
                                @if($document->category)
                                    <span class="badge badge-secondary">{{ $document->category->name }}</span>
                                @else
                                    <span class="text-muted">Uncategorized</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Uploaded By</th>
                            <td>
                                <i class="fas fa-user"></i> {{ $document->uploader->name }}
                                <small class="text-muted">({{ $document->uploader->email }})</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Uploaded On</th>
                            <td>
                                {{ $document->created_at->format('F d, Y h:i A') }}
                                <small class="text-muted">({{ $document->created_at->diffForHumans() }})</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Last Modified</th>
                            <td>
                                {{ $document->updated_at->format('F d, Y h:i A') }}
                                <small class="text-muted">({{ $document->updated_at->diffForHumans() }})</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Current Version</th>
                            <td>
                                <span class="badge badge-info">Version {{ $document->version }}</span>
                                @if($document->versions->count() > 0)
                                    <small class="text-muted">
                                        ({{ $document->versions->count() }} previous version(s))
                                    </small>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Action Buttons -->
                <div class="btn-group btn-group-lg mt-3" role="group">
                    <a href="{{ route('documents.download', $document->id) }}" 
                       class="btn btn-success">
                        <i class="fas fa-download"></i> Download
                    </a>
                    
                    @can('update', $document)
                    <a href="{{ route('documents.edit', $document->id) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endcan
                    
                    @can('delete', $document)
                    <button type="button" 
                            class="btn btn-danger" 
                            onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    @endcan
                    
                    <a href="{{ route('documents.index') }}" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <form id="delete-form" 
                      action="{{ route('documents.destroy', $document->id) }}" 
                      method="POST" 
                      style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        <!-- Version History -->
        @if($document->versions->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history"></i> Version History
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>File Name</th>
                                <th>Size</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($document->versions->sortByDesc('version_number') as $version)
                            <tr>
                                <td>
                                    <span class="badge badge-info">v{{ $version->version_number }}</span>
                                </td>
                                <td>{{ $version->file_name }}</td>
                                <td>{{ number_format($version->file_size / 1024, 2) }} KB</td>
                                <td>{{ $version->uploader->name }}</td>
                                <td>
                                    {{ $version->created_at->format('M d, Y h:i A') }}
                                    <br>
                                    <small class="text-muted">{{ $version->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" 
                                            onclick="alert('Download version feature coming soon!')">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('documents.download', $document->id) }}" 
                       class="list-group-item list-group-item-action">
                        <i class="fas fa-download text-success"></i> Download Document
                    </a>
                    
                    @can('update', $document)
                    <a href="{{ route('documents.edit', $document->id) }}" 
                       class="list-group-item list-group-item-action">
                        <i class="fas fa-edit text-warning"></i> Edit Details
                    </a>
                    @endcan
                    
                    <a href="#" 
                       class="list-group-item list-group-item-action"
                       onclick="shareDocument(); return false;">
                        <i class="fas fa-share text-info"></i> Share Document
                    </a>
                    
                    <a href="#" 
                       class="list-group-item list-group-item-action"
                       onclick="printDocument(); return false;">
                        <i class="fas fa-print text-secondary"></i> Print Details
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Statistics
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-eye text-primary"></i>
                        <strong>Views:</strong> 0
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-download text-success"></i>
                        <strong>Downloads:</strong> 0
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-code-branch text-info"></i>
                        <strong>Versions:</strong> {{ $document->versions->count() + 1 }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-share text-warning"></i>
                        <strong>Shared with:</strong> 0 user(s)
                    </li>
                </ul>
            </div>
        </div>

        <!-- Related Documents -->
        @if($document->category)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-folder"></i> Related Documents
                </h5>
            </div>
            <div class="card-body">
                @php
                    $relatedDocs = \App\Models\Document::where('category_id', $document->category_id)
                        ->where('id', '!=', $document->id)
                        ->where('status', 'published')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($relatedDocs->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($relatedDocs as $relatedDoc)
                        <li class="mb-2">
                            <a href="{{ route('documents.show', $relatedDoc->id) }}">
                                <i class="fas fa-file-alt"></i>
                                {{ Str::limit($relatedDoc->title, 30) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No related documents found</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this document?\n\nThis action cannot be undone and will delete all versions.')) {
        document.getElementById('delete-form').submit();
    }
}

function shareDocument() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: '{{ $document->title }}',
            text: 'Check out this document',
            url: url
        });
    } else {
        prompt('Copy this link to share:', url);
    }
}

function printDocument() {
    window.print();
}
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn-group, .card-header, nav, .sidebar {
        display: none !important;
    }
}

.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}
</style>
@endpush