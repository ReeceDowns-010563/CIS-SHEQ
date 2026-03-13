<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

/**
 * @OA\Tag(
 *     name="Customers",
 *     description="Customer management endpoints"
 * )
 */
class CustomerApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/customers",
     *     summary="Get list of customers",
     *     description="Retrieve a paginated list of customers with optional filtering and searching",
     *     operationId="getCustomers",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximum number of customers to return (1-100)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=50)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search customers by name, email, or phone",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Parameter(
     *         name="active",
     *         in="query",
     *         description="Filter customers by active status (accepts: true, false, 1, 0)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"true", "false", "1", "0"},
     *             example="true"
     *         )
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
     *                     @OA\Property(property="name", type="string", example="ABC Parking Ltd"),
     *                     @OA\Property(property="email", type="string", example="contact@abcparking.com"),
     *                     @OA\Property(property="phone", type="string", nullable=true, example="01234567890"),
     *                     @OA\Property(property="address", type="string", nullable=true, example="123 Main Street"),
     *                     @OA\Property(property="postcode", type="string", nullable=true, example="AB12 3CD"),
     *                     @OA\Property(property="contact_person", type="string", nullable=true, example="John Smith"),
     *                     @OA\Property(property="company", type="string", nullable=true, example="ABC Parking Ltd"),
     *                     @OA\Property(property="active", type="boolean", example=true),
     *                     @OA\Property(property="notes", type="string", nullable=true, example="Important client"),
     *                     @OA\Property(property="full_address", type="string", example="123 Main Street, AB12 3CD"),
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
            'search' => 'nullable|string|max:255',
            'active' => 'nullable|in:true,false,1,0',
        ]);

        $limit = $request->get('limit', 50);

        $query = Customer::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($request->has('active')) {
            // Convert string/numeric values to boolean
            $activeValue = filter_var($request->active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($activeValue !== null) {
                $query->where('active', $activeValue);
            }
        }

        $customers = $query->orderBy('name')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $customers,
            'count' => $customers->count(),
            'limit' => $limit
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/customers/{customer}",
     *     summary="Get a specific customer",
     *     description="Retrieve details of a specific customer by ID",
     *     operationId="getCustomer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         description="Customer ID",
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
     *                 @OA\Property(property="name", type="string", example="ABC Parking Ltd"),
     *                 @OA\Property(property="email", type="string", example="contact@abcparking.com"),
     *                 @OA\Property(property="phone", type="string", nullable=true, example="01234567890"),
     *                 @OA\Property(property="address", type="string", nullable=true, example="123 Main Street"),
     *                 @OA\Property(property="postcode", type="string", nullable=true, example="AB12 3CD"),
     *                 @OA\Property(property="contact_person", type="string", nullable=true, example="John Smith"),
     *                 @OA\Property(property="company", type="string", nullable=true, example="ABC Parking Ltd"),
     *                 @OA\Property(property="active", type="boolean", example=true),
     *                 @OA\Property(property="notes", type="string", nullable=true, example="Important client"),
     *                 @OA\Property(property="full_address", type="string", example="123 Main Street, AB12 3CD")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Customer not found")
     * )
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $customer
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/customers",
     *     summary="Create a new customer",
     *     description="Create a new customer with the provided information",
     *     operationId="createCustomer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="ABC Parking Ltd"),
     *             @OA\Property(property="email", type="string", format="email", example="contact@abcparking.com"),
     *             @OA\Property(property="phone", type="string", nullable=true, example="01234567890"),
     *             @OA\Property(property="address", type="string", nullable=true, example="123 Main Street"),
     *             @OA\Property(property="postcode", type="string", nullable=true, example="AB12 3CD"),
     *             @OA\Property(property="contact_person", type="string", nullable=true, example="John Smith"),
     *             @OA\Property(property="company", type="string", nullable=true, example="ABC Parking Ltd"),
     *             @OA\Property(property="active", type="boolean", example=true, default=true),
     *             @OA\Property(property="notes", type="string", nullable=true, example="Important client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="ABC Parking Ltd"),
     *                 @OA\Property(property="email", type="string", example="contact@abcparking.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        // Set default value for active
        $validated['active'] = $validated['active'] ?? true;

        $customer = Customer::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/customers/{customer}",
     *     summary="Update a customer",
     *     description="Update an existing customer with new information",
     *     operationId="updateCustomer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         description="Customer ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="ABC Parking Ltd"),
     *             @OA\Property(property="email", type="string", format="email", example="contact@abcparking.com"),
     *             @OA\Property(property="phone", type="string", nullable=true, example="01234567890"),
     *             @OA\Property(property="address", type="string", nullable=true, example="123 Main Street"),
     *             @OA\Property(property="postcode", type="string", nullable=true, example="AB12 3CD"),
     *             @OA\Property(property="contact_person", type="string", nullable=true, example="John Smith"),
     *             @OA\Property(property="company", type="string", nullable=true, example="ABC Parking Ltd"),
     *             @OA\Property(property="active", type="boolean", example=true),
     *             @OA\Property(property="notes", type="string", nullable=true, example="Important client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="ABC Parking Ltd"),
     *                 @OA\Property(property="email", type="string", example="contact@abcparking.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Customer not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $customer->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully',
            'data' => $customer
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/customers/{customer}",
     *     summary="Delete a customer",
     *     description="Delete an existing customer. Consider archiving instead of deleting if the customer has associated data.",
     *     operationId="deleteCustomer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         description="Customer ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Cannot delete customer with associated records",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Cannot delete customer because they have associated sites or complaints. Consider archiving the customer instead (set active = false) to maintain data integrity.")
     *         )
     *     )
     * )
     */
    public function destroy(Customer $customer): JsonResponse
    {
        try {
            $customer->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer deleted successfully'
            ]);
        } catch (QueryException $e) {
            // Check if this is a foreign key constraint violation
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'foreign key constraint fails')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete customer because they have associated sites or complaints. Consider archiving the customer instead (set active = false) to maintain data integrity.'
                ], 409);
            }

            // Re-throw the exception if it's not a foreign key constraint issue
            throw $e;
        }
    }
}
