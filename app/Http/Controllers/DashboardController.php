<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\IncidentReport;
use App\Models\UserDashboardPreference;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Helpers\FeatureHelper;

/**
 * Dashboard Controller
 *
 * Manages the main administrative dashboard of the application, providing
 * comprehensive analytics, metrics, and visualizations for both complaint
 * management and incident reporting systems. Features a dual-view toggle
 * system with customizable widget layouts and persistent user preferences.
 * Access is restricted based on user permissions and site access rights.
 *
 * @author CIS Security
 * @version 2.0
 */
class DashboardController extends Controller
{
    /**
     * Display the unified dashboard with analytics for both complaints and incidents.
     *
     * This method aggregates comprehensive metrics and analytics based on user
     * access permissions and site restrictions. The dashboard features:
     * - Permission-based feature access control
     * - Site-restricted data filtering
     * - Real-time KPI tracking
     * - Historical trend analysis
     * - Type categorization statistics
     * - Recent activity monitoring
     * - Customizable widget layouts
     *
     * @return View The dashboard view with all necessary data variables
     */
    public function index(): View
    {
        // === USER ACCESS PERMISSIONS ===
        $canAccessComplaints = FeatureHelper::canAccess('complaints');
        $canAccessIncidents = FeatureHelper::canAccess('incidents');

        // Get accessible site IDs for data filtering
        $accessibleSiteIds = auth()->user()->getAccessibleSiteIds();

        // === COMPLAINT ANALYTICS ===
        $monthlyComplaints = 0;
        $averageClosureTime = 0;
        $complaintTrends = [];
        $complaintTypes = [];
        $recentComplaints = collect();
        $openComplaints = 0;
        $closedComplaints = 0;

        if ($canAccessComplaints) {
            $monthlyComplaints = $this->getMonthlyComplaintCount($accessibleSiteIds);
            $averageClosureTime = $this->getAverageComplaintClosureTime($accessibleSiteIds);
            $complaintTrends = $this->getComplaintTrends($accessibleSiteIds);
            $complaintTypes = $this->getComplaintTypes($accessibleSiteIds);
            $recentComplaints = $this->getRecentComplaints($accessibleSiteIds);
            $openComplaints = $this->getOpenComplaintsCount($accessibleSiteIds);
            $closedComplaints = $this->getClosedComplaintsCount($accessibleSiteIds);
        }

        // === INCIDENT REPORT ANALYTICS ===
        $monthlyIncidents = 0;
        $averageClosureTimeIncidents = 0;
        $incidentTrends = [];
        $incidentTypes = [];
        $recentIncidents = collect();
        $openIncidents = 0;
        $closedIncidents = 0;

        if ($canAccessIncidents) {
            $monthlyIncidents = $this->getMonthlyIncidentCount($accessibleSiteIds);
            $averageClosureTimeIncidents = $this->getAverageIncidentClosureTime($accessibleSiteIds);
            $incidentTrends = $this->getIncidentTrends($accessibleSiteIds);
            $incidentTypes = $this->getIncidentTypes($accessibleSiteIds);
            $recentIncidents = $this->getRecentIncidents($accessibleSiteIds);
            $openIncidents = $this->getOpenIncidentsCount($accessibleSiteIds);
            $closedIncidents = $this->getClosedIncidentsCount($accessibleSiteIds);
        }

        // === USER PREFERENCES ===
        $dashboardLayout = auth()->user()->dashboardPreference?->layout_config;

        // Debug logging for layout restoration troubleshooting
        \Log::info('Loading dashboard layout for user ' . auth()->id(), [
            'layout_config' => $dashboardLayout,
            'accessible_sites' => $accessibleSiteIds->count(),
            'can_access_complaints' => $canAccessComplaints,
            'can_access_incidents' => $canAccessIncidents,
            'complaint_metrics' => [
                'monthly' => $monthlyComplaints,
                'open' => $openComplaints,
                'closed' => $closedComplaints
            ],
            'incident_metrics' => [
                'monthly' => $monthlyIncidents,
                'open' => $openIncidents,
                'closed' => $closedIncidents
            ]
        ]);

        // Pass all analytics data to the dashboard view for rendering
        return view('dashboard', compact(
        // Access permissions
            'canAccessComplaints',
            'canAccessIncidents',

            // Complaint data
            'monthlyComplaints',
            'averageClosureTime',
            'complaintTrends',
            'complaintTypes',
            'recentComplaints',
            'openComplaints',
            'closedComplaints',

            // Incident data
            'monthlyIncidents',
            'averageClosureTimeIncidents',
            'incidentTrends',
            'incidentTypes',
            'recentIncidents',
            'openIncidents',
            'closedIncidents',

            // User preferences
            'dashboardLayout'
        ));
    }

    /**
     * Save the user's customized dashboard layout configuration.
     *
     * Persists widget positioning and layout preferences to provide
     * a personalized dashboard experience. Includes comprehensive
     * error handling and validation for data integrity.
     *
     * @param Request $request Contains layout_config array with widget positions
     * @return JsonResponse Success/failure response with detailed messaging
     */
    public function saveDashboardLayout(Request $request): JsonResponse
    {
        try {
            // Log incoming data for debugging layout save issues
            \Log::info('Dashboard layout save attempt', [
                'user_id' => auth()->id(),
                'raw_data' => $request->all()
            ]);

            // Extract and validate layout configuration data
            $layoutData = $request->input('layout_config');

            // Validate required data presence
            if ($layoutData === null) {
                \Log::warning('Layout save failed - missing layout_config', [
                    'user_id' => auth()->id(),
                    'request_keys' => array_keys($request->all())
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Layout configuration is required and cannot be null'
                ], 422);
            }

            // Handle JSON string input (decode if necessary)
            if (is_string($layoutData)) {
                $layoutData = json_decode($layoutData, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('Layout save failed - JSON decode error', [
                        'user_id' => auth()->id(),
                        'json_error' => json_last_error_msg(),
                        'raw_data' => $layoutData
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid JSON format in layout configuration'
                    ], 422);
                }
            }

            // Sanitize and validate data structure
            if (!is_array($layoutData)) {
                \Log::warning('Layout save - converting invalid data to array', [
                    'user_id' => auth()->id(),
                    'data_type' => gettype($layoutData),
                    'data_value' => $layoutData
                ]);
                $layoutData = [];
            }

            // Log successful data processing
            \Log::info('Layout save - processed data successfully', [
                'user_id' => auth()->id(),
                'layout_slots' => array_keys($layoutData),
                'layout_items' => array_values($layoutData)
            ]);

            // Persist layout configuration to database
            auth()->user()->dashboardPreference()->updateOrCreate(
                ['user_id' => auth()->id()],
                ['layout_config' => $layoutData]
            );

            \Log::info('Dashboard layout saved successfully', [
                'user_id' => auth()->id(),
                'layout_config' => $layoutData
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dashboard layout saved successfully',
                'layout_config' => $layoutData
            ]);

        } catch (\Exception $e) {
            // Comprehensive error logging for debugging
            \Log::error('Dashboard layout save exception', [
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save dashboard layout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset the user's dashboard layout to system default configuration.
     *
     * Removes all personalized layout preferences, reverting to the
     * standard widget arrangement. Useful for troubleshooting layout
     * issues or returning to the original design.
     *
     * @return JsonResponse Confirmation of successful reset operation
     */
    public function resetDashboardLayout(): JsonResponse
    {
        try {
            $userId = auth()->id();

            // Remove existing layout preferences
            auth()->user()->dashboardPreference()?->delete();

            \Log::info('Dashboard layout reset successfully', [
                'user_id' => $userId,
                'action' => 'layout_reset'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dashboard layout reset to default configuration'
            ]);

        } catch (\Exception $e) {
            \Log::error('Dashboard layout reset failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset dashboard layout. Please try again.'
            ], 500);
        }
    }

    // ========================================
    // COMPLAINT ANALYTICS METHODS
    // ========================================

    /**
     * Calculate the total number of complaints received in the current month
     * for sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return int Count of complaints received this month from accessible sites
     */
    private function getMonthlyComplaintCount($accessibleSiteIds): int
    {
        $query = Complaint::active()
            ->whereMonth('date_received', Carbon::now()->month)
            ->whereYear('date_received', Carbon::now()->year);

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        return $query->count();
    }

    /**
     * Calculate the average resolution time for complaints received this month
     * from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return float Average resolution time in days (rounded to 1 decimal place)
     */
    private function getAverageComplaintClosureTime($accessibleSiteIds): float
    {
        $query = Complaint::active()
            ->whereMonth('date_received', Carbon::now()->month)
            ->whereYear('date_received', Carbon::now()->year)
            ->whereNotNull('date_concluded')
            ->whereNotNull('date_received')
            ->select('date_received', 'date_concluded');

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        $complaints = $query->get();

        if ($complaints->isEmpty()) {
            return 0;
        }

        // Calculate average resolution time across all completed complaints
        $averageTime = $complaints->avg(function ($complaint) {
            try {
                // Use raw database values to avoid accessor formatting issues
                $receivedRaw = $complaint->getRawOriginal('date_received');
                $concludedRaw = $complaint->getRawOriginal('date_concluded');

                $received = Carbon::parse($receivedRaw);
                $concluded = Carbon::parse($concludedRaw);

                return $received->diffInDays($concluded);
            } catch (\Exception $e) {
                \Log::warning('Error calculating complaint resolution time', [
                    'complaint_id' => $complaint->id,
                    'error' => $e->getMessage()
                ]);
                return 0;
            }
        });

        return round($averageTime ?? 0, 1);
    }

    /**
     * Generate complaint volume trend data for the past 6 months
     * from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return array Monthly complaint counts with formatted month labels
     */
    private function getComplaintTrends($accessibleSiteIds): array
    {
        // Generate 6-month historical data (current month + 5 previous)
        $trendData = collect(range(5, 0))->map(function ($monthsBack) use ($accessibleSiteIds) {
            $targetDate = Carbon::now()->subMonths($monthsBack)->startOfMonth();

            $query = Complaint::active()
                ->whereYear('date_received', $targetDate->year)
                ->whereMonth('date_received', $targetDate->month);

            // Apply site restriction if user doesn't have access to all sites
            if ($accessibleSiteIds->isNotEmpty()) {
                $query->whereIn('site_id', $accessibleSiteIds);
            }

            $count = $query->count();

            return [
                'month' => $targetDate->format('M'), // Short month format (Jan, Feb, etc.)
                'count' => $count,
                'full_month' => $targetDate->format('F Y') // For detailed logging
            ];
        });

        \Log::debug('Complaint trends calculated', [
            'months' => $trendData->pluck('full_month'),
            'counts' => $trendData->pluck('count'),
            'accessible_sites' => $accessibleSiteIds->count()
        ]);

        return $trendData->toArray();
    }

    /**
     * Analyze and categorize complaint types by frequency for current month
     * from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return array Top 5 complaint categories with counts and percentages
     */
    private function getComplaintTypes($accessibleSiteIds): array
    {
        $query = Complaint::active()
            ->whereMonth('date_received', Carbon::now()->month)
            ->whereYear('date_received', Carbon::now()->year)
            ->select('nature')
            ->selectRaw('count(*) as count')
            ->groupBy('nature')
            ->orderByDesc('count')
            ->limit(5);

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        $complaintTypes = $query->get()->toArray();

        // Log type distribution for analysis
        \Log::debug('Complaint types analysis', [
            'month' => Carbon::now()->format('F Y'),
            'types' => $complaintTypes,
            'accessible_sites' => $accessibleSiteIds->count()
        ]);

        return $complaintTypes;
    }

    /**
     * Retrieve the most recently received complaints from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return \Illuminate\Database\Eloquent\Collection Recent complaints with full details
     */
    private function getRecentComplaints($accessibleSiteIds)
    {
        $query = Complaint::active()
            ->latest('date_received')
            ->take(5);

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        return $query->get();
    }

    /**
     * Count currently open/active complaints from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return int Number of complaints with 'open' status from accessible sites
     */
    private function getOpenComplaintsCount($accessibleSiteIds): int
    {
        $query = Complaint::active()
            ->where('status', 'open');

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        return $query->count();
    }

    /**
     * Count complaints closed/resolved in the current month from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return int Number of complaints with 'closed' status from accessible sites
     */
    private function getClosedComplaintsCount($accessibleSiteIds): int
    {
        $query = Complaint::active()
            ->whereMonth('date_received', Carbon::now()->month)
            ->whereYear('date_received', Carbon::now()->year)
            ->where('status', 'closed');

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        return $query->count();
    }

    // ========================================
    // INCIDENT REPORT ANALYTICS METHODS
    // ========================================

    /**
     * Calculate the total number of incident reports received in the current month
     * from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return int Count of incident reports from this month from accessible sites
     */
    private function getMonthlyIncidentCount($accessibleSiteIds): int
    {
        $query = IncidentReport::active()
            ->whereMonth('date_of_occurrence', Carbon::now()->month)
            ->whereYear('date_of_occurrence', Carbon::now()->year);

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        return $query->count();
    }

    /**
     * Calculate the average investigation completion time for incidents this month
     * from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return float Average investigation time in days from accessible sites
     */
    private function getAverageIncidentClosureTime($accessibleSiteIds): float
    {
        try {
            $query = IncidentReport::active()
                ->whereMonth('date_of_occurrence', Carbon::now()->month)
                ->whereYear('date_of_occurrence', Carbon::now()->year)
                ->where('status', '!=', 'pending') // Exclude pending incidents
                ->whereNotNull('updated_at') // Use updated_at as proxy for completion
                ->whereNotNull('date_of_occurrence')
                ->select('date_of_occurrence', 'updated_at', 'status');

            // Apply site restriction if user doesn't have access to all sites
            if ($accessibleSiteIds->isNotEmpty()) {
                $query->whereIn('site_id', $accessibleSiteIds);
            }

            $incidents = $query->get();

            if ($incidents->isEmpty()) {
                return 0;
            }

            // Calculate average time from occurrence to last update (rough estimation)
            $averageTime = $incidents->avg(function ($incident) {
                try {
                    $occurrenceDate = Carbon::parse($incident->date_of_occurrence);
                    $completedDate = Carbon::parse($incident->updated_at);

                    return $occurrenceDate->diffInDays($completedDate);
                } catch (\Exception $e) {
                    \Log::warning('Error calculating incident processing time', [
                        'incident_id' => $incident->id,
                        'error' => $e->getMessage()
                    ]);
                    return 0;
                }
            });

            return round($averageTime ?? 0, 1);

        } catch (\Exception $e) {
            \Log::warning('Error in incident closure time calculation', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Generate incident report volume trend data for the past 6 months
     * from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return array Monthly incident counts with formatted month labels
     */
    private function getIncidentTrends($accessibleSiteIds): array
    {
        // Generate 6-month historical incident data
        $trendData = collect(range(5, 0))->map(function ($monthsBack) use ($accessibleSiteIds) {
            $targetDate = Carbon::now()->subMonths($monthsBack)->startOfMonth();

            $query = IncidentReport::active()
                ->whereYear('date_of_occurrence', $targetDate->year)
                ->whereMonth('date_of_occurrence', $targetDate->month);

            // Apply site restriction if user doesn't have access to all sites
            if ($accessibleSiteIds->isNotEmpty()) {
                $query->whereIn('site_id', $accessibleSiteIds);
            }

            $count = $query->count();

            return [
                'month' => $targetDate->format('M'), // Short month format
                'count' => $count,
                'full_month' => $targetDate->format('F Y')
            ];
        });

        \Log::debug('Incident trends calculated', [
            'months' => $trendData->pluck('full_month'),
            'counts' => $trendData->pluck('count'),
            'accessible_sites' => $accessibleSiteIds->count()
        ]);

        return $trendData->toArray();
    }

    /**
     * Analyze and categorize incident types by frequency for current month
     * from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return array Top 5 incident categories with counts for analysis
     */
    private function getIncidentTypes($accessibleSiteIds): array
    {
        try {
            $query = IncidentReport::active()
                ->with('incidentType') // Load the related incident type
                ->whereMonth('date_of_occurrence', Carbon::now()->month)
                ->whereYear('date_of_occurrence', Carbon::now()->year)
                ->whereNotNull('incident_type_id');

            // Apply site restriction if user doesn't have access to all sites
            if ($accessibleSiteIds->isNotEmpty()) {
                $query->whereIn('site_id', $accessibleSiteIds);
            }

            // Get incident types through the relationship
            $incidentTypes = $query->get()
                ->groupBy('incident_type_id')
                ->map(function ($incidents, $typeId) {
                    $firstIncident = $incidents->first();
                    return [
                        'nature' => $firstIncident->incidentType?->name ?? 'Unknown Type',
                        'count' => $incidents->count()
                    ];
                })
                ->sortByDesc('count')
                ->take(5)
                ->values()
                ->toArray();

            \Log::debug('Incident types analysis', [
                'month' => Carbon::now()->format('F Y'),
                'types' => $incidentTypes,
                'accessible_sites' => $accessibleSiteIds->count()
            ]);

            return $incidentTypes;

        } catch (\Exception $e) {
            \Log::warning('Error analyzing incident types', [
                'error' => $e->getMessage()
            ]);

            // Return empty array as fallback
            return [];
        }
    }

    /**
     * Retrieve the most recently reported incidents from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return \Illuminate\Database\Eloquent\Collection Recent incidents with full details
     */
    private function getRecentIncidents($accessibleSiteIds)
    {
        $query = IncidentReport::active()
            ->with(['incidentType', 'affectedEmployee', 'reportedEmployee']) // Eager load relationships
            ->latest('date_of_occurrence')
            ->take(5);

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        return $query->get();
    }

    /**
     * Count currently open/active incident reports from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return int Number of incidents requiring attention from accessible sites
     */
    private function getOpenIncidentsCount($accessibleSiteIds): int
    {
        $query = IncidentReport::active()
            ->whereIn('status', ['open', 'pending']);

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        return $query->count();
    }

    /**
     * Count incident investigations closed/completed in the current month
     * from sites the user has access to.
     *
     * @param \Illuminate\Support\Collection $accessibleSiteIds Site IDs user can access
     * @return int Number of incidents completed from accessible sites
     */
    private function getClosedIncidentsCount($accessibleSiteIds): int
    {
        $query = IncidentReport::active()
            ->whereMonth('date_of_occurrence', Carbon::now()->month)
            ->whereYear('date_of_occurrence', Carbon::now()->year)
            ->whereIn('status', ['closed', 'completed', 'resolved']);

        // Apply site restriction if user doesn't have access to all sites
        if ($accessibleSiteIds->isNotEmpty()) {
            $query->whereIn('site_id', $accessibleSiteIds);
        }

        return $query->count();
    }
}
