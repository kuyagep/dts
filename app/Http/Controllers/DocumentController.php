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
     * Move document to Archived Vault.
     */
    public function archive(Request $request, Document $document)
    {
        $user = auth()->user();

        DB::transaction(function () use ($document, $user, $request) {
            $document->update([
                'status' => 'Archived',
                'current_custodian_id' => $user->id,
            ]);

            DocumentLog::create([
                'document_id' => $document->id,
                'user_id'     => $user->id,
                'office_id'   => $user->office_id,
                'action'      => 'ARCHIVED',
                'remarks'     => $request->input('remarks', 'Filed in official archive vault.'),
            ]);
        });

        return redirect()->route('documents.pending')->with('success', "Document {$document->tracking_number} moved to archive vault.");
    }
    /**
     * Display the Archived Vault.
     * Regular users see only documents they created/originated.
     * Admins/Super Admins see all archived documents.
     */
    public function archived()
    {
        $user = auth()->user();

        $query = Document::where('status', 'Archived')
            ->with(['documentType', 'originatingOffice', 'creator', 'currentOffice']);

        // Non-admin users can only view archived documents if they are the original owner/creator
        if (!$user->hasAnyRole(['Super Admin', 'Administrator'])) {
            $query->where('created_by', $user->id);
        }

        $documents = $query->latest()->paginate(10);

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
            'priority' => 'required|in:Normal,Urgent,Immediate',
            'destination_office_id' => 'required|exists:offices,id',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        DB::transaction(function () use ($validated, $user, $request, &$document) {
            $trackingNumber = 'DIV-' . now()->format('Ym') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $document = Document::create([
                'tracking_number' => $trackingNumber,
                'title' => $validated['title'],
                'document_type_id' => $validated['document_type_id'],
                'description' => $validated['description'] ?? null,
                'priority' => $validated['priority'],
                'status' => 'In Transit',
                'originating_office_id' => $user->office_id,
                'current_office_id' => $user->office_id,
                'destination_office_id' => $validated['destination_office_id'],
                'created_by' => $user->id,
                'current_custodian_id' => $user->id,
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
     * Display pending documents for current office queue.
     */
    public function pending()
    {
        $user = auth()->user();

        $documents = Document::where('current_office_id', $user->office_id)
            ->whereIn('status', ['Received', 'In Review'])
            ->with(['documentType', 'originatingOffice', 'currentCustodian'])
            ->latest()
            ->paginate(10);

        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('documents.pending', compact('documents', 'offices'));
    }

    /**
     * Mark document as Released to recipient.
     */
    public function release(Request $request, Document $document)
    {
        $validated = $request->validate([
            'released_to' => 'required|string|max:255',
            'remarks'     => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        DB::transaction(function () use ($document, $user, $validated) {
            $document->update([
                'status' => 'Released',
                'current_custodian_id' => $user->id,
            ]);

            DocumentLog::create([
                'document_id' => $document->id,
                'user_id'     => $user->id,
                'action'      => 'RELEASED',
                'remarks'     => "Released to: {$validated['released_to']}. Notes: " . ($validated['remarks'] ?? 'None'),
            ]);
        });

        return redirect()->route('documents.pending')->with('success', "Document {$document->tracking_number} released successfully.");
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


    /**
     * Mark document as Received by current office/custodian.
     */
    public function receive(Request $request, Document $document)
    {
        $user = auth()->user();

        DB::transaction(function () use ($document, $user, $request) {
            // Update document state
            $document->update([
                'status' => 'Received',
                'current_office_id' => $user->office_id,
                'current_custodian_id' => $user->id,

            ]);

            // Record in audit log
            DocumentLog::create([
                'document_id' => $document->id,
                'user_id'     => $user->id,
                'office_id'   => $user->office_id,
                'action'      => 'RECEIVED',
                'remarks'     => $request->input('remarks', 'Document received into office queue.'),
            ]);
        });

        return redirect()->back()->with('success', "Document {$document->tracking_number} received successfully.");
    }

    /**
     * Dispatch / Forward document to another office.
     */
    public function forward(Request $request, Document $document)
    {
        $validated = $request->validate([
            'to_office_id' => 'required|exists:offices,id',
            'remarks'      => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $fromOfficeId = $document->current_office_id;

        DB::transaction(function () use ($document, $user, $validated, $fromOfficeId) {
            // Update document state to In Transit
            $document->update([
                'status' => 'In Transit',
                'current_office_id' => $validated['to_office_id'],
                'current_custodian_id' => null, // Pending intake by target office clerk
                'destination_office_id' => $validated['to_office_id'],
            ]);

            // Create route transition history
            DocumentRoute::create([
                'document_id'    => $document->id,
                'from_office_id' => $fromOfficeId,
                'to_office_id'   => $validated['to_office_id'],
                'status'   => 'In Transit',
                'remarks'        => $validated['remarks'] ?? null,
                'step_number' => 1,
                // 'sender_id'      => $user->id,
            ]);

            // Audit log
            DocumentLog::create([
                'document_id' => $document->id,
                'user_id'     => $user->id,
                'office_id'   => $fromOfficeId,
                'action'      => 'FORWARDED',
                'remarks'     => $validated['remarks'] ?? "Forwarded to Office ID: {$validated['to_office_id']}",
                // 'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('documents.index')->with('success', "Document {$document->tracking_number} dispatched successfully.");
    }

    /**
     * Finalize and Approve document.
     */
    public function approve(Request $request, Document $document)
    {
        $user = auth()->user();

        DB::transaction(function () use ($document, $user, $request) {
            // Mark document as Approved
            $document->update([
                'status' => 'Approved',
                'current_custodian_id' => $user->id,
            ]);

            // Audit log
            DocumentLog::create([
                'document_id' => $document->id,
                'user_id'     => $user->id,
                'office_id'   => $user->office_id,
                'action'      => 'APPROVED',
                'remarks'     => $request->input('remarks', 'Document officially approved.'),
            ]);
        });

        return redirect()->back()->with('success', "Document {$document->tracking_number} approved and finalized.");
    }

    /**
     * Display list of documents dispatched / forwarded from current office.
     */
    public function forwarded()
    {
        $user = auth()->user();

        // Fetch documents where the user's office is the originating office OR has routed the document out
        $documents = Document::where('originating_office_id', $user->office_id)
            ->where('current_office_id', '!=', $user->office_id)
            ->with(['documentType', 'currentOffice', 'creator'])
            ->latest()
            ->paginate(10);

        return view('documents.forwarded', compact('documents'));
    }

    /**
     * Mark document as Completed / Filed in the current office.
     */
    public function complete(Request $request, Document $document)
    {
        $user = auth()->user();

        DB::transaction(function () use ($document, $user, $request) {
            // Update status to Completed and assign current user as final custodian
            $document->update([
                'status' => 'Completed',
                'current_custodian_id' => $user->id,
            ]);

            // Create entry in Audit Log
            DocumentLog::create([
                'document_id' => $document->id,
                'user_id'     => $user->id,
                'office_id'   => $user->office_id,
                'action'      => 'COMPLETED',
                'remarks'     => $request->input('remarks', 'Document action completed and filed in office.'),
            ]);
        });

        return redirect()->route('documents.pending')->with('success', "Document {$document->tracking_number} marked as Completed / Filed successfully.");
    }

    /**
     * Display list of completed / filed documents for current office.
     */
    public function completed()
    {
        $user = auth()->user();

        $documents = Document::where('current_office_id', $user->office_id)
            ->where('status', 'Completed')
            ->with(['documentType', 'originatingOffice', 'currentCustodian'])
            ->latest()
            ->paginate(10);

        return view('documents.completed', compact('documents'));
    }

    /**
     * Display list of documents currently in transit dispatched by the user's office.
     */
    public function transit()
    {
        $user = auth()->user();

        // Fetch documents where the active route from the user's office is 'In Transit'
        $documents = Document::where('status', 'In Transit')
            ->whereHas('routes', function ($query) use ($user) {
                $query->where('from_office_id', $user->office_id)
                    ->where('status', 'In Transit');
            })
            ->with(['documentType', 'currentOffice', 'creator'])
            ->latest()
            ->paginate(10);

        return view('documents.transit', compact('documents'));
    }

    /**
     * Generate printable batch transmittal slip for selected documents.
     */
    public function generateTransmittal(Request $request)
    {
        $validated = $request->validate([
            'document_ids'   => 'required|array|min:1',
            'document_ids.*' => 'exists:documents,id',
        ]);

        $documents = Document::whereIn('id', $validated['document_ids'])
            ->with(['documentType', 'currentOffice', 'originatingOffice', 'creator'])
            ->get();

        return view('documents.print_transmittal', compact('documents'));
    }
}
