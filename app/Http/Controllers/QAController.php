<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;

class QAController extends Controller
{
    public function pending()
    {
        $pendingDocuments = Document::where('status', 'pending')
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['Area-Member', 'Area-Chair']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('documents.pending', compact('pendingDocuments'));
    }
}
