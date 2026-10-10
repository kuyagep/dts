<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentLog;
use App\Models\DocumentRoute;
use App\Models\DocumentType;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Display listing of all tracked documents.
     */
    public function index(Request $request)
    {
        $query = Document::with(['documentType', 'originatingOffice', 'currentOffice', 'currentCustodian']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->latest()->paginate(15);

        return view('documents.index', compact('documents'));
    }

    /**
     * Display queue of incoming documents for the user's office.
     */
    public function incoming()
    {
        $userOfficeId = auth()->user()->office_id;

        $documents = Document::with(['documentType', 'originatingOffice', 'creator'])
            ->where('status', 'In Transit')
            ->whereHas('routes', function ($q) use ($userOfficeId) {
                $q->where('to_office_id', $userOfficeId)->where('status', 'In Transit');
            })
            ->latest()
            ->paginate(15);

        return view('documents.incoming', compact('documents'));
    }

    /**
     * Display archived documents.
     */
    public function archived()
    {
        $documents = Document::with(['documentType', 'originatingOffice'])
            ->where('status', 'Archived')
            ->latest()
            ->paginate(15);

        return view('documents.archived', compact('documents'));
    }

    /**
     * Show form for creating a document.
     */
    public function create()
    {
        $documentTypes = DocumentType::where('is_active', true)->orderBy('name')->get();
        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('documents.create', compact('documentTypes', 'offices'));
    }

    /**
     * Store new document and dispatch route.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'urgency' => 'required|in:Normal,Urgent,Immediate',
            'destination_office_id' => 'required|exists:offices,id',
            'description' => 'nullable|string',
            'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
            'remarks' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        DB::transaction(function () use ($validated, $user, $request, &$document) {
            $trackingNumber = 'DIV-' . now()->format('Ym') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $filePath = $request->file('attachment')->store('documents/' . now()->format('Y/m'), 'public');

            $document = Document::create([
                'tracking_number' => $trackingNumber,
                'title' => $validated['title'],
                'document_type_id' => $validated['document_type_id'],
                'description' => $validated['description'] ?? null,
                'urgency' => $validated['urgency'],
                'status' => 'In Transit',
                'originating_office_id' => $user->office_id,
                'current_office_id' => $user->office_id,
                'created_by' => $user->id,
                'current_custodian_id' => $user->id,
                'file_path' => $filePath,
            ]);

            DocumentRoute::create([
                'document_id' => $document->id,
                'step_number' => 1,
                'from_office_id' => $user->office_id,
                'to_office_id' => $validated['destination_office_id'],
                'status' => 'In Transit',
                'remarks' => $validated['remarks'] ?? 'Document created and dispatched.',
            ]);

            DocumentLog::create([
                'document_id' => $document->id,
                'user_id' => $user->id,
                'action' => 'CREATED',
                'from_status' => 'Draft',
                'to_status' => 'In Transit',
                'remarks' => 'Document generated and routed.',
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('documents.show', $document->id)
            ->with('success', "Document {$document->tracking_number} created successfully!");
    }

    /**
     * Show document details, routing path, and audit log.
     */
    public function show(Document $document)
    {
        $document->load([
            'documentType',
            'originatingOffice',
            'currentOffice',
            'creator',
            'currentCustodian',
            'routes.fromOffice',
            'routes.toOffice',
            'routes.assignedUser',
            'logs.user'
        ]);

        $offices = Office::where('is_active', true)->where('id', '!=', auth()->user()->office_id)->get();

        return view('documents.show', compact('document', 'offices'));
    }
}
