<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

/**
 * @OA\Tag(
 *     name="Sites",
 *     description="Site management endpoints"
 * )
 */
class SiteApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/sites",
     *     summary="Get list of sites",
     *     description="Retrieve a paginated list of sites with optional filtering",
     *     operationId="getSites",
     *     tags={"Sites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximum number of sites to return (1-100)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=50)
     *     ),
     *     @OA\Parameter(
     *         name="customer_id",
     *         in="query",
     *         description="Filter sites by customer ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="active",
     *         in="query",
     *         description="Filter sites by active status (accepts: true, false, 1, 0)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"true", "false", "1", "0"},
     *             example="true"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search sites by name or address",
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
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Downtown Parking Site"),
     *                     @OA\Property(property="customer_id", type="integer", example=1),
     *                     @OA\Property(property="address", type="string", example="123 Main Street"),
     *                     @OA\Property(property="city", type="string", example="London"),
     *                     @OA\Property(property="county", type="string", nullable=true, example="Greater London"),
     *                     @OA\Property(property="postal_code", type="string", example="SW1A 1AA"),
     *                     @OA\Property(property="description", type="string", nullable=true, example="Large downtown parking facility"),
     *                     @OA\Property(property="active", type="boolean", example=true),
     *                     @OA\Property(property="full_address", type="string", example="123 Main Street, London, Greater London, SW1A 1AA"),
     *                     @OA\Property(property="display_name", type="string", example="Downtown Parking Site (1)"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="customer",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="ABC Parking Ltd"),
     *                         @OA\Property(property="email", type="string", example="contact@abcparking.com"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:100',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'active' => 'nullable|in:true,false,1,0',
            'search' => 'nullable|string|max:255',
        ]);

        $limit = $request->get('limit', 50);

        $query = Site::with('customer');

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('active')) {
            // Convert string/numeric values to boolean
            $activeValue = filter_var($request->active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($activeValue !== null) {
                $query->where('active', $activeValue);
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $sites = $query->orderBy('name')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $sites,
            'count' => $sites->count(),
            'limit' => $limit
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/sites/{site}",
     *     summary="Get a specific site",
     *     description="Retrieve details of a specific site by ID",
     *     operationId="getSite",
     *     tags={"Sites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="site",
     *         in="path",
     *         description="Site ID",
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
     *                 @OA\Property(property="name", type="string", example="Downtown Parking Site"),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="address", type="string", example="123 Main Street"),
     *                 @OA\Property(property="city", type="string", example="London"),
     *                 @OA\Property(property="county", type="string", nullable=true, example="Greater London"),
     *                 @OA\Property(property="postal_code", type="string", example="SW1A 1AA"),
     *                 @OA\Property(property="description", type="string", nullable=true, example="Large downtown parking facility"),
     *                 @OA\Property(property="active", type="boolean", example=true),
     *                 @OA\Property(property="full_address", type="string", example="123 Main Street, London, Greater London, SW1A 1AA"),
     *                 @OA\Property(property="display_name", type="string", example="Downtown Parking Site (1)"),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="ABC Parking Ltd"),
     *                     @OA\Property(property="email", type="string", example="contact@abcparking.com"),
     *                     @OA\Property(property="active", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Site not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Site not found")
     *         )
     *     )
     * )
     */
    public function show(Site $site): JsonResponse
    {
        $site->load('customer');

        return response()->json([
            'status' => 'success',
            'data' => $site
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/sites",
     *     summary="Create a new site",
     *     description="Create a new site with the provided information",
     *     operationId="createSite",
     *     tags={"Sites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "customer_id"},
     *             @OA\Property(property="name", type="string", example="Downtown Parking Site"),
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="address", type="string", nullable=true, example="123 Main Street"),
     *             @OA\Property(property="city", type="string", nullable=true, example="London"),
     *             @OA\Property(property="county", type="string", nullable=true, example="Greater London"),
     *             @OA\Property(property="postal_code", type="string", nullable=true, example="SW1A 1AA"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Large downtown parking facility"),
     *             @OA\Property(property="active", type="boolean", example=true, default=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Site created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Site created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Downtown Parking Site"),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="address", type="string", example="123 Main Street")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ]);

        // Set default value for active if not provided
        $validated['active'] = $validated['active'] ?? true;

        $site = Site::create($validated);
        $site->load('customer');

        return response()->json([
            'status' => 'success',
            'message' => 'Site created successfully',
            'data' => $site
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/sites/{site}",
     *     summary="Update a site",
     *     description="Update an existing site with new information",
     *     operationId="updateSite",
     *     tags={"Sites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="site",
     *         in="path",
     *         description="Site ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Downtown Parking Site"),
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="address", type="string", nullable=true, example="123 Main Street"),
     *             @OA\Property(property="city", type="string", nullable=true, example="London"),
     *             @OA\Property(property="county", type="string", nullable=true, example="Greater London"),
     *             @OA\Property(property="postal_code", type="string", nullable=true, example="SW1A 1AA"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Large downtown parking facility"),
     *             @OA\Property(property="active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Site updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Site updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Downtown Parking Site"),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="address", type="string", example="123 Main Street")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Site not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, Site $site): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'customer_id' => 'sometimes|required|exists:customers,id',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ]);

        $site->update($validated);
        $site->load('customer');

        return response()->json([
            'status' => 'success',
            'message' => 'Site updated successfully',
            'data' => $site
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/sites/{site}",
     *     summary="Delete a site",
     *     description="Delete an existing site",
     *     operationId="deleteSite",
     *     tags={"Sites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="site",
     *         in="path",
     *         description="Site ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Site deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Site deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Site not found"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Cannot delete site with associated records",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Cannot delete site because it has associated complaints. Please delete or reassign the complaints first.")
     *         )
     *     )
     * )
     */
    public function destroy(Site $site): JsonResponse
    {
        try {
            $site->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Site deleted successfully'
            ]);
        } catch (QueryException $e) {
            // Check if this is a foreign key constraint violation
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'foreign key constraint fails')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete site because it has associated complaints. Please delete or reassign the complaints first.'
                ], 409);
            }

            // Re-throw the exception if it's not a foreign key constraint issue
            throw $e;
        }
    }
}
