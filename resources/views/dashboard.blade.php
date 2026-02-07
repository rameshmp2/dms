<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </h2>
    </div>
</div>

<!-- Statistics Cards - RESPONSIVE VERSION -->
<div class="row">
    <!-- Total Documents -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white mb-2">
                            <i class="fas fa-file"></i> Total Documents
                        </h6>
                        <h2 class="mb-0">{{ $totalDocuments }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-file fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary-dark">
                <a href="{{ route('documents.index') }}" class="text-white text-decoration-none small">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- This Month -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-4">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white mb-2">
                            <i class="fas fa-upload"></i> This Month
                        </h6>
                        <h2 class="mb-0">
                            @php
                                $thisMonthCount = \App\Models\Document::where('uploaded_by', auth()->id())
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
                            @endphp
                            {{ $thisMonthCount }}
                        </h2>
                        <small class="text-white-50">Uploaded</small>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-success-dark">
                <span class="text-white small">
                    <i class="fas fa-info-circle"></i> Current month only
                </span>
            </div>
        </div>
    </div>

    <!-- Shared With Me -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-4">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white mb-2">
                            <i class="fas fa-share"></i> Shared With Me
                        </h6>
                        <h2 class="mb-0">
                            @php
                                $sharedCount = \App\Models\DocumentPermission::where('user_id', auth()->id())
                                    ->count();
                            @endphp
                            {{ $sharedCount }}
                        </h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-info-dark">
                <a href="#" class="text-white text-decoration-none small">
                    View Shared <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-4">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white mb-2">
                            <i class="fas fa-clock"></i> Recent Activity
                        </h6>
                        <h2 class="mb-0">{{ $recentDocuments->count() }}</h2>
                        <small class="text-white-50">Last 5 docs</small>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-history fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-warning-dark">
                <span class="text-white small">
                    <i class="fas fa-info-circle"></i> Last 7 days
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Documents -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-history"></i> Recent Documents
                </h5>
                <a href="{{ route('documents.index') }}" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($recentDocuments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDocuments as $document)
                                    <tr>
                                        <td>
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            {{ Str::limit($document->title, 50) }}
                                        </td>
                                        <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                        <td>
                                            <span class="badge badge-{{ $document->status == 'published' ? 'success' : 'warning' }}">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $document->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('documents.show', $document->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No documents yet</p>
                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Upload Your First Document
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row (Optional) -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line"></i> Document Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="stat-box">
                            <h4 class="text-primary">
                                @php
                                    $publishedCount = \App\Models\Document::where('uploaded_by', auth()->id())
                                        ->where('status', 'published')
                                        ->count();
                                @endphp
                                {{ $publishedCount }}
                            </h4>
                            <p class="text-muted mb-0">Published</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-box">
                            <h4 class="text-warning">
                                @php
                                    $draftCount = \App\Models\Document::where('uploaded_by', auth()->id())
                                        ->where('status', 'draft')
                                        ->count();
                                @endphp
                                {{ $draftCount }}
                            </h4>
                            <p class="text-muted mb-0">Drafts</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-box">
                            <h4 class="text-secondary">
                                @php
                                    $archivedCount = \App\Models\Document::where('uploaded_by', auth()->id())
                                        ->where('status', 'archived')
                                        ->count();
                                @endphp
                                {{ $archivedCount }}
                            </h4>
                            <p class="text-muted mb-0">Archived</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle"></i> Quick Info
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-user text-primary"></i>
                        <strong>Name:</strong> {{ auth()->user()->name }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-shield-alt text-success"></i>
                        <strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-clock text-info"></i>
                        <strong>Timezone:</strong> {{ auth()->user()->timezone ?? 'UTC' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar text-warning"></i>
                        <strong>Member since:</strong> {{ auth()->user()->created_at->format('M Y') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Responsive Card Styles */
.stat-icon {
    opacity: 0.3;
}

.bg-primary-dark {
    background-color: rgba(0, 0, 0, 0.1);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.bg-success-dark {
    background-color: rgba(0, 0, 0, 0.1);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.bg-info-dark {
    background-color: rgba(0, 0, 0, 0.1);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.bg-warning-dark {
    background-color: rgba(0, 0, 0, 0.1);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.card-footer {
    padding: 0.75rem 1.25rem;
}

.opacity-50 {
    opacity: 0.5;
}

/* Make sure cards are same height */
.h-100 {
    height: 100%;
}

/* Responsive text sizing */
@media (max-width: 1199.98px) {
    .card-body h2 {
        font-size: 1.75rem;
    }
    
    .stat-icon i {
        font-size: 2rem !important;
    }
}

@media (max-width: 767.98px) {
    .card-body h6 {
        font-size: 0.9rem;
    }
    
    .card-body h2 {
        font-size: 1.5rem;
    }
    
    .card-body small {
        font-size: 0.75rem;
    }
    
    .stat-icon {
        display: none; /* Hide icon on very small screens */
    }
}

@media (max-width: 575.98px) {
    .card-body {
        padding: 1rem;
    }
    
    .card-body h6 {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    
    .card-body h2 {
        font-size: 1.25rem;
    }
}

/* Stat box styling */
.stat-box {
    padding: 1rem;
    border-radius: 0.25rem;
    background-color: #f8f9fa;
}

.stat-box h4 {
    margin-bottom: 0.5rem;
    font-weight: bold;
}
</style>
@endpush