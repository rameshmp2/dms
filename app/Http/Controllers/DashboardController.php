<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $totalDocuments = Document::where('uploaded_by', $user->id)->count();
        $recentDocuments = Document::where('uploaded_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact('user', 'totalDocuments', 'recentDocuments'));
    }
}