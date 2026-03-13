<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;

/**
 * @OA\Tag(
 *     name="Complaints",
 *     description="Complaint management endpoints"
 * )
 */
class ComplaintApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/complaints",
     *     summary="Get list of complaints",
     *     description="Retrieve a paginated list of complaints with comprehensive filtering options including date ranges",
     *     operationId="getComplaints",
     *     tags={"Complaints"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximum number of complaints to return (1-100)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=50)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter complaints by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"open", "closed"})
     *     ),
     *     @OA\Parameter(
     *         name="nature",
     *         in="query",
     *         description="Filter complaints by nature/type",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter complaints from this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter complaints to this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="pcn_number",
     *         in="query",
     *         description="Filter complaints by PCN number",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=50)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="General search across name, PCN, nature fields",
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
     *                 @OA\Property(property="nature", type="string", nullable=true),
     *                 @OA\Property(property="date_from", type="string", nullable=true),
     *                 @OA\Property(property="date_to", type="string", nullable=true),
     *                 @OA\Property(property="pcn_number", type="string", nullable=true),
     *                 @OA\Property(property="search", type="string", nullable=true)
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="date_received", type="string", example="15/01/2024"),
     *                     @OA\Property(property="name", type="string", example="Jane Smith"),
     *                     @OA\Property(property="pcn_number", type="string", example="PCN123456"),
     *                     @OA\Property(property="site_id", type="integer", example=1),
     *                     @OA\Property(property="nature", type="string", example="Parking Fine Dispute"),
     *                     @OA\Property(property="date_acknowledged", type="string", nullable=true, example="16/01/2024"),
     *                     @OA\Property(property="status", type="string", enum={"open", "closed"}, example="open"),
     *                     @OA\Property(property="conclusion", type="string", nullable=true, example="Complaint resolved"),
     *                     @OA\Property(property="date_concluded", type="string", nullable=true, example="20/01/2024"),
     *                     @OA\Property(property="ico_complaint", type="boolean", example=false),
     *                     @OA\Property(property="archived", type="boolean", example=false),
     *                     @OA\Property(property="archived_by", type="integer", nullable=true),
     *                     @OA\Property(property="archived_at", type="string", nullable=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
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
            'status' => 'nullable|string|in:open,closed',
            'nature' => 'nullable|string|max:255',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'pcn_number' => 'nullable|string|max:50',
            'search' => 'nullable|string|max:255',
        ]);

        $limit = $request->get('limit', 50);

        $query = Complaint::query()->active(); // Only get non-archived complaints

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by nature
        if ($request->has('nature')) {
            $query->where('nature', 'like', '%' . $request->nature . '%');
        }

        // Filter by date range (using raw original values for filtering)
        if ($request->has('date_from')) {
            $query->whereDate('date_received', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date_received', '<=', $request->date_to);
        }

        // Filter by PCN number
        if ($request->has('pcn_number')) {
            $query->where('pcn_number', 'like', '%' . $request->pcn_number . '%');
        }

        // General search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('pcn_number', 'like', "%{$search}%")
                    ->orWhere('nature', 'like', "%{$search}%");
            });
        }

        $complaints = $query->with('site')
            ->orderBy('date_received', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $complaints,
            'count' => $complaints->count(),
            'limit' => $limit,
            'filters' => [
                'status' => $request->status,
                'nature' => $request->nature,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'pcn_number' => $request->pcn_number,
                'search' => $request->search,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/complaints/{complaint}",
     *     summary="Get a specific complaint",
     *     description="Retrieve details of a specific complaint by ID",
     *     operationId="getComplaint",
     *     tags={"Complaints"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="complaint",
     *         in="path",
     *         description="Complaint ID",
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
     *                 @OA\Property(property="date_received", type="string", example="15/01/2024"),
     *                 @OA\Property(property="name", type="string", example="Jane Smith"),
     *                 @OA\Property(property="pcn_number", type="string", example="PCN123456"),
     *                 @OA\Property(property="site_id", type="integer", example=1),
     *                 @OA\Property(property="nature", type="string", example="Parking Fine Dispute"),
     *                 @OA\Property(property="date_acknowledged", type="string", nullable=true, example="16/01/2024"),
     *                 @OA\Property(property="status", type="string", enum={"open", "closed"}, example="open"),
     *                 @OA\Property(property="conclusion", type="string", nullable=true, example="Complaint resolved"),
     *                 @OA\Property(property="date_concluded", type="string", nullable=true, example="20/01/2024"),
     *                 @OA\Property(property="ico_complaint", type="boolean", example=false),
     *                 @OA\Property(property="archived", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Complaint not found")
     * )
     */
    public function show(Complaint $complaint): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $complaint->load('site')
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/complaints",
     *     summary="Create a new complaint",
     *     description="Create a new complaint with the provided information",
     *     operationId="createComplaint",
     *     tags={"Complaints"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "nature"},
     *             @OA\Property(property="date_received", type="string", format="date", example="2024-01-15"),
     *             @OA\Property(property="name", type="string", example="Jane Smith"),
     *             @OA\Property(property="pcn_number", type="string", example="PCN123456"),
     *             @OA\Property(property="site_id", type="integer", example=1),
     *             @OA\Property(property="nature", type="string", example="Parking Fine Dispute"),
     *             @OA\Property(property="date_acknowledged", type="string", format="date", nullable=true, example="2024-01-16"),
     *             @OA\Property(property="status", type="string", enum={"open", "closed"}, example="open", default="open"),
     *             @OA\Property(property="conclusion", type="string", nullable=true, example="Complaint resolved"),
     *             @OA\Property(property="date_concluded", type="string", format="date", nullable=true, example="2024-01-20"),
     *             @OA\Property(property="ico_complaint", type="boolean", example=false, default=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Complaint created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Complaint created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date_received", type="string", example="15/01/2024"),
     *                 @OA\Property(property="name", type="string", example="Jane Smith")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date_received' => 'nullable|date',
            'name' => 'required|string|max:255',
            'pcn_number' => 'nullable|string|max:255',
            'site_id' => 'nullable|integer|exists:sites,id',
            'nature' => 'required|string|max:255',
            'date_acknowledged' => 'nullable|date',
            'status' => 'nullable|string|in:open,closed',
            'conclusion' => 'nullable|string',
            'date_concluded' => 'nullable|date',
            'ico_complaint' => 'nullable|boolean'
        ]);

        // Set default values
        $validated['date_received'] = $validated['date_received'] ?? now();
        $validated['status'] = $validated['status'] ?? 'open';
        $validated['ico_complaint'] = $validated['ico_complaint'] ?? false;

        $complaint = Complaint::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Complaint created successfully',
            'data' => $complaint
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/complaints/{complaint}",
     *     summary="Update a complaint",
     *     description="Update an existing complaint with new information. Auto-sets conclusion date when status changes to closed.",
     *     operationId="updateComplaint",
     *     tags={"Complaints"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="complaint",
     *         in="path",
     *         description="Complaint ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="date_received", type="string", format="date", example="2024-01-15"),
     *             @OA\Property(property="name", type="string", example="Jane Smith"),
     *             @OA\Property(property="pcn_number", type="string", example="PCN123456"),
     *             @OA\Property(property="site_id", type="integer", example=1),
     *             @OA\Property(property="nature", type="string", example="Parking Fine Dispute"),
     *             @OA\Property(property="date_acknowledged", type="string", format="date", nullable=true, example="2024-01-16"),
     *             @OA\Property(property="status", type="string", enum={"open", "closed"}, example="closed"),
     *             @OA\Property(property="conclusion", type="string", nullable=true, example="Complaint resolved"),
     *             @OA\Property(property="date_concluded", type="string", format="date", nullable=true, example="2024-01-20"),
     *             @OA\Property(property="ico_complaint", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Complaint updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Complaint updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="status", type="string", example="closed")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Complaint not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, Complaint $complaint): JsonResponse
    {
        $validated = $request->validate([
            'date_received' => 'nullable|date',
            'name' => 'sometimes|required|string|max:255',
            'pcn_number' => 'nullable|string|max:255',
            'site_id' => 'nullable|integer|exists:sites,id',
            'nature' => 'sometimes|required|string|max:255',
            'date_acknowledged' => 'nullable|date',
            'status' => 'nullable|string|in:open,closed',
            'conclusion' => 'nullable|string',
            'date_concluded' => 'nullable|date',
            'ico_complaint' => 'nullable|boolean'
        ]);

        $complaint->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Complaint updated successfully',
            'data' => $complaint
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/complaints/{complaint}",
     *     summary="Delete a complaint",
     *     description="Archive an existing complaint instead of hard deleting it",
     *     operationId="deleteComplaint",
     *     tags={"Complaints"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="complaint",
     *         in="path",
     *         description="Complaint ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Complaint archived successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Complaint archived successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Complaint not found")
     * )
     */
    public function destroy(Request $request, Complaint $complaint): JsonResponse
    {
        // Archive instead of delete
        $complaint->update([
            'archived' => true,
            'archived_by' => $request->user()->id ?? null,
            'archived_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Complaint archived successfully'
        ]);
    }
}
