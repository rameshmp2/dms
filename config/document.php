<?php
// config/document.php

return [
    /*
    |--------------------------------------------------------------------------
    | Document Storage Configuration
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'disk' => env('DOCUMENT_STORAGE_DISK', 'public'),
        'path' => env('DOCUMENT_STORAGE_PATH', 'documents'),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Limits
    |--------------------------------------------------------------------------
    */
    'upload' => [
        'max_size' => env('DOCUMENT_MAX_SIZE', 10240), // KB
        'allowed_mimes' => [
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 
            'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png', 'gif'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Versioning Configuration
    |--------------------------------------------------------------------------
    */
    'versioning' => [
        'enabled' => env('DOCUMENT_VERSIONING_ENABLED', true),
        'max_versions' => env('DOCUMENT_MAX_VERSIONS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'scan_uploads' => env('DOCUMENT_SCAN_UPLOADS', false),
        'encrypt_storage' => env('DOCUMENT_ENCRYPT_STORAGE', false),
    ],
];