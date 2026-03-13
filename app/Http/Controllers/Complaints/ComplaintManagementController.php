<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Complaint Management Controller
 *
 * Handles the complaint management interface including filtering,
 * searching, sorting, and pagination of complaint records.
 */
class ComplaintManagementController extends Controller
{
    /**
     * Display the complaint management interface with filtering and sorting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $this->buildManagementQuery($request);
        $this->applyFilters($query, $request);
        $this->applySorting($query, $request);

        $complaints = $query->paginate(10)->appends($request->query());
        $sites = Site::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('complaints.manage', compact('complaints', 'sites', 'users'));
    }

    /**
     * Build the base query for complaint management
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildManagementQuery(Request $request)
    {
        // Handle archived vs active complaints
        if ($request->filled('archived') && $request->archived == 1) {
            return Complaint::query()->archived()->with(['site', 'assignedTo']);
        }

        $query = Complaint::query()->active()->with(['site', 'assignedTo']);

        // Default to showing only 'open' complaints unless a status is explicitly requested
        if (!$request->filled('status')) {
            $query->where('status', 'open');
        }

        return $query;
    }

    /**
     * Apply search and filter criteria to the query
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     */
    private function applyFilters($query, Request $request): void
    {
        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('pcn_number', 'like', '%' . $request->search . '%');
            });
        }

        // Site filter
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Assignment filter
        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->withAssignedTo($request->assigned_to);
            }
        }
    }

    /**
     * Apply sorting to the query
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     */
    private function applySorting($query, Request $request): void
    {
        $allowedSortFields = [
            'name', 'pcn_number', 'date_received', 'nature',
            'date_acknowledged', 'status', 'conclusion', 'date_concluded',
            'ico_complaint', 'site_id', 'assigned_to', 'archived_at'
        ];

        if ($request->filled('sort') && in_array($request->sort, $allowedSortFields)) {
            $direction = $request->get('direction', 'asc');
            $query->orderBy($request->sort, $direction);
        }
    }
}
