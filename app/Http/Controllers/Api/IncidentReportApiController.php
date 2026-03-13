<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;

/**
 * @OA\Tag(
 *     name="Incident Reports",
 *     description="Incident report management endpoints"
 * )
 */
class IncidentReportApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/incident-reports",
     *     summary="Get list of incident reports",
     *     description="Retrieve a paginated list of incident reports with comprehensive filtering options including date ranges",
     *     operationId="getIncidentReports",
     *     tags={"Incident Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximum number of incident reports to return (1-100)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=50)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter incident reports by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "open", "closed", "resolved"})
     *     ),
     *     @OA\Parameter(
     *         name="incident_type_id",
     *         in="query",
     *         description="Filter incident reports by incident type ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter incident reports from this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter incident reports to this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="site_id",
     *         in="query",
     *         description="Filter incident reports by site ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="General search across description, location, what happened fields",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="count", type="integer", example=25),
     *             @OA\Property(property="limit", type="integer", example=50),
     *             @OA\Property(
     *                 property="filters",
     *                 type="object",
     *                 @OA\Property(property="status", type="string", nullable=true),
     *                 @OA\Property(property="incident_type_id", type="integer", nullable=true),
     *                 @OA\Property(property="date_from", type="string", nullable=true),
     *                 @OA\Property(property="date_to", type="string", nullable=true),
     *                 @OA\Property(property="site_id", type="integer", nullable=true),
     *                 @OA\Property(property="search", type="string", nullable=true)
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="brief_description", type="string", example="Slip and fall incident"),
     *                     @OA\Property(property="date_of_occurrence", type="string", example="2024-01-15"),
     *                     @OA\Property(property="time_of_occurrence", type="string", example="14:30:00"),
     *                     @OA\Property(property="location", type="string", example="Main entrance"),
     *                     @OA\Property(property="status", type="string", enum={"pending", "open", "closed", "resolved"}, example="pending"),
     *                     @OA\Property(property="incident_type_id", type="integer", example=1),
     *                     @OA\Property(property="site_id", type="integer", example=1),
     *                     @OA\Property(property="what_happened", type="string", example="Employee slipped on wet floor"),
     *                     @OA\Property(property="archived", type="boolean", example=false),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="incident_type",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Slip/Trip/Fall")
     *                     ),
     *                     @OA\Property(
     *                         property="site",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Main Office")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:100',
            'status' => 'nullable|string|in:pending,open,closed,resolved',
            'incident_type_id' => 'nullable|integer|exists:incident_types,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'site_id' => 'nullable|integer|exists:sites,id',
            'search' => 'nullable|string|max:255',
        ]);

        $limit = $request->get('limit', 50);

        $query = IncidentReport::query()->active(); // Only get non-archived incident reports

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by incident type
        if ($request->has('incident_type_id')) {
            $query->where('incident_type_id', $request->incident_type_id);
        }

        // Filter by date range (using date_of_occurrence)
        if ($request->has('date_from')) {
            $query->whereDate('date_of_occurrence', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date_of_occurrence', '<=', $request->date_to);
        }

        // Filter by site
        if ($request->has('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // General search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brief_description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('what_happened', 'like', "%{$search}%");
            });
        }

        $incidentReports = $query->with(['incidentType', 'site', 'affectedEmployee', 'reportedEmployee'])
            ->orderBy('date_of_occurrence', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $incidentReports,
            'count' => $incidentReports->count(),
            'limit' => $limit,
            'filters' => [
                'status' => $request->status,
                'incident_type_id' => $request->incident_type_id,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'site_id' => $request->site_id,
                'search' => $request->search,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/incident-reports/{incidentReport}",
     *     summary="Get a specific incident report",
     *     description="Retrieve details of a specific incident report by ID",
     *     operationId="getIncidentReport",
     *     tags={"Incident Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="incidentReport",
     *         in="path",
     *         description="Incident Report ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="brief_description", type="string", example="Slip and fall incident"),
     *                 @OA\Property(property="date_of_occurrence", type="string", example="2024-01-15"),
     *                 @OA\Property(property="time_of_occurrence", type="string", example="14:30:00"),
     *                 @OA\Property(property="location", type="string", example="Main entrance"),
     *                 @OA\Property(property="status", type="string", enum={"pending", "open", "closed", "resolved"}, example="pending"),
     *                 @OA\Property(property="incident_type_id", type="integer", example=1),
     *                 @OA\Property(property="site_id", type="integer", example=1),
     *                 @OA\Property(property="what_happened", type="string", example="Employee slipped on wet floor"),
     *                 @OA\Property(property="archived", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Incident report not found")
     * )
     */
    public function show(IncidentReport $incidentReport): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $incidentReport->load([
                'incidentType',
                'site',
                'affectedEmployee',
                'reportedEmployee',
                'treatmentType',
                'mechanism',
                'bodyPart',
                'injuryType'
            ])
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/incident-reports",
     *     summary="Create a new incident report",
     *     description="Create a new incident report with the provided information",
     *     operationId="createIncidentReport",
     *     tags={"Incident Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"brief_description", "date_of_occurrence", "location"},
     *             @OA\Property(property="brief_description", type="string", example="Slip and fall incident"),
     *             @OA\Property(property="incident_type_id", type="integer", example=1),
     *             @OA\Property(property="location", type="string", example="Main entrance"),
     *             @OA\Property(property="additional_information", type="string", example="Additional details about the incident"),
     *             @OA\Property(property="date_of_occurrence", type="string", format="date", example="2024-01-15"),
     *             @OA\Property(property="time_of_occurrence", type="string", format="time", example="14:30:00"),
     *             @OA\Property(property="work_shift", type="string", example="Day shift"),
     *             @OA\Property(property="hours_worked_prior", type="number", format="float", example=6.5),
     *             @OA\Property(property="site_id", type="integer", example=1),
     *             @OA\Property(property="what_happened", type="string", example="Employee slipped on wet floor"),
     *             @OA\Property(property="status", type="string", enum={"pending", "open", "closed", "resolved"}, example="pending", default="pending"),
     *             @OA\Property(property="affected_person_source", type="string", enum={"employee", "customer", "other"}, example="employee"),
     *             @OA\Property(property="affected_employee_id", type="integer", example=1),
     *             @OA\Property(property="reported_by_source", type="string", enum={"employee", "customer", "other"}, example="employee"),
     *             @OA\Property(property="reported_employee_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Incident report created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Incident report created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="brief_description", type="string", example="Slip and fall incident"),
     *                 @OA\Property(property="date_of_occurrence", type="string", example="2024-01-15")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'brief_description' => 'required|string|max:255',
            'incident_type_id' => 'nullable|integer|exists:incident_types,id',
            'location' => 'required|string|max:255',
            'additional_information' => 'nullable|string',
            'date_of_occurrence' => 'required|date',
            'time_of_occurrence' => 'nullable|date_format:H:i:s',
            'work_shift' => 'nullable|string|max:100',
            'hours_worked_prior' => 'nullable|numeric|min:0|max:24',
            'site_id' => 'nullable|integer|exists:sites,id',
            'what_happened' => 'nullable|string',
            'status' => 'nullable|string|in:pending,open,closed,resolved',

            // Affected person fields
            'affected_person_source' => 'nullable|string|in:employee,customer,other',
            'affected_employee_id' => 'nullable|integer|exists:employees,id',
            'affected_customer_id' => 'nullable|integer|exists:customers,id',
            'affected_person_other' => 'nullable|string|max:255',

            // Reported by fields
            'reported_by_source' => 'nullable|string|in:employee,customer,other',
            'reported_employee_id' => 'nullable|integer|exists:employees,id',
            'reported_customer_id' => 'nullable|integer|exists:customers,id',
            'reported_by_other' => 'nullable|string|max:255',

            // Additional fields
            'treatment_type_id' => 'nullable|integer|exists:treatment_types,id',
            'mechanism_id' => 'nullable|integer|exists:mechanisms,id',
            'body_part_id' => 'nullable|integer|exists:body_parts,id',
            'injury_type_id' => 'nullable|integer|exists:injury_types,id',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'agency_id' => 'nullable|integer|exists:agencies,id',
        ]);

        // Set default values
        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['created_by'] = $request->user()->id;

        $incidentReport = IncidentReport::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Incident report created successfully',
            'data' => $incidentReport
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/incident-reports/{incidentReport}",
     *     summary="Update an incident report",
     *     description="Update an existing incident report with new information",
     *     operationId="updateIncidentReport",
     *     tags={"Incident Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="incidentReport",
     *         in="path",
     *         description="Incident Report ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="brief_description", type="string", example="Updated incident description"),
     *             @OA\Property(property="incident_type_id", type="integer", example=2),
     *             @OA\Property(property="location", type="string", example="Updated location"),
     *             @OA\Property(property="additional_information", type="string", example="Updated additional information"),
     *             @OA\Property(property="status", type="string", enum={"pending", "open", "closed", "resolved"}, example="open"),
     *             @OA\Property(property="what_happened", type="string", example="Updated description of what happened")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incident report updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Incident report updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="status", type="string", example="open")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Incident report not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, IncidentReport $incidentReport): JsonResponse
    {
        $validated = $request->validate([
            'brief_description' => 'sometimes|required|string|max:255',
            'incident_type_id' => 'nullable|integer|exists:incident_types,id',
            'location' => 'sometimes|required|string|max:255',
            'additional_information' => 'nullable|string',
            'date_of_occurrence' => 'sometimes|required|date',
            'time_of_occurrence' => 'nullable|date_format:H:i:s',
            'work_shift' => 'nullable|string|max:100',
            'hours_worked_prior' => 'nullable|numeric|min:0|max:24',
            'site_id' => 'nullable|integer|exists:sites,id',
            'what_happened' => 'nullable|string',
            'status' => 'nullable|string|in:pending,open,closed,resolved',

            // Affected person fields
            'affected_person_source' => 'nullable|string|in:employee,customer,other',
            'affected_employee_id' => 'nullable|integer|exists:employees,id',
            'affected_customer_id' => 'nullable|integer|exists:customers,id',
            'affected_person_other' => 'nullable|string|max:255',

            // Reported by fields
            'reported_by_source' => 'nullable|string|in:employee,customer,other',
            'reported_employee_id' => 'nullable|integer|exists:employees,id',
            'reported_customer_id' => 'nullable|integer|exists:customers,id',
            'reported_by_other' => 'nullable|string|max:255',

            // Additional fields
            'treatment_type_id' => 'nullable|integer|exists:treatment_types,id',
            'mechanism_id' => 'nullable|integer|exists:mechanisms,id',
            'body_part_id' => 'nullable|integer|exists:body_parts,id',
            'injury_type_id' => 'nullable|integer|exists:injury_types,id',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'agency_id' => 'nullable|integer|exists:agencies,id',
        ]);

        $validated['updated_by'] = $request->user()->id;

        $incidentReport->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Incident report updated successfully',
            'data' => $incidentReport
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/incident-reports/{incidentReport}",
     *     summary="Delete an incident report",
     *     description="Archive an existing incident report instead of hard deleting it",
     *     operationId="deleteIncidentReport",
     *     tags={"Incident Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="incidentReport",
     *         in="path",
     *         description="Incident Report ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incident report archived successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Incident report archived successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Incident report not found")
     * )
     */
    public function destroy(Request $request, IncidentReport $incidentReport): JsonResponse
    {
        // Archive instead of delete
        $incidentReport->update([
            'archived' => true,
            'archived_by' => $request->user()->id ?? null,
            'archived_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Incident report archived successfully'
        ]);
    }
}
