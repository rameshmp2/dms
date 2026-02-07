<?php
// app/Http/Requests/StoreDocumentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', \App\Models\Document::class);
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png|max:10240', // 10MB
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Document title is required',
            'file.required' => 'Please upload a file',
            'file.mimes' => 'Only PDF, Word, Excel, PowerPoint, Text, and Image files are allowed',
            'file.max' => 'File size must not exceed 10MB',
        ];
    }
}