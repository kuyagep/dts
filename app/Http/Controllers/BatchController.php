<?php

namespace App\Http\Controllers;

use App\Models\BatchTransmittal;
use App\Models\Document;
use App\Models\DocumentRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BatchController extends Controller
{
    public function dispatchBatch(Request $request)
    {
        $validated = $request->validate([
            'document_ids'   => 'required|array|min:1',
            'document_ids.*' => 'exists:documents,id',
            'to_office_id'   => 'required|exists:offices,id',
            'remarks'        => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        DB::beginTransaction();
        try {
            // 1. Create Master Batch Transmittal Record
            $batch = BatchTransmittal::create([
                'batch_number'   => 'TR-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'from_office_id' => $user->office_id,
                'to_office_id'   => $validated['to_office_id'],
                'dispatched_by'  => $user->id,
                'status'         => 'In Transit',
                'remarks'        => $validated['remarks'] ?? null,
            ]);

            // 2. Attach selected documents to the batch and record routes
            foreach ($validated['document_ids'] as $docId) {
                $doc = Document::findOrFail($docId);

                $doc->update([
                    'status'            => 'In Transit',
                    'current_office_id' => $validated['to_office_id'],
                ]);

                DocumentRoute::create([
                    'document_id'          => $doc->id,
                    'batch_transmittal_id' => $batch->id,
                    'from_office_id'       => $user->office_id,
                    'to_office_id'         => $validated['to_office_id'],
                    // 'action_taken'         => 'Dispatched via Batch Transmittal',
                    'status'               => 'In Transit',
                    'remarks'              => $validated['remarks'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Batch transmittal created successfully.',
                'batch_number' => $batch->batch_number,
                'print_url'    => route('batches.print', $batch->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process batch dispatch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display printable batch transmittal slip.
     */
    public function printBatch($id)
    {
        $batch = BatchTransmittal::with([
            'routes.document.documentType',
            'fromOffice',
            'toOffice',
            'dispatcher'
        ])->findOrFail($id);

        return view('batches.print', compact('batch'));
    }

    /**
     * Display batch contents when scanned via QR code.
     */
    public function showReceiveView($batch_number)
    {
        $batch = BatchTransmittal::where('batch_number', $batch_number)
            ->with(['routes.document.documentType', 'fromOffice', 'toOffice', 'dispatcher'])
            ->firstOrFail();

        // Ensure user belongs to destination office
        if (auth()->user()->office_id !== $batch->to_office_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized: This batch transmittal is addressed to ' . $batch->toOffice->name);
        }

        return view('batches.receive', compact('batch'));
    }

    /**
     * Process verified batch receipts and handle rejected/missing items.
     */
    public function processBatchReceive(Request $request, $id)
    {
        $batch = BatchTransmittal::findOrFail($id);
        $user = auth()->user();

        $receivedDocIds = $request->input('received_documents', []);
        $actionNotes = $request->input('action_notes', []);

        DB::beginTransaction();
        try {
            foreach ($batch->routes as $route) {
                $doc = $route->document;
                $note = $actionNotes[$doc->id] ?? 'Processed during batch receipt';

                if (in_array($doc->id, $receivedDocIds)) {
                    // Mark Received
                    $doc->update([
                        'status' => 'Received',
                        'current_office_id' => $user->office_id,
                        'current_custodian_id' => $user->id,
                    ]);

                    $route->update([
                        'status' => 'Received',
                        'received_at' => now(),
                        'remarks' => $note,
                    ]);
                } else {
                    // Physical Document Missing -> Reject & Flag Return
                    $doc->update([
                        'status' => 'Rejected',
                        'current_office_id' => $batch->from_office_id,
                    ]);

                    $route->update([
                        'status' => 'Rejected',
                        'remarks' => 'REJECTED: Physical document missing during intake. Note: ' . $note,
                    ]);

                    DocumentRoute::create([
                        'document_id' => $doc->id,
                        'from_office_id' => $user->office_id,
                        'to_office_id' => $batch->from_office_id,
                        // 'action_taken' => 'Rejected / Physical Copy Missing',
                        'status' => 'Rejected',
                        'remarks' => 'Returned automatically via Batch Receipt discrepancy.'
                    ]);
                }
            }

            $batch->update([
                'status' => count($receivedDocIds) === $batch->routes->count() ? 'Received' : 'Partially Received',
                'received_by' => $user->id,
                'received_at' => now(),
                'remarks' => $request->input('batch_remarks'),
            ]);

            DB::commit();

            return redirect()->route('documents.incoming')
                ->with('success', "Batch {$batch->batch_number} processed. " . count($receivedDocIds) . " items received.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error processing batch receipt: ' . $e->getMessage());
        }
    }
}
