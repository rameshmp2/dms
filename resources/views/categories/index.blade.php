<!-- resources/views/categories/index.blade.php -->
@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-folder"></i> Categories</h2>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Category
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($categories->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Parent</th>
                        <th>Documents</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>
                            <i class="fas fa-folder text-warning"></i>
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td>
                            @if($category->parent)
                                <span class="badge badge-secondary">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $category->documents->count() }}</span>
                        </td>
                        <td>
                            <a href="{{ route('categories.edit', $category->id) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteCategory({{ $category->id }})" 
                                    class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                            
                            <form id="delete-category-{{ $category->id }}"
                                  action="{{ route('categories.destroy', $category->id) }}"
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
        @else
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">No categories yet</p>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create First Category
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCategory(id) {
    if (confirm('Delete this category? Documents will become uncategorized.')) {
        document.getElementById('delete-category-' + id).submit();
    }
}
</script>
@endpush