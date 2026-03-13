<?php

namespace App\Http\Controllers\Incidents;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\BodyPart;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\IncidentReport;
use App\Models\IncidentType;
use App\Models\InjuryType;
use App\Models\Mechanism;
use App\Models\Site;
use App\Models\TreatmentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncidentController extends Controller
{
    /**
     * Display a listing of incident reports.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $accessibleSiteIds = $user->getAccessibleSiteIds();

        $query = IncidentReport::active()
            ->with([
                'incidentType',
                'treatmentType',
                'branch',
                'site',
                'affectedEmployee',
                'reportedEmployee',
                'coordinator',
                'creator'
            ]);

        // Apply site restrictions
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        // Default filter: Only show open and investigating incidents unless status filter is applied
        if (!$request->filled('status')) {
            $query->whereIn('status', ['open', 'investigating']);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brief_description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('what_happened', 'like', "%{$search}%");
            });
        }

        // Status filter - when explicitly set, override default filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('date_of_occurrence', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_of_occurrence', '<=', $request->date_to);
        }

        $incidents = $query->latest('created_at')->paginate(15);

        // Get all the data that the view might need (similar to form controllers)
        $incidentTypes = IncidentType::active()->orderBy('name')->get();
        $treatmentTypes = TreatmentType::active()->orderBy('name')->get();
        $mechanisms = Mechanism::active()->orderBy('name')->get();
        $bodyParts = BodyPart::active()->orderBy('name')->get();
        $injuryTypes = InjuryType::active()->orderBy('name')->get();
        $agencies = Agency::active()->orderBy('name')->get();
        $customers = Customer::active()->orderBy('name')->get();

        // Define incident statuses - these are the standard Laravel enum values for incident status
        $statuses = [
            'open' => 'open',
            'investigating' => 'Investigating',
            'closed' => 'Closed'
        ];

        // Get accessible branches and sites
        $accessibleBranchIds = collect();
        if ($accessibleSiteIds->isNotEmpty()) {
            $accessibleBranchIds = Site::whereIn('id', $accessibleSiteIds)
                ->pluck('branch_id')
                ->unique()
                ->values();
        }

        // Get filtered data based on access
        $employees = collect();
        $branches = collect();
        $sites = collect();

        if ($accessibleSiteIds->isNotEmpty()) {
            $employees = Employee::whereIn('site_id', $accessibleSiteIds)
                ->active()
                ->with(['site', 'site.branch'])
                ->orderBy('first_name')
                ->get();

            $branches = Branch::whereIn('id', $accessibleBranchIds)
                ->orderBy('branch_name')
                ->get();

            $sites = Site::whereIn('id', $accessibleSiteIds)
                ->active()
                ->with('branch')
                ->orderBy('name')
                ->get();
        }

        // SHEQ users for coordinator assignments
        $sheqUsers = User::whereHas('role', function($query) {
            $query->where('name', 'SHEQ');
        })->active()->orderBy('name')->get();

        return view('incidents.register', compact(
            'incidents',
            'incidentTypes',
            'treatmentTypes',
            'mechanisms',
            'bodyParts',
            'injuryTypes',
            'agencies',
            'customers',
            'employees',
            'branches',
            'sites',
            'sheqUsers',
            'statuses'
        ));
    }

    /**
     * Display the specified incident report.
     */
    public function show(IncidentReport $incident): View
    {
        // Load all necessary relationships - REMOVED 'bodyPart' relationship
        $incident->load([
            'incidentType',
            'treatmentType',
            'mechanism',
            'injuryType',
            'agency',
            'branch',
            'site.branch',
            'affectedEmployee.site.branch',
            'affectedCustomer',
            'reportedEmployee.site.branch',
            'reportedCustomer',
            'coordinator',
            'creator',
            'updater',
            'archivedByUser',
            'comments.user'
        ]);

        return view('incidents.show', compact('incident'));
    }

    /**
     * Display incidents for current user's investigations.
     */
    public function myInvestigations(Request $request): View
    {
        $user = auth()->user();

        $query = IncidentReport::active()
            ->where(function($q) use ($user) {
                // Show incidents where user is the coordinator
                $q->where('coordinator_id', $user->id)
                  // OR where user created/raised the incident
                  ->orWhere('created_by', $user->id);
            })
            ->with([
                'incidentType',
                'treatmentType',
                'branch',
                'site',
                'affectedEmployee',
                'reportedEmployee'
            ]);

        // Default filter: Only show open and investigating incidents unless status filter is applied
        if (!$request->filled('status')) {
            $query->whereIn('status', ['open', 'investigating']);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brief_description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('what_happened', 'like', "%{$search}%");
            });
        }

        // Status filter - when explicitly set, override default filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $incidents = $query->latest('created_at')->paginate(15);

        // Get filter data
        $incidentTypes = IncidentType::active()->orderBy('name')->get();
        $branches = Branch::orderBy('branch_name')->get();

        return view('incidents.my-investigations', compact('incidents', 'incidentTypes', 'branches'));
    }

    /**
     * Show analytics dashboard.
     */
    public function analytics(): View
    {
        $user = auth()->user();
        $accessibleSiteIds = $user->getAccessibleSiteIds();

        $query = IncidentReport::active();

        // Apply site restrictions
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        // Get basic statistics
        $totalIncidents = $query->count();
        $openIncidents = $query->where('status', 'open')->count();
        $investigatingIncidents = $query->where('status', 'investigating')->count();
        $completedIncidents = $query->where('status', 'completed')->count();
        $closedIncidents = $query->where('status', 'closed')->count();

        // Monthly trends (last 12 months)
        $monthlyTrends = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = $query->whereYear('date_of_occurrence', $date->year)
                ->whereMonth('date_of_occurrence', $date->month)
                ->count();

            $monthlyTrends[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        // Most common incident types
        $incidentTypeStats = $query->with('incidentType')
            ->select('incident_type_id', \DB::raw('count(*) as count'))
            ->groupBy('incident_type_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('incidents.analytics', compact(
            'totalIncidents',
            'openIncidents',
            'investigatingIncidents',
            'completedIncidents',
            'closedIncidents',
            'monthlyTrends',
            'incidentTypeStats'
        ));
    }

    /**
     * Remove the specified incident from storage.
     */
    public function destroy(IncidentReport $incident)
    {
        try {
            // Delete associated files
            if ($incident->attachments) {
                foreach ($incident->attachments as $attachment) {
                    if (isset($attachment['path'])) {
                        \Storage::delete($attachment['path']);
                    }
                }
            }

            $incident->delete();

            return redirect()->route('incidents.register')
                ->with('success', 'Incident report deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to delete incident report', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete incident report. Please try again.');
        }
    }

    /**
     * Alias for index method - shows incident register.
     */
    public function register(Request $request): View
    {
        return $this->index($request);
    }
}