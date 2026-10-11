<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display a listing of offices.
     */
    public function index()
    {
        $offices = Office::with('department')->latest()->paginate(10);
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('offices.index', compact('offices', 'departments'));
    }

    /**
     * Store a newly created office in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:offices,code',
        ]);

        Office::create($validated);

        return redirect()->route('offices.index')->with('success', 'Office created successfully!');
    }

    /**
     * Update the specified office in storage.
     */
    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'code'          => 'required|string|max:20|unique:offices,code,' . $office->id,
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $office->update($validated);

        return redirect()->back()->with('success', "Office '{$office->name}' updated successfully.");
    }
    /**
     * Remove the specified office from storage.
     */
    public function destroy(Office $office)
    {
        $office->delete();

        return redirect()->route('offices.index')->with('success', 'Office deleted successfully!');
    }
}
