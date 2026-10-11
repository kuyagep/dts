<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('offices')->latest()->paginate(10);
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully!');
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:20|unique:departments,code,' . $department->id,
            'name'        => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $department->update($validated);

        return redirect()->back()->with('success', "Department '{$department->name}' updated successfully.");
    }

    public function destroy(Department $department)
    {
        if ($department->offices()->count() > 0) {
            return redirect()->route('departments.index')->with('error', 'Cannot delete department with associated offices.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }
}
