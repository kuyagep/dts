<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $userOfficeId = auth()->user()->office_id;

        $metrics = [
            'total' => Document::count(),
            'pending' => Document::where('status', 'In Transit')->where('current_office_id', $userOfficeId)->count(),
            'received' => Document::where('status', 'Received')->where('current_office_id', $userOfficeId)->count(),
            'archived' => Document::where('status', 'Archived')->count(),
        ];

        $recentDocuments = Document::with(['documentType', 'originatingOffice', 'currentOffice'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('metrics', 'recentDocuments'));
    }
}
