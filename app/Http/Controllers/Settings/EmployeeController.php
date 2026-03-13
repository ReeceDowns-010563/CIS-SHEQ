<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Site;
use App\Models\Branch;
use App\Scopes\SiteOwnedScope;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // For users with all access, bypass the SiteOwnedScope specifically
        if ($user && ($user->isAdmin() || $user->hasAccessToAllSites() || $user->hasAccessToAllBranches())) {
            $query = Employee::withoutGlobalScope(SiteOwnedScope::class)->with(['site', 'site.branch']);
        } else {
            $query = Employee::with(['site', 'site.branch']);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by site
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->whereHas('site', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Filter by status - Default to active unless explicitly filtered
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: only show active employees when no status filter is applied
            $query->where('status', 'active');
        }

        $employees = $query->orderBy('first_name')->paginate(15);
        $sites = Site::orderBy('name')->get();
        $branches = Branch::orderBy('branch_name')->get();

        return view('settings.employees.index', compact('employees', 'sites', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sites = Site::active()->with('branch')->orderBy('name')->get();
        return view('settings.employees.create', compact('sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|string|max:255|unique:employees',
            'timegate_id' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'number' => 'nullable|string|max:255',
            'site_id' => 'required|exists:sites,id',
            'status' => 'required|in:active,leaver',
        ]);

        Employee::create($request->all());

        return redirect()->route('settings.employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $sites = Site::active()->with('branch')->orderBy('name')->get();
        return view('settings.employees.edit', compact('employee', 'sites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_number' => ['required', 'string', 'max:255', Rule::unique('employees')->ignore($employee)],
            'timegate_id' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('employees')->ignore($employee)],
            'number' => 'nullable|string|max:255',
            'site_id' => 'required|exists:sites,id',
            'status' => 'required|in:active,leaver',
        ]);

        $employee->update($request->all());

        return redirect()->route('settings.employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Mark the employee as a leaver instead of deleting them.
     * This implements a soft delete by setting status to 'leaver'.
     */
    public function destroy(Employee $employee)
    {
        $employeeName = $employee->full_name;

        try {
            // Instead of deleting, mark as leaver
            $employee->update(['status' => 'leaver']);

            return redirect()->route('settings.employees.index')
                ->with('success', "Employee '{$employeeName}' has been marked as a leaver successfully.");
        } catch (\Exception $e) {
            return redirect()->route('settings.employees.index')
                ->with('error', "Unable to mark employee '{$employeeName}' as a leaver. Please try again.");
        }
    }
}
