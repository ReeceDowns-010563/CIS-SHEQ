<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD and listing controller for sites, including filtering, searching, sorting,
 * and association to customers and branches.
 */
class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Only show active sites by default
        $query = Site::active()->with(['customer', 'branch']);

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by branch - now using branch_code
        if ($request->filled('branch_code')) {
            $query->where('branch_code', $request->branch_code);
        }

        // Filter by status - modified to handle the new soft delete approach
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', 1);
            } elseif ($request->status === 'inactive') {
                // Show inactive sites (overrides the active scope)
                $query = Site::where('active', 0)->with(['customer', 'branch']);

                // Re-apply other filters for inactive view
                if ($request->filled('search')) {
                    $query->search($request->search);
                }
                if ($request->filled('customer_id')) {
                    $query->where('customer_id', $request->customer_id);
                }
                if ($request->filled('branch_code')) {
                    $query->where('branch_code', $request->branch_code);
                }
            }
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Handle sorting with joins for related tables
        if ($sortField === 'customer_id') {
            $query->join('customers', 'sites.customer_id', '=', 'customers.id')
                ->orderBy('customers.name', $sortDirection)
                ->select('sites.*');
        } elseif ($sortField === 'branch_code') {
            $query->join('branches', 'sites.branch_code', '=', 'branches.branch_code')
                ->orderBy('branches.branch_name', $sortDirection)
                ->select('sites.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $sites = $query->paginate(15)->withQueryString();

        // Get all customers and branches for the filter dropdowns
        $customers = Customer::orderBy('name')->get();
        $branches = Branch::active()->orderBy('branch_code')->get();

        return view('settings.sites.index', compact('sites', 'customers', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customers = Customer::active()->orderBy('name')->get();
        $branches = Branch::active()->orderBy('branch_code')->get();

        return view('settings.sites.create', compact('customers', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'site_code' => 'nullable|string|max:50',
            'customer_id' => 'nullable|exists:customers,id',
            'branch_code' => 'required|exists:branches,branch_code',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        // Handle checkbox value for active field - default to true for new sites
        $validated['active'] = $request->has('active') ? true : true;

        Site::create($validated);

        return redirect()->route('settings.sites.index')
            ->with('success', 'Site created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site): View
    {
        $site->load(['customer', 'branch']);
        return view('settings.sites.show', compact('site'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site): View
    {
        $customers = Customer::active()->orderBy('name')->get();
        $branches = Branch::active()->orderBy('branch_code')->get();

        return view('settings.sites.edit', compact('site', 'customers', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'site_code' => 'nullable|string|max:50',
            'customer_id' => 'nullable|exists:customers,id',
            'branch_code' => 'required|exists:branches,branch_code',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        // Handle checkbox value for active field
        $validated['active'] = $request->has('active');

        $site->update($validated);

        return redirect()->route('settings.sites.index')
            ->with('success', 'Site updated successfully.');
    }

    /**
     * Mark the site as inactive instead of deleting it.
     * This implements a soft delete by setting active = 0.
     */
    public function destroy(Site $site): RedirectResponse
    {
        $siteName = $site->name;

        try {
            // Instead of deleting, mark as inactive
            $site->update(['active' => 0]);

            return redirect()->route('settings.sites.index')
                ->with('success', "Site '{$siteName}' has been deactivated successfully.");
        } catch (\Exception $e) {
            return redirect()->route('settings.sites.index')
                ->with('error', "Unable to deactivate site '{$siteName}'. Please try again.");
        }
    }
}
