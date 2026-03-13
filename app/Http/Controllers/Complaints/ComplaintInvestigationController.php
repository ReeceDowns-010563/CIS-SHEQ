<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintLog;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Complaint Investigation Controller
 *
 * Handles investigation-related functionality including the user's
 * assigned complaints, investigation editing, and archiving from investigations.
 */
class ComplaintInvestigationController extends Controller
{
    /**
     * Display the user's assigned investigations with filtering and sorting
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $this->buildInvestigationsQuery($request);
        $this->applyInvestigationFilters($query, $request);
        $this->applyInvestigationSorting($query, $request);

        $complaints = $query->paginate(15)->withQueryString();
        $sites = Site::all();
        $users = User::all();

        return view('complaints.my-investigations', compact('complaints', 'sites', 'users'));
    }

    /**
     * Show the form for editing a complaint from investigations
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $complaint = Complaint::findOrFail($id);
        $sites = Site::all();
        $users = User::all();

        return view('complaints.edit-investigation', compact('complaint', 'sites', 'users'));
    }

    /**
     * Archive a complaint from my-investigations page (stays on same page)
     *
     * @param Complaint $complaint
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function archive(Complaint $complaint)
    {
        if ($complaint->archived) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Complaint is already archived.'
                ], 400);
            }
            return redirect()->route('complaints.my-investigations')
                ->with('error', 'Complaint is already archived.');
        }

        $complaint->update([
            'archived' => true,
            'archived_by' => Auth::id(),
            'archived_at' => now()
        ]);

        // Log the archiving
        ComplaintLog::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action_type' => 'archived',
            'changes' => [
                'archived' => [
                    'from' => false,
                    'to' => true
                ]
            ]
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Complaint archived successfully.'
            ]);
        }

        return redirect()->route('complaints.my-investigations')
            ->with('success', 'Complaint archived successfully.');
    }

    /**
     * Build the base query for user investigations
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildInvestigationsQuery(Request $request)
    {
        $query = Complaint::with(['site', 'assignedTo'])
            ->withCount('comments')
            ->where('assigned_to', auth()->id());

        // Handle archived vs active complaints
        if ($request->filled('archived') && $request->archived == '1') {
            // Show archived complaints (remove the status filter for archived)
            $query->where('archived', true);
        } else {
            // Show active complaints with open status
            $query->where('archived', false)
                ->where('status', 'open');
        }

        return $query;
    }

    /**
     * Apply filters to the investigations query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     */
    private function applyInvestigationFilters($query, Request $request): void
    {
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('pcn_number', 'LIKE', "%{$search}%");
            });
        }

        // Filter by site
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }
    }

    /**
     * Apply sorting to the investigations query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     */
    private function applyInvestigationSorting($query, Request $request): void
    {
        $sort = $request->get('sort', 'date_received');
        $direction = $request->get('direction', 'desc');

        $allowedSortFields = [
            'name', 'pcn_number', 'date_received', 'nature',
            'date_acknowledged', 'status', 'conclusion', 'date_concluded',
            'ico_complaint', 'site_id', 'assigned_to', 'archived_at'
        ];

        if (in_array($sort, $allowedSortFields)) {
            $query->orderBy($sort, $direction);
        }
    }
}
