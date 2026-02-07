<!-- resources/views/documents/create.blade.php -->
@extends('layouts.app')

@section('title', 'Upload Document')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-upload"></i> Upload New Document
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" 
                      action="{{ route('documents.store') }}" 
                      enctype="multipart/form-data"
                      id="documentForm">
                    @csrf

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">
                            Document Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               placeholder="Enter document title"
                               required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Give your document a descriptive title
                        </small>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Enter document description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Provide additional details about this document
                        </small>
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
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <small class="form-text text-muted">
                            Select a category to organize your document
                        </small>
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
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                Published
                            </option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                                Archived
                            </option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <strong>Draft:</strong> Only you can see | 
                            <strong>Published:</strong> Visible to others | 
                            <strong>Archived:</strong> Hidden from lists
                        </small>
                    </div>

                    <!-- File Upload -->
                    <div class="form-group">
                        <label for="file">
                            Upload File <span class="text-danger">*</span>
                        </label>
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif"
                                   required
                                   onchange="updateFileName(this)">
                            <label class="custom-file-label" for="file" id="file-label">
                                Choose file...
                            </label>
                            @error('file')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            <strong>Allowed formats:</strong> PDF, Word, Excel, PowerPoint, Text, Images<br>
                            <strong>Maximum size:</strong> 10MB
                        </small>
                        
                        <!-- File Preview Area -->
                        <div id="file-preview" class="mt-3" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Selected File:</strong> <span id="file-name"></span><br>
                                <strong>Size:</strong> <span id="file-size"></span><br>
                                <strong>Type:</strong> <span id="file-type"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Document
                        </button>
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Section -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-info-circle"></i> Tips for Uploading Documents</h6>
                <ul class="mb-0 small">
                    <li>Use descriptive titles to make documents easy to find</li>
                    <li>Add detailed descriptions for better context</li>
                    <li>Organize documents using categories</li>
                    <li>Save as "Draft" if the document isn't ready to share</li>
                    <li>Make sure files are virus-free before uploading</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Update file name label and show preview
function updateFileName(input) {
    const fileName = input.files[0].name;
    const fileSize = (input.files[0].size / 1024).toFixed(2); // Convert to KB
    const fileType = input.files[0].type || 'Unknown';
    
    // Update label
    document.getElementById('file-label').textContent = fileName;
    
    // Show preview
    document.getElementById('file-name').textContent = fileName;
    document.getElementById('file-size').textContent = fileSize + ' KB';
    document.getElementById('file-type').textContent = fileType;
    document.getElementById('file-preview').style.display = 'block';
}

// Auto-generate title from filename if title is empty
document.getElementById('file').addEventListener('change', function(e) {
    const titleInput = document.getElementById('title');
    if (!titleInput.value && e.target.files.length > 0) {
        const fileName = e.target.files[0].name;
        // Remove extension and replace underscores/hyphens with spaces
        const suggestedTitle = fileName
            .replace(/\.[^/.]+$/, '')
            .replace(/[_-]/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase());
        
        titleInput.value = suggestedTitle;
    }
});

// Form validation
document.getElementById('documentForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('file');
    
    if (fileInput.files.length > 0) {
        const fileSize = fileInput.files[0].size / 1024 / 1024; // Convert to MB
        
        if (fileSize > 10) {
            e.preventDefault();
            alert('File size exceeds 10MB limit. Please choose a smaller file.');
            return false;
        }
    }
});
</script>
@endpush