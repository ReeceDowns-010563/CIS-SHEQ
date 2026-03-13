<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Branch::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $branches = $query->orderBy('branch_code')->paginate(15);

        return view('settings.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_code' => 'required|string|max:10|unique:branches',
            'branch_name' => 'required|string|max:255',
        ]);

        Branch::create($request->all());

        return redirect()->route('settings.branches.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return view('settings.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'branch_code' => ['required', 'string', 'max:10', Rule::unique('branches')->ignore($branch)],
            'branch_name' => 'required|string|max:255',
        ]);

        $branch->update($request->all());

        return redirect()->route('settings.branches.index')->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('settings.branches.index')->with('success', 'Branch deleted successfully.');
    }
}
