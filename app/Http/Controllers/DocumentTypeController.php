<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::latest()->paginate(10);
        return view('document_types.index', compact('documentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DocumentType::create($validated);

        return redirect()->route('document-types.index')->with('success', 'Document type created successfully!');
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $documentType->update($validated);

        return redirect()->route('document-types.index')->with('success', 'Document type updated successfully!');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        return redirect()->route('document-types.index')->with('success', 'Document type deleted successfully!');
    }
}
