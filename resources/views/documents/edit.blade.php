<!-- resources/views/documents/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Document')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="fas fa-edit"></i> Edit Document
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" 
                      action="{{ route('documents.update', $document->id) }}" 
                      enctype="multipart/form-data"
                      id="documentEditForm">
                    @csrf
                    @method('PUT')

                    <!-- Current Document Info -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Current Document Information</h6>
                        <strong>File:</strong> {{ $document->file_name }}<br>
                        <strong>Size:</strong> {{ number_format($document->file_size / 1024, 2) }} KB<br>
                        <strong>Version:</strong> v{{ $document->version }}<br>
                        <strong>Uploaded:</strong> {{ $document->created_at->format('M d, Y h:i A') }}<br>
                        <strong>Last Modified:</strong> {{ $document->updated_at->diffForHumans() }}
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">
                            Document Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $document->title) }}" 
                               placeholder="Enter document title"
                               required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Enter document description">{{ old('description', $document->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" 
                                id="category_id" 
                                name="category_id">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                    @if($category->parent)
                                        ({{ $category->parent->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="draft" {{ old('status', $document->status) == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>
                            <option value="published" {{ old('status', $document->status) == 'published' ? 'selected' : '' }}>
                                Published
                            </option>
                            <option value="archived" {{ old('status', $document->status) == 'archived' ? 'selected' : '' }}>
                                Archived
                            </option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Replace File (Optional) -->
                    <div class="form-group">
                        <label for="file">
                            Replace File (Optional)
                        </label>
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif"
                                   onchange="updateFileName(this)">
                            <label class="custom-file-label" for="file" id="file-label">
                                Choose new file...
                            </label>
                            @error('file')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Leave empty to keep current file. 
                            Uploading a new file will create a new version.<br>
                            <strong>Max size:</strong> 10MB
                        </small>
                        
                        <!-- New File Preview -->
                        <div id="file-preview" class="mt-3" style="display: none;">
                            <div class="alert alert-warning">
                                <strong><i class="fas fa-exclamation-triangle"></i> New File Selected:</strong><br>
                                <strong>Name:</strong> <span id="file-name"></span><br>
                                <strong>Size:</strong> <span id="file-size"></span><br>
                                <strong>Type:</strong> <span id="file-type"></span><br>
                                <em>This will become version {{ $document->version + 1 }}</em>
                            </div>
                        </div>
                    </div>

                    <!-- Version History Link -->
                    @if($document->versions->count() > 0)
                    <div class="alert alert-secondary">
                        <i class="fas fa-history"></i> 
                        This document has {{ $document->versions->count() }} previous version(s).
                        <a href="#versionHistory" data-toggle="collapse">View History</a>
                        
                        <div id="versionHistory" class="collapse mt-2">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Version</th>
                                        <th>File</th>
                                        <th>Date</th>
                                        <th>By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($document->versions as $version)
                                    <tr>
                                        <td>v{{ $version->version_number }}</td>
                                        <td>{{ $version->file_name }}</td>
                                        <td>{{ $version->created_at->format('M d, Y') }}</td>
                                        <td>{{ $version->uploader->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update Document
                        </button>
                        <a href="{{ route('documents.show', $document->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Back to List
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Warning Message -->
        <div class="alert alert-warning mt-3">
            <h6><i class="fas fa-exclamation-triangle"></i> Important Notes</h6>
            <ul class="mb-0 small">
                <li>Updating document details (title, description, etc.) will NOT create a new version</li>
                <li>Uploading a new file WILL create a new version and preserve the old file</li>
                <li>All previous versions are kept and can be restored if needed</li>
                <li>Users with access will be notified of changes</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateFileName(input) {
    if (input.files.length > 0) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024).toFixed(2);
        const fileType = input.files[0].type || 'Unknown';
        
        document.getElementById('file-label').textContent = fileName;
        document.getElementById('file-name').textContent = fileName;
        document.getElementById('file-size').textContent = fileSize + ' KB';
        document.getElementById('file-type').textContent = fileType;
        document.getElementById('file-preview').style.display = 'block';
    }
}

// Warn user if they're leaving without saving
let formChanged = false;
document.querySelectorAll('#documentEditForm input, #documentEditForm select, #documentEditForm textarea').forEach(element => {
    element.addEventListener('change', function() {
        formChanged = true;
    });
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});

document.getElementById('documentEditForm').addEventListener('submit', function() {
    formChanged = false;
});
</script>
@endpush