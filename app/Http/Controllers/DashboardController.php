<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRoute;
use App\Models\DocumentType;
use App\Models\Office;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $officeId = auth()->user()->office_id ?? null;
        $documentTypes = DocumentType::where('is_active', true)->orderBy('name')->get();
        $offices = Office::where('is_active', true)->orderBy('name')->get();
        // Dynamic Counters
        $pendingCount = Document::where('current_office_id', $officeId)
            ->whereIn('status', ['Pending', 'Received'])
            ->count();

        $transitCount = Document::where('status', 'In Transit')
            ->whereHas('routes', function ($q) use ($officeId) {
                $q->where('from_office_id', $officeId)->where('status', 'In Transit');
            })->count();

        $inReviewCount = Document::where('current_office_id', $officeId)
            ->where('status', 'In Review')
            ->count();

        $completedCount = Document::where('status', 'Completed')->count();

        // Tables & Activity Feed
        $recentDocuments = Document::with(['documentType', 'currentOffice'])
            ->latest()
            ->take(5)
            ->get();

        $recentRoutes = DocumentRoute::with(['document', 'fromOffice', 'toOffice'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'pendingCount',
            'transitCount',
            'inReviewCount',
            'completedCount',
            'recentDocuments',
            'recentRoutes',
            'documentTypes',
            'offices'
        ));
    }
}
