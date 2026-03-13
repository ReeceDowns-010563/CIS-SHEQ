<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use Carbon\Carbon;

/**
 * Controller responsible for synchronizing data between external TimeGate API
 * and local database. Handles branches, sites, and employee data synchronization.
 * 
 * CRITICAL: API is treated as the source of truth for all data.
 * Automatically manages user branch access when sites change branches.
 */
class TimegateController extends Controller
{
    /**
     * Synchronize branch data from external API to local database.
     * Handles pagination to retrieve all branches and maintains data integrity.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function synchronizeBranches()
    {
        // Set execution limits for synchronization
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        try {
            $apiCredentials = $this->validateApiConfiguration();
            if (!$apiCredentials['valid']) {
                return $this->errorResponse('API configuration missing', 500);
            }

            $allApiBranches = $this->fetchAllBranchesFromApi($apiCredentials);
            
            // Validate API returned data
            if (empty($allApiBranches)) {
                Log::warning('Branch sync: API returned empty data - skipping sync to prevent data loss');
                return response()->json([
                    'success' => false,
                    'error' => 'API returned no branch data. Sync aborted to prevent accidental deactivation of all branches.',
                    'timestamp' => now()
                ], 500);
            }
            
            $result = $this->processBranchSynchronization($allApiBranches);

            Log::info("Branch synchronization completed", $result);

            return response()->json([
                'success' => true,
                'message' => "Branch sync completed: {$result['added']} added, {$result['updated']} updated, {$result['deactivated']} deactivated",
                'stats' => $result,
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            return $this->handleSyncException('Branch synchronization', $e);
        }
    }

    /**
     * Synchronize site data from external API to local database.
     * Maintains relationships with branches and handles site lifecycle management.
     * Includes automatic user branch access management when sites change branches.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function synchronizeSites()
    {
        // Set execution limits for synchronization
        ini_set('max_execution_time', 480);
        ini_set('memory_limit', '512M');

        try {
            $apiCredentials = $this->validateApiConfiguration();
            if (!$apiCredentials['valid']) {
                return $this->errorResponse('API configuration missing', 500);
            }

            Log::info("Starting site synchronization");

            // Fetch ALL sites until an empty page is returned (complete coverage).
            $allApiSites = $this->fetchAllSitesFromApi($apiCredentials);
            
            // CRITICAL validation - API must return reasonable data
            if (empty($allApiSites)) {
                Log::warning('Site sync: API returned empty data - skipping sync to prevent data loss');
                return response()->json([
                    'success' => false,
                    'error' => 'API returned no site data. Sync aborted to prevent accidental deactivation of all sites.',
                    'timestamp' => now()
                ], 500);
            }
            
            // Sanity check - detect suspiciously large data drops
            $dbSiteCount = DB::table('sites')->where('active', 1)->count();
            $apiSiteCount = count($allApiSites);
            
            if ($apiSiteCount < ($dbSiteCount * 0.5) && $dbSiteCount > 10) {
                Log::error("Site sync: API returned suspiciously few sites", [
                    'api_count' => $apiSiteCount,
                    'db_count' => $dbSiteCount,
                    'difference_percent' => round((($dbSiteCount - $apiSiteCount) / $dbSiteCount) * 100, 2)
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => "API returned only {$apiSiteCount} sites while database has {$dbSiteCount} active sites. This is a >50% reduction and may indicate an API issue. Sync aborted for safety.",
                    'timestamp' => now()
                ], 500);
            }

            Log::info("API validation passed", [
                'api_sites' => $apiSiteCount,
                'db_sites' => $dbSiteCount
            ]);

            // Process sites and handle branch changes with user access updates
            $result = $this->processSiteSynchronization($allApiSites);

            // Build comprehensive message including user access changes
            $message = "Site sync completed: {$result['added']} added, {$result['updated']} updated, {$result['deactivated']} deactivated";
            
            if (isset($result['branch_changes_detected']) && $result['branch_changes_detected'] > 0) {
                $message .= ", {$result['branch_changes_detected']} sites changed branches";
                if (isset($result['branch_access_records_added'])) {
                    $message .= ", {$result['branch_access_records_added']} user branch access records added";
                }
                if (isset($result['branch_access_records_removed'])) {
                    $message .= ", {$result['branch_access_records_removed']} obsolete branch access records removed";
                }
            }

            Log::info($message, $result);

            return response()->json([
                'success' => true,
                'message' => $message,
                'stats' => $result,
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            return $this->handleSyncException('Site synchronization', $e);
        }
    }

    /**
     * Synchronize employee data from external API to local database.
     * Implements comprehensive employee lifecycle management with duplicate prevention.
     * API is treated as the source of truth for all employee data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function synchronizeEmployees()
    {
        // Increase execution limits for large datasets
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $errors = [];

        try {
            $apiCredentials = $this->validateApiConfiguration();
            if (!$apiCredentials['valid']) {
                return $this->errorResponse('API configuration missing', 500);
            }

            Log::info("Starting comprehensive employee synchronization");

            // Phase 1: Retrieve all employee data from API
            $apiEmployees = $this->fetchAllEmployeesFromApi($apiCredentials);
            Log::info("Retrieved " . count($apiEmployees) . " employees from API");

            // Phase 2: Get current database state
            $currentEmployees = $this->getCurrentEmployeesFromDatabase();
            Log::info("Found " . $currentEmployees->count() . " employees with employee numbers in local database");

            // Get total employee count for reporting
            $totalDbEmployees = DB::table('employees')->count();
            Log::info("Total employees in database: " . $totalDbEmployees);

            // Phase 3: Create processing maps for efficient lookups
            $siteMapping = $this->createSiteMapping();
            $apiEmployeesByPin = $this->indexEmployeesByPin($apiEmployees);
            $apiEmployeesByTimegateId = $this->indexEmployeesByTimegateId($apiEmployees);

            $stats = [
                'added' => 0,
                'updated' => 0,
                'marked_as_leavers' => 0,
                'marked_as_active' => 0,
                'errors' => 0,
                'skipped_duplicates' => 0,
                'duplicate_pins_found' => 0
            ];

            // Phase 4: Handle duplicate PINs in API data
            $duplicatePinStats = $this->handleDuplicatePinsInApi($apiEmployeesByPin);
            $stats['duplicate_pins_found'] = $duplicatePinStats['total_duplicates'];

            // Phase 5: Process API employees (add new or update existing)
            foreach ($apiEmployees as $apiEmployee) {
                try {
                    $result = $this->processIndividualEmployee($apiEmployee, $currentEmployees, $siteMapping, $apiEmployeesByPin);
                    $stats[$result['action']]++;

                    if ($result['action'] === 'error') {
                        $errors[] = $result['message'];
                    }
                } catch (\Exception $e) {
                    $stats['errors']++;
                    $error = "Failed to process employee {$apiEmployee['TimeGateId']}: " . $e->getMessage();
                    Log::error($error);
                    $errors[] = $error;
                }
            }

            // Phase 6: Process employment status based on API termination dates
            $statusUpdateStats = $this->updateEmployeeStatusBasedOnTermination($apiEmployees, $currentEmployees);
            $stats['marked_as_leavers'] += $statusUpdateStats['marked_as_leavers'];
            $stats['marked_as_active'] += $statusUpdateStats['marked_as_active'];

            $message = "Employee sync completed: {$stats['added']} added, {$stats['updated']} updated, {$stats['marked_as_leavers']} marked as leavers, {$stats['marked_as_active']} reactivated";
            if ($stats['duplicate_pins_found'] > 0) {
                $message .= ", {$stats['duplicate_pins_found']} duplicate PINs handled";
            }
            if ($stats['errors'] > 0) {
                $message .= ", {$stats['errors']} errors occurred";
            }

            Log::info($message);

            return response()->json([
                'success' => true,
                'message' => $message,
                'stats' => array_merge($stats, [
                    'total_api_employees' => count($apiEmployees),
                    'total_db_employees' => $totalDbEmployees,
                    'total_db_employees_with_numbers' => $currentEmployees->count(),
                    'total_db_employees_without_numbers' => $totalDbEmployees - $currentEmployees->count()
                ]),
                'errors' => $errors,
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            return $this->handleSyncException('Employee synchronization', $e, $errors);
        }
    }

    /**
     * Execute complete synchronization of all entity types in sequence.
     * Ensures data consistency across branches, sites, and employees.
     * Includes failure detection and early abort for data safety.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function synchronizeAllEntities()
    {
        // Set execution limits for full synchronization
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '1G');

        try {
            $results = [];

            Log::info("Starting full synchronization of all entities");

            // Check each step for success before proceeding
            $branchResult = $this->synchronizeBranches();
            $results['branches'] = json_decode($branchResult->getContent(), true);

            if (!$results['branches']['success']) {
                Log::error("Full sync failed at branch sync", $results['branches']);
                return response()->json([
                    'success' => false,
                    'error' => 'Branch synchronization failed. Aborting full sync.',
                    'results' => $results,
                    'timestamp' => now()
                ], 500);
            }

            $siteResult = $this->synchronizeSites();
            $results['sites'] = json_decode($siteResult->getContent(), true);

            if (!$results['sites']['success']) {
                Log::error("Full sync failed at site sync", $results['sites']);
                return response()->json([
                    'success' => false,
                    'error' => 'Site synchronization failed. Aborting full sync.',
                    'results' => $results,
                    'timestamp' => now()
                ], 500);
            }

            $employeeResult = $this->synchronizeEmployees();
            $results['employees'] = json_decode($employeeResult->getContent(), true);

            Log::info("Full synchronization completed successfully");

            return response()->json([
                'success' => true,
                'message' => 'All entities synchronized successfully',
                'results' => $results,
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            return $this->handleSyncException('Full synchronization', $e);
        }
    }

    /**
     * Search for a specific employee by employee number within a branch.
     * Searches both external API and local database for comprehensive results.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchEmployeeInBranch(Request $request)
    {
        try {
            $branchCode = $request->input('branch_code', 'HO');
            $employeeNumber = $request->input('employee_number', '010563');

            Log::info("Searching for employee {$employeeNumber} in branch {$branchCode}");

            $apiCredentials = $this->validateApiConfiguration();
            $searchResults = [
                'api' => ['found' => false, 'searched' => $apiCredentials['valid']],
                'database' => ['found' => false, 'searched' => true]
            ];

            // Search API first if credentials are available
            if ($apiCredentials['valid']) {
                $apiResult = $this->searchEmployeeInApiByBranch($branchCode, $employeeNumber, $apiCredentials);
                if ($apiResult) {
                    $searchResults['api'] = [
                        'found' => true,
                        'employee' => $this->formatApiEmployeeData($apiResult)
                    ];
                }
            }

            // Search local database
            $dbResult = $this->searchEmployeeInDatabaseByBranch($branchCode, $employeeNumber);
            if ($dbResult) {
                $searchResults['database'] = [
                    'found' => true,
                    'employee' => $this->formatDatabaseEmployeeData($dbResult)
                ];
            }

            if ($searchResults['api']['found'] || $searchResults['database']['found']) {
                return response()->json([
                    'success' => true,
                    'message' => "Employee {$employeeNumber} found in branch {$branchCode}",
                    'sources' => $searchResults,
                    'timestamp' => now()
                ]);
            }

            // Not found in either source - provide debugging information
            return $this->handleEmployeeNotFound($branchCode, $employeeNumber, $searchResults);

        } catch (\Exception $e) {
            Log::error("Employee search failed: " . $e->getMessage());
            return $this->errorResponse('Employee search failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update user branch access when sites change branches.
     * 
     * CRITICAL: When a site moves to a different branch, users who have access to that site
     * need to be given access to the new branch so they can still reach their site.
     * 
     * Logic:
     * - If user has access to a site, they should have access to that site's current branch
     * - Add user to new branch if needed
     * - Remove user from old branch if they have no other sites there
     * - Skip users with "all branches" access
     *
     * @param array $sitesBranchChanges Array of sites that changed branches
     * @return array Statistics about changes made
     */
    private function updateUserBranchAccessAfterSiteChanges(array $sitesBranchChanges): array
    {
        $stats = [
            'branch_access_records_added' => 0,
            'branch_access_records_removed' => 0,
            'users_affected_by_branch_changes' => 0,
            'user_access_errors' => 0
        ];

        try {
            $affectedUserIds = collect();

            foreach ($sitesBranchChanges as $change) {
                try {
                    // Validate change data
                    if (!isset($change['site_id']) || !isset($change['new_branch_id'])) {
                        Log::warning("Invalid branch change data, skipping", ['change' => $change]);
                        $stats['user_access_errors']++;
                        continue;
                    }

                    // Find all users who have explicit access to this site
                    $usersWithSiteAccess = DB::table('user_site_access')
                        ->where('site_id', $change['site_id'])
                        ->pluck('user_id')
                        ->unique();

                    if ($usersWithSiteAccess->isEmpty()) {
                        Log::debug("No users have access to site {$change['site_code']}, skipping user access updates");
                        continue;
                    }

                    Log::info("Site {$change['site_code']} moved from branch {$change['old_branch_id']} to {$change['new_branch_id']}", [
                        'affected_users_count' => $usersWithSiteAccess->count(),
                        'site_name' => $change['site_name'] ?? 'Unknown'
                    ]);

                    foreach ($usersWithSiteAccess as $userId) {
                        try {
                            $affectedUserIds->push($userId);

                            // Check if user has "all branches" access
                            $userAccessSettings = DB::table('user_access_settings')
                                ->where('user_id', $userId)
                                ->first();

                            if ($userAccessSettings && $userAccessSettings->all_branches == 1) {
                                // User has access to all branches, no need to update
                                Log::debug("User {$userId} has all_branches access, skipping");
                                continue;
                            }

                            // STEP 1: Ensure user has access to the NEW branch
                            $hasNewBranchAccess = DB::table('user_branch_access')
                                ->where('user_id', $userId)
                                ->where('branch_id', $change['new_branch_id'])
                                ->exists();

                            if (!$hasNewBranchAccess) {
                                // Add access to new branch
                                DB::table('user_branch_access')->insert([
                                    'user_id' => $userId,
                                    'branch_id' => $change['new_branch_id'],
                                    'all_sites_in_branch' => 0,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);

                                $stats['branch_access_records_added']++;
                                
                                Log::info("Granted user {$userId} access to branch {$change['new_branch_id']} ({$change['new_branch_code']}) because they have access to site {$change['site_code']}");
                            } else {
                                Log::debug("User {$userId} already has access to new branch {$change['new_branch_id']}");
                            }

                            // STEP 2: Check if user should be removed from OLD branch
                            if ($change['old_branch_id']) {
                                // Count how many OTHER sites user has in the old branch
                                $otherSitesInOldBranch = DB::table('user_site_access as usa')
                                    ->join('sites as s', 'usa.site_id', '=', 's.id')
                                    ->where('usa.user_id', $userId)
                                    ->where('s.branch_id', $change['old_branch_id'])
                                    ->where('s.id', '!=', $change['site_id']) // Exclude the site that just moved
                                    ->where('s.active', 1) // Only count active sites
                                    ->count();

                                // If user has NO other sites in the old branch, remove their access to old branch
                                if ($otherSitesInOldBranch == 0) {
                                    $deleted = DB::table('user_branch_access')
                                        ->where('user_id', $userId)
                                        ->where('branch_id', $change['old_branch_id'])
                                        ->delete();

                                    if ($deleted > 0) {
                                        $stats['branch_access_records_removed']++;
                                        
                                        Log::info("Removed user {$userId} access from old branch {$change['old_branch_id']} ({$change['old_branch_code']}) - no remaining sites in that branch");
                                    }
                                } else {
                                    Log::debug("User {$userId} still has {$otherSitesInOldBranch} other sites in old branch {$change['old_branch_id']}, keeping branch access");
                                }
                            }

                        } catch (\Exception $e) {
                            Log::error("Error updating branch access for user {$userId}: " . $e->getMessage(), [
                                'user_id' => $userId,
                                'site_change' => $change,
                                'error' => $e->getMessage()
                            ]);
                            $stats['user_access_errors']++;
                        }
                    }

                } catch (\Exception $e) {
                    Log::error("Error processing branch change for site: " . $e->getMessage(), [
                        'change' => $change,
                        'error' => $e->getMessage()
                    ]);
                    $stats['user_access_errors']++;
                }
            }

            $stats['users_affected_by_branch_changes'] = $affectedUserIds->unique()->count();

            if ($stats['branch_access_records_added'] > 0 || $stats['branch_access_records_removed'] > 0) {
                Log::info("User branch access updates completed", [
                    'unique_users_affected' => $stats['users_affected_by_branch_changes'],
                    'branch_access_added' => $stats['branch_access_records_added'],
                    'branch_access_removed' => $stats['branch_access_records_removed']
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Critical error updating user branch access: " . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return $stats;
    }

    /**
     * Identify and log duplicate PINs in API data for resolution tracking.
     * Provides comprehensive reporting of duplicate employee records.
     *
     * @param array $apiEmployeesByPin
     * @return array
     */
    private function handleDuplicatePinsInApi(array $apiEmployeesByPin): array
    {
        $duplicateCount = 0;
        $duplicatePins = [];

        foreach ($apiEmployeesByPin as $pin => $employees) {
            if (count($employees) > 1) {
                $duplicateCount += count($employees);
                $duplicatePins[] = [
                    'pin' => $pin,
                    'count' => count($employees),
                    'timegate_ids' => array_column($employees, 'TimeGateId'),
                    'names' => array_map(function($emp) {
                        return trim(($emp['FirstName'] ?? '') . ' ' . ($emp['LastName'] ?? ''));
                    }, $employees)
                ];

                Log::warning("Duplicate PIN found in API: {$pin}", [
                    'count' => count($employees),
                    'timegate_ids' => array_column($employees, 'TimeGateId'),
                    'employees' => array_map(function($emp) {
                        return [
                            'timegate_id' => $emp['TimeGateId'],
                            'name' => trim(($emp['FirstName'] ?? '') . ' ' . ($emp['LastName'] ?? '')),
                            'terminated' => $emp['TerminatedDate'] ?? null
                        ];
                    }, $employees)
                ]);
            }
        }

        if (!empty($duplicatePins)) {
            Log::info("Summary of duplicate PINs found in API:", [
                'total_duplicate_pins' => count($duplicatePins),
                'total_duplicate_records' => $duplicateCount,
                'details' => $duplicatePins
            ]);
        }

        return [
            'total_duplicates' => $duplicateCount,
            'duplicate_pins' => $duplicatePins
        ];
    }

    /**
     * Update employee status based on API termination dates.
     * Synchronizes employment status between API data and local database.
     *
     * @param array $apiEmployees
     * @param \Illuminate\Support\Collection $currentEmployees
     * @return array
     */
    private function updateEmployeeStatusBasedOnTermination(array $apiEmployees, \Illuminate\Support\Collection $currentEmployees): array
    {
        $stats = [
            'marked_as_leavers' => 0,
            'marked_as_active' => 0
        ];

        // Create lookup of API employees by PIN with their termination status
        $apiEmployeeStatus = [];
        foreach ($apiEmployees as $apiEmployee) {
            $pin = trim($apiEmployee['Pin'] ?? '');
            if (!empty($pin)) {
                $isTerminated = !is_null($apiEmployee['TerminatedDate'] ?? null);
                $apiEmployeeStatus[$pin] = $isTerminated;
            }
        }

        // Process each database employee
        foreach ($currentEmployees as $dbEmployee) {
            $pin = $dbEmployee->employee_number;

            // Skip if PIN is not in API (employee might be manually added or API incomplete)
            if (!isset($apiEmployeeStatus[$pin])) {
                Log::info("Employee PIN {$pin} not found in API data - skipping status update");
                continue;
            }

            $isTerminatedInApi = $apiEmployeeStatus[$pin];
            $currentStatus = $dbEmployee->status;

            // Update status based on API termination status
            if ($isTerminatedInApi && $currentStatus === 'active') {
                DB::table('employees')
                    ->where('employee_number', $pin)
                    ->update([
                        'status' => 'leaver',
                        'updated_at' => now()
                    ]);

                $stats['marked_as_leavers']++;
                Log::info("Marked employee {$pin} as leaver based on API termination date");

            } elseif (!$isTerminatedInApi && $currentStatus === 'leaver') {
                DB::table('employees')
                    ->where('employee_number', $pin)
                    ->update([
                        'status' => 'active',
                        'updated_at' => now()
                    ]);

                $stats['marked_as_active']++;
                Log::info("Reactivated employee {$pin} - no termination date in API");
            }
        }

        return $stats;
    }

    /**
     * Process individual employee from API - either create new or update existing.
     * Handles duplicate PIN resolution and employment status validation.
     *
     * @param array $apiEmployee
     * @param \Illuminate\Support\Collection $currentEmployees
     * @param \Illuminate\Support\Collection $siteMapping
     * @param array $apiEmployeesByPin
     * @return array
     */
    private function processIndividualEmployee(array $apiEmployee, \Illuminate\Support\Collection $currentEmployees, \Illuminate\Support\Collection $siteMapping, array $apiEmployeesByPin): array
    {
        $timegateId = $apiEmployee['TimeGateId'];
        $employeePin = trim($apiEmployee['Pin'] ?? '');
        $isTerminated = !is_null($apiEmployee['TerminatedDate'] ?? null);

        // Skip employees without PIN as they cannot be properly identified
        if (empty($employeePin)) {
            return [
                'action' => 'skipped_duplicates',
                'message' => "Skipped employee {$timegateId} - no PIN provided"
            ];
        }

        // Handle duplicate PIN resolution in API data
        if (isset($apiEmployeesByPin[$employeePin]) && count($apiEmployeesByPin[$employeePin]) > 1) {
            $duplicateEmployees = $apiEmployeesByPin[$employeePin];

            // Prioritize active employees over terminated ones
            $activeEmployees = array_filter($duplicateEmployees, function($emp) {
                return is_null($emp['TerminatedDate'] ?? null);
            });

            if (count($activeEmployees) === 1) {
                $activeEmployee = array_values($activeEmployees)[0];
                if ($activeEmployee['TimeGateId'] !== $timegateId) {
                    return [
                        'action' => 'skipped_duplicates',
                        'message' => "Duplicate PIN {$employeePin} - skipping terminated employee {$timegateId}, processing active employee {$activeEmployee['TimeGateId']} instead"
                    ];
                }
            } elseif (count($activeEmployees) > 1) {
                $firstActiveEmployee = array_values($activeEmployees)[0];
                if ($firstActiveEmployee['TimeGateId'] !== $timegateId) {
                    return [
                        'action' => 'skipped_duplicates',
                        'message' => "Duplicate PIN {$employeePin} with multiple active employees - skipping {$timegateId}, processing {$firstActiveEmployee['TimeGateId']} instead"
                    ];
                }
            } else {
                $firstEmployee = $duplicateEmployees[0];
                if ($firstEmployee['TimeGateId'] !== $timegateId) {
                    return [
                        'action' => 'skipped_duplicates',
                        'message' => "Duplicate PIN {$employeePin} (all terminated) - skipping {$timegateId}, processing {$firstEmployee['TimeGateId']} instead"
                    ];
                }
            }
        }

        // Check if employee exists in database by PIN
        $existingEmployee = $currentEmployees->get($employeePin);

        if (!$existingEmployee) {
            if (!$isTerminated) {
                try {
                    $this->createNewEmployee($apiEmployee, $siteMapping);
                    return ['action' => 'added', 'message' => "Created new active employee {$timegateId} with PIN {$employeePin}"];
                } catch (\Exception $e) {
                    if (str_contains($e->getMessage(), 'already exists')) {
                        return [
                            'action' => 'skipped_duplicates',
                            'message' => "Skipped creating employee {$timegateId}: " . $e->getMessage()
                        ];
                    }
                    throw $e;
                }
            } else {
                return ['action' => 'skipped_duplicates', 'message' => "Skipped creating terminated employee {$timegateId} with PIN {$employeePin}"];
            }
        } else {
            try {
                $updated = $this->updateExistingEmployee($apiEmployee, $existingEmployee, $isTerminated);
                if ($updated) {
                    $statusMessage = $isTerminated ? "terminated" : "active";
                    return ['action' => 'updated', 'message' => "Updated employee {$timegateId} with PIN {$employeePin} (status: {$statusMessage})"];
                } else {
                    return ['action' => 'skipped_duplicates', 'message' => "No changes needed for employee {$timegateId} with PIN {$employeePin}"];
                }
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    return [
                        'action' => 'skipped_duplicates',
                        'message' => "Skipped updating employee {$timegateId} due to duplicate constraint"
                    ];
                }
                throw $e;
            }
        }
    }

    /**
     * Validate API configuration credentials for external service access.
     * Ensures required configuration values are present before attempting API calls.
     *
     * @return array
     */
    private function validateApiConfiguration(): array
    {
        $apiToken = config('services.api.token');
        $baseUrl = config('services.api.base_url');

        return [
            'valid' => !empty($apiToken) && !empty($baseUrl),
            'token' => $apiToken,
            'base_url' => $baseUrl
        ];
    }

    /**
     * Fetch all branches from external API with pagination handling.
     * Retrieves complete branch dataset using paginated API requests.
     *
     * @param array $credentials
     * @return array
     * @throws \Exception
     */
    private function fetchAllBranchesFromApi(array $credentials): array
    {
        $allBranches = [];
        $page = 1;
        $limit = 20;

        do {
            $response = Http::withToken($credentials['token'])
                ->timeout(30)
                ->get("{$credentials['base_url']}/list/branch", [
                    'page' => $page,
                    'limit' => $limit
                ]);

            if ($response->failed()) {
                throw new \Exception("Branch API request failed: " . $response->status());
            }

            $data = $response->json();
            $pageData = $data['data'] ?? [];

            if (is_array($pageData)) {
                $allBranches = array_merge($allBranches, $pageData);
            }

            $hasMorePages = count($pageData) === $limit;
            $page++;

        } while ($hasMorePages);

        return $allBranches;
    }

    /**
     * Process branch synchronization between API and database.
     * Manages branch lifecycle including creation, updates, and deactivation.
     *
     * @param array $apiBranches
     * @return array
     */
    private function processBranchSynchronization(array $apiBranches): array
    {
        $currentBranches = DB::table('branches')
            ->select('branch_code', 'branch_name', 'TimeGateId', 'active')
            ->get()
            ->keyBy('branch_code');

        $apiBranchCodes = collect($apiBranches)->pluck('Code')->toArray();
        $stats = ['added' => 0, 'updated' => 0, 'deactivated' => 0];

        // Process each API branch
        foreach ($apiBranches as $apiBranch) {
            $existingBranch = $currentBranches->get($apiBranch['Code']);
            $timeGateId = $apiBranch['TimeGateId'] ?? null;

            if (!$existingBranch) {
                $this->insertNewBranch($apiBranch, $timeGateId);
                $stats['added']++;
            } else {
                if ($this->branchRequiresUpdate($existingBranch, $apiBranch, $timeGateId)) {
                    $this->updateExistingBranch($apiBranch['Code'], $apiBranch, $timeGateId);
                    $stats['updated']++;
                }
            }
        }

        // Deactivate branches that exist in database but not in API
        $branchesToDeactivate = $currentBranches->keys()->diff($apiBranchCodes);
        if ($branchesToDeactivate->isNotEmpty()) {
            $stats['deactivated'] = DB::table('branches')
                ->whereIn('branch_code', $branchesToDeactivate->toArray())
                ->where('active', 1)
                ->update(['active' => 0, 'updated_at' => now()]);
        }

        return array_merge($stats, [
            'total_api_branches' => count($apiBranches),
            'total_db_branches' => $currentBranches->count()
        ]);
    }

    /**
     * Fetch all sites from external API until an empty page is returned.
     * Ensures 100% coverage by continuing page-by-page until no data is returned.
     * 
     * Features:
     * - Increased max retries to 5
     * - Increased timeout to 120 seconds
     * - Increased delay between pages to 0.5s
     * - Skips problematic pages instead of aborting entire sync
     * - Tracks failed pages for logging and investigation
     * - Stops only on 3 consecutive failures (with safety checks)
     * - Better error handling for connection timeouts
     * - Enhanced logging for troubleshooting
     *
     * @param array $credentials
     * @return array
     * @throws \Exception
     */
    private function fetchAllSitesFromApi(array $credentials): array
    {
        $allSites = [];
        $page = 1;
        $limit = 20;
        $maxRetries = 5;
        $startTime = time();
        $maxExecutionTime = 600; // Hard cap to avoid runaway execution
        $failedPages = [];

        Log::info("Starting site synchronization (complete coverage)", [
            'endpoint' => "{$credentials['base_url']}/list/site",
            'limit' => $limit,
            'max_retries' => $maxRetries
        ]);

        while (true) {
            // Stop if we're approaching a hard execution limit
            if ((time() - $startTime) > $maxExecutionTime) {
                throw new \Exception("Site API fetch aborted due to execution time limit. Collected=" . count($allSites) . ", last_page={$page}");
            }

            $retries = 0;
            $fetched = false;

            while ($retries < $maxRetries && !$fetched) {
                try {
                    Log::debug("Attempting to fetch site page {$page}, attempt " . ($retries + 1));
                    
                    $response = Http::withToken($credentials['token'])
                        ->withHeaders([
                            'User-Agent' => 'CIS-Security-Sync/1.0',
                            'Accept' => 'application/json'
                        ])
                        ->timeout(120)
                        ->get("{$credentials['base_url']}/list/site", [
                            'page' => $page,
                            'limit' => $limit
                        ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $pageData = $data['data'] ?? [];

                        // Stop condition: empty data array means no more pages
                        if (!is_array($pageData) || empty($pageData)) {
                            Log::info("Sites paging complete - empty data returned", [
                                'final_page' => $page,
                                'total_sites' => count($allSites),
                                'failed_pages' => count($failedPages) > 0 ? $failedPages : 'none'
                            ]);
                            
                            if (count($failedPages) > 0) {
                                Log::warning("Site sync completed with some failed pages", [
                                    'failed_pages' => $failedPages,
                                    'successful_pages' => $page - 1 - count($failedPages),
                                    'total_sites_collected' => count($allSites)
                                ]);
                            }
                            
                            return $allSites;
                        }

                        // Merge and proceed to next page
                        $allSites = array_merge($allSites, $pageData);
                        Log::info("Fetched sites page {$page}", [
                            'rows' => count($pageData),
                            'total_so_far' => count($allSites)
                        ]);

                        $page++;
                        $fetched = true;

                        usleep(500000); // 0.5s delay

                    } else {
                        $status = $response->status();
                        Log::warning("Site API non-success status", [
                            'page' => $page,
                            'status' => $status,
                            'attempt' => $retries + 1,
                            'response_body' => $response->body()
                        ]);

                        if (in_array($status, [403, 429], true)) {
                            // Exponential backoff for rate limiting
                            $backoff = min(60, pow(2, $retries) * 5);
                            Log::info("Backoff for {$backoff}s (status {$status})");
                            sleep($backoff);
                            $retries++;
                        } else {
                            // Other HTTP errors - retry with backoff
                            $retries++;
                            if ($retries >= $maxRetries) {
                                Log::error("Unrecoverable HTTP error on page {$page}, will skip", [
                                    'status' => $status,
                                    'retries' => $retries
                                ]);
                            } else {
                                sleep(min(30, $retries * 5));
                            }
                        }
                    }

                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    Log::error("Site API connection/timeout exception", [
                        'page' => $page,
                        'attempt' => $retries + 1,
                        'message' => $e->getMessage(),
                        'type' => 'connection_timeout'
                    ]);
                    $retries++;
                    $backoff = min(30, $retries * 10);
                    Log::info("Connection error backoff: {$backoff}s");
                    sleep($backoff);
                    
                } catch (\Illuminate\Http\Client\RequestException $e) {
                    Log::error("Site API request exception", [
                        'page' => $page,
                        'attempt' => $retries + 1,
                        'message' => $e->getMessage()
                    ]);
                    $retries++;
                    sleep(min(30, $retries * 5));
                    
                } catch (\Exception $e) {
                    Log::error("Site API general exception", [
                        'page' => $page,
                        'attempt' => $retries + 1,
                        'message' => $e->getMessage(),
                        'type' => get_class($e)
                    ]);
                    $retries++;
                    sleep(min(30, $retries * 5));
                }
            }

            // Improved error handling - skip failed page and continue
            if (!$fetched) {
                $failedPages[] = $page;
                Log::error("Failed to fetch page {$page} after {$maxRetries} retries - SKIPPING TO NEXT PAGE", [
                    'page' => $page,
                    'retries' => $maxRetries,
                    'total_collected' => count($allSites),
                    'failed_pages_so_far' => $failedPages
                ]);
                
                // Skip to next page instead of stopping completely
                $page++;
                
                // Safety check - stop if 3 consecutive pages fail
                if (count($failedPages) >= 3) {
                    $lastThreeFailures = array_slice($failedPages, -3);
                    $isConsecutive = ($lastThreeFailures[2] - $lastThreeFailures[0]) === 2;
                    
                    if ($isConsecutive) {
                        Log::critical("3 consecutive page failures detected, stopping sync", [
                            'failed_pages' => $failedPages,
                            'consecutive_failures' => $lastThreeFailures,
                            'total_sites_collected' => count($allSites)
                        ]);
                        
                        if (count($allSites) < 100) {
                            throw new \Exception("Site API failed on 3 consecutive pages (pages " . implode(', ', $lastThreeFailures) . "). Only collected " . count($allSites) . " sites. This may indicate a major API issue.");
                        }
                        
                        Log::warning("Returning partial site data due to consecutive failures", [
                            'sites_collected' => count($allSites),
                            'failed_pages' => $failedPages,
                            'warning' => 'Data may be incomplete'
                        ]);
                        
                        return $allSites;
                    }
                }
                
                sleep(2);
            }
        }
    }

    /**
     * Process site synchronization between API and database.
     * Manages site lifecycle: sets sites present in API to active=1 (creating/updating as needed),
     * and sets to active=0 any sites present in DB but not returned by the API.
     * Tracks branch changes and automatically updates user branch access.
     *
     * @param array $apiSites
     * @return array
     */
    private function processSiteSynchronization(array $apiSites): array
    {
        $stats = [
            'added' => 0,
            'updated' => 0,
            'deactivated' => 0,
            'branch_changes_detected' => 0,
            'branch_access_records_added' => 0,
            'branch_access_records_removed' => 0,
            'users_affected_by_branch_changes' => 0,
            'errors' => 0
        ];

        try {
            $currentSites = DB::table('sites')
                ->select('id', 'site_code', 'name', 'branch_code', 'branch_id', 'TimeGateId', 'active')
                ->get()
                ->keyBy('site_code');

            $apiSiteCodes = collect($apiSites)->pluck('Code')->filter()->values()->toArray();
            $branchChanges = [];

            Log::info("Processing site synchronization", [
                'api_count' => count($apiSites),
                'db_count' => $currentSites->count()
            ]);

            foreach ($apiSites as $apiSite) {
                try {
                    $siteCode = $apiSite['Code'] ?? null;
                    
                    if (!$siteCode) {
                        Log::warning("Skipping API site without Code", ['site' => $apiSite]);
                        $stats['errors']++;
                        continue;
                    }

                    $existingSite = $currentSites->get($siteCode);
                    $timeGateId = $apiSite['TimeGateId'] ?? null;
                    
                    // Get branch code from API
                    $branchCode = null;
                    if (isset($apiSite['BranchReference']['Code'])) {
                        $branchCode = $apiSite['BranchReference']['Code'];
                    } elseif (isset($apiSite['BranchCode'])) {
                        $branchCode = $apiSite['BranchCode'];
                    }

                    // CRITICAL: Calculate branch_id BEFORE the if/else - needed for both new and existing sites
                    $newBranchId = null;
                    if ($branchCode) {
                        $branch = DB::table('branches')
                            ->where('branch_code', $branchCode)
                            ->first();
                        
                        if ($branch) {
                            $newBranchId = $branch->id;
                        } else {
                            Log::warning("Branch code '{$branchCode}' not found in database for site {$siteCode}");
                        }
                    }

                    if (!$existingSite) {
                        // New site - insert with branch_id
                        $this->insertNewSite($apiSite, $timeGateId, $branchCode, $newBranchId);
                        $stats['added']++;
                        Log::debug("Added new site: {$siteCode}");
                        
                    } else {
                        // Check if branch changed (this affects user access)
                        if ($existingSite->branch_id != $newBranchId && 
                            $newBranchId !== null && 
                            $existingSite->branch_id !== null) {
                            
                            $branchChanges[] = [
                                'site_id' => $existingSite->id,
                                'site_code' => $siteCode,
                                'site_name' => $existingSite->name,
                                'old_branch_id' => $existingSite->branch_id,
                                'new_branch_id' => $newBranchId,
                                'old_branch_code' => $existingSite->branch_code,
                                'new_branch_code' => $branchCode
                            ];
                            
                            Log::info("Branch change detected for site {$siteCode}", [
                                'old_branch_id' => $existingSite->branch_id,
                                'new_branch_id' => $newBranchId,
                                'old_branch_code' => $existingSite->branch_code,
                                'new_branch_code' => $branchCode
                            ]);
                        }

                        // Check if update needed (NOW includes branch_id comparison)
                        $needsUpdate = $this->siteRequiresUpdate($existingSite, $apiSite, $timeGateId, $branchCode, $newBranchId);

                        if ($needsUpdate) {
                            // Update with branch_id
                            $this->updateExistingSite($siteCode, $apiSite, $timeGateId, $branchCode, $newBranchId);
                            $stats['updated']++;
                            Log::debug("Updated site: {$siteCode}");
                        } elseif ((int)$existingSite->active !== 1) {
                            // Reactivate if present in API but inactive in DB
                            DB::table('sites')
                                ->where('site_code', $siteCode)
                                ->update(['active' => 1, 'updated_at' => now()]);
                            $stats['updated']++;
                            Log::info("Reactivated site {$siteCode}");
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing site: " . $e->getMessage(), [
                        'site_code' => $siteCode ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                    $stats['errors']++;
                }
            }

            // Deactivate any DB sites not present in the API response
            $sitesToDeactivate = $currentSites->keys()->filter()->diff($apiSiteCodes);
            if ($sitesToDeactivate->isNotEmpty()) {
                $affected = DB::table('sites')
                    ->whereIn('site_code', $sitesToDeactivate->toArray())
                    ->where('active', 1)
                    ->update(['active' => 0, 'updated_at' => now()]);

                $stats['deactivated'] = $affected;
                Log::info("Deactivated sites not in API", [
                    'count' => $affected,
                    'total_considered' => $sitesToDeactivate->count()
                ]);
            }

            // Handle branch changes and update user branch access
            if (!empty($branchChanges)) {
                $stats['branch_changes_detected'] = count($branchChanges);

                Log::info("Processing {$stats['branch_changes_detected']} site branch changes for user access updates", [
                    'changes' => $branchChanges
                ]);

                try {
                    $userAccessStats = $this->updateUserBranchAccessAfterSiteChanges($branchChanges);
                    $stats = array_merge($stats, $userAccessStats);
                    
                    Log::info("User access updates completed", $userAccessStats);
                } catch (\Exception $e) {
                    Log::error("Failed to update user branch access after site changes: " . $e->getMessage(), [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $stats['errors']++;
                }
            } else {
                Log::info("No branch changes detected - user access remains unchanged");
            }

            Log::info("Site synchronization processing completed", $stats);

        } catch (\Exception $e) {
            Log::error("Critical error in site synchronization processing: " . $e->getMessage());
            throw $e;
        }

        return array_merge($stats, [
            'total_api_sites' => count($apiSites),
            'total_db_sites' => $currentSites->count()
        ]);
    }

    /**
     * Fetch all employees from external API with pagination and rate limiting.
     * Implements robust error handling and retry logic for large dataset retrieval.
     *
     * @param array $credentials
     * @return array
     * @throws \Exception
     */
    private function fetchAllEmployeesFromApi(array $credentials): array
    {
        $allEmployees = [];
        $page = 1;
        $limit = 50;
        $maxPages = 5000;
        $maxRetries = 3;

        do {
            $retries = 0;
            $success = false;

            while ($retries < $maxRetries && !$success) {
                try {
                    Log::info("Fetching employees page {$page} (attempt " . ($retries + 1) . ")");

                    $response = Http::withToken($credentials['token'])
                        ->withHeaders([
                            'User-Agent' => 'CIS-Security-Sync/1.0',
                            'Accept' => 'application/json'
                        ])
                        ->timeout(120)
                        ->get("{$credentials['base_url']}/list/officer", [
                            'page' => $page,
                            'limit' => $limit
                        ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $pageData = $data['data'] ?? [];

                        if (!is_array($pageData) || empty($pageData)) {
                            Log::info("Employees paging complete - empty data at page {$page}");
                            return $allEmployees;
                        }

                        $allEmployees = array_merge($allEmployees, $pageData);
                        $hasMorePages = count($pageData) === $limit;
                        $page++;
                        $success = true;

                        Log::info("Fetched employees page " . ($page - 1) . " (" . count($pageData) . " rows)");

                        if ($hasMorePages) {
                            sleep(2);
                        }

                    } else if ($response->status() === 403) {
                        Log::warning("403 Forbidden on page {$page}, attempt " . ($retries + 1));
                        if ($retries < $maxRetries - 1) {
                            $backoffDelay = pow(2, $retries) * 5;
                            Log::info("Waiting {$backoffDelay} seconds before retry...");
                            sleep($backoffDelay);
                        }
                        $retries++;
                    } else if ($response->status() === 429) {
                        Log::warning("Rate limited on page {$page}, attempt " . ($retries + 1));
                        if ($retries < $maxRetries - 1) {
                            $backoffDelay = 30;
                            Log::info("Rate limited - waiting {$backoffDelay} seconds...");
                            sleep($backoffDelay);
                        }
                        $retries++;
                    } else {
                        throw new \Exception("Employee API request failed on page {$page}: HTTP " . $response->status());
                    }

                } catch (\Exception $e) {
                    if ($retries < $maxRetries - 1) {
                        Log::error("API request failed on page {$page}, attempt " . ($retries + 1) . ": " . $e->getMessage());
                        $retries++;
                        sleep(5);
                    } else {
                        throw $e;
                    }
                }
            }

            if (!$success) {
                throw new \Exception("Failed to fetch page {$page} after {$maxRetries} attempts");
            }

        } while ($page <= $maxPages);

        Log::info("Completed API fetch - total employees: " . count($allEmployees));
        return $allEmployees;
    }

    /**
     * Retrieve current employees from local database for synchronization comparison.
     * Returns employees with employee numbers indexed by PIN for efficient matching.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getCurrentEmployeesFromDatabase(): \Illuminate\Support\Collection
    {
        return DB::table('employees')
            ->select('id', 'timegate_id', 'employee_number', 'first_name', 'last_name', 'email', 'site_code', 'status')
            ->whereNotNull('employee_number')
            ->where('employee_number', '!=', '')
            ->get()
            ->keyBy('employee_number');
    }

    /**
     * Create mapping between site codes and site IDs for efficient lookups.
     * Provides quick site ID resolution during employee processing.
     *
     * @return \Illuminate\Support\Collection
     */
    private function createSiteMapping(): \Illuminate\Support\Collection
    {
        return DB::table('sites')
            ->select('site_code', 'id')
            ->whereNotNull('site_code')
            ->get()
            ->keyBy('site_code');
    }

    /**
     * Create index of API employees by employee PIN for duplicate detection.
     * Enables efficient duplicate PIN identification and resolution.
     *
     * @param array $apiEmployees
     * @return array
     */
    private function indexEmployeesByPin(array $apiEmployees): array
    {
        $index = [];
        foreach ($apiEmployees as $employee) {
            $pin = trim($employee['Pin'] ?? '');
            if (!empty($pin)) {
                if (!isset($index[$pin])) {
                    $index[$pin] = [];
                }
                $index[$pin][] = $employee;
            }
        }
        return $index;
    }

    /**
     * Create index of API employees by TimeGate ID for efficient lookups.
     * Provides fast access to employee records by their unique API identifier.
     *
     * @param array $apiEmployees
     * @return array
     */
    private function indexEmployeesByTimegateId(array $apiEmployees): array
    {
        $index = [];
        foreach ($apiEmployees as $employee) {
            $timegateId = $employee['TimeGateId'] ?? null;
            if ($timegateId) {
                $index[$timegateId] = $employee;
            }
        }
        return $index;
    }

    /**
     * Create new employee record from API data with comprehensive validation.
     * Generates placeholder emails for employees without valid email addresses to satisfy database constraints.
     *
     * @param array $apiEmployee
     * @param \Illuminate\Support\Collection $siteMapping
     * @return void
     * @throws \Exception
     */
    private function createNewEmployee(array $apiEmployee, \Illuminate\Support\Collection $siteMapping): void
    {
        $siteCode = $apiEmployee['SiteCode'] ?? null;
        $siteId = $siteCode && $siteMapping->has($siteCode) ? $siteMapping->get($siteCode)->id : null;

        $employeeNumber = trim($apiEmployee['Pin'] ?? '');
        $email = trim($apiEmployee['EmailAddress'] ?? '');

        // Convert empty employee number to NULL
        $employeeNumber = !empty($employeeNumber) ? $employeeNumber : null;

        // Generate valid email address - use provided email if valid, otherwise create placeholder
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Keep provided email
        } else {
            $identifier = $employeeNumber ?: $apiEmployee['TimeGateId'];
            $email = "noemail_{$identifier}@placeholder.local";
        }

        // Validate employee number uniqueness
        if (!empty($employeeNumber)) {
            $existingPinEmployee = DB::table('employees')
                ->where('employee_number', $employeeNumber)
                ->first();

            if ($existingPinEmployee) {
                throw new \Exception("Employee with PIN {$employeeNumber} already exists in database (ID: {$existingPinEmployee->id})");
            }
        }

        // Validate email uniqueness for non-placeholder emails
        if (!str_contains($email, '@placeholder.local')) {
            $existingEmailEmployee = DB::table('employees')
                ->where('email', $email)
                ->first();

            if ($existingEmailEmployee) {
                throw new \Exception("Employee with email {$email} already exists in database (ID: {$existingEmailEmployee->id})");
            }
        }

        DB::table('employees')->insert([
            'timegate_id' => $apiEmployee['TimeGateId'],
            'employee_number' => $employeeNumber,
            'first_name' => trim($apiEmployee['FirstName'] ?? ''),
            'last_name' => trim($apiEmployee['LastName'] ?? ''),
            'email' => $email,
            'site_code' => $siteCode,
            'site_id' => $siteId,
            'status' => 'active',
            'number' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Update existing employee with API data, handling status changes and field updates.
     * Manages employee lifecycle including termination status and data synchronization.
     *
     * @param array $apiEmployee
     * @param object $existingEmployee
     * @param bool $isTerminated
     * @return bool
     */
    private function updateExistingEmployee(array $apiEmployee, object $existingEmployee, bool $isTerminated): bool
    {
        $updateData = [];

        // Ensure timegate_id linkage is maintained
        if ($existingEmployee->timegate_id !== $apiEmployee['TimeGateId']) {
            $updateData['timegate_id'] = $apiEmployee['TimeGateId'];
        }

        // Handle employment status based on API termination data
        if ($isTerminated && $existingEmployee->status !== 'leaver') {
            $updateData['status'] = 'leaver';
        } elseif (!$isTerminated && $existingEmployee->status === 'leaver') {
            $updateData['status'] = 'active';
        }

        // Update employee data for active employees
        if (!$isTerminated) {
            $this->processEmployeeFieldUpdates($apiEmployee, $existingEmployee, $updateData);
        }

        // Update site assignment if changed
        $newSiteCode = $apiEmployee['SiteCode'] ?? null;
        if ($existingEmployee->site_code !== $newSiteCode) {
            $updateData['site_code'] = $newSiteCode;
        }

        // Apply updates if changes detected
        if (!empty($updateData)) {
            $updateData['updated_at'] = now();

            DB::table('employees')
                ->where('employee_number', $existingEmployee->employee_number)
                ->update($updateData);

            return true;
        }

        return false;
    }

    /**
     * Process field-level updates for employee data during synchronization.
     * Handles email validation and generates placeholder emails when necessary.
     *
     * @param array $apiEmployee
     * @param object $existingEmployee
     * @param array &$updateData
     * @return void
     */
    private function processEmployeeFieldUpdates(array $apiEmployee, object $existingEmployee, array &$updateData): void
    {
        $newFirstName = trim($apiEmployee['FirstName'] ?? '');
        $newLastName = trim($apiEmployee['LastName'] ?? '');
        $newEmail = trim($apiEmployee['EmailAddress'] ?? '');

        // Validate email or assign placeholder
        if (!empty($newEmail) && filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            // Keep provided email
        } else {
            $identifier = $existingEmployee->employee_number ?: $existingEmployee->timegate_id;
            $newEmail = "noemail_{$identifier}@placeholder.local";
        }

        // Update first name if changed
        if ($existingEmployee->first_name !== $newFirstName) {
            $updateData['first_name'] = $newFirstName;
        }

        // Update last name if changed
        if ($existingEmployee->last_name !== $newLastName) {
            $updateData['last_name'] = $newLastName;
        }

        // Update email if changed (with duplicate check for non-placeholder)
        $currentEmail = preg_replace('/_temp_\d+$/', '', $existingEmployee->email ?? '');

        if ($currentEmail !== $newEmail) {
            if (!str_contains($newEmail, '@placeholder.local')) {
                $duplicateEmailCheck = DB::table('employees')
                    ->where('email', $newEmail)
                    ->where('employee_number', '!=', $existingEmployee->employee_number)
                    ->first();

                if (!$duplicateEmailCheck) {
                    $updateData['email'] = $newEmail;
                }
            } else {
                $updateData['email'] = $newEmail;
            }
        }
    }

    /**
     * Search for employee in external API by branch and employee number.
     * Provides comprehensive employee lookup across API data sources.
     *
     * @param string $branchCode
     * @param string $employeeNumber
     * @param array $credentials
     * @return array|null
     */
    private function searchEmployeeInApiByBranch(string $branchCode, string $employeeNumber, array $credentials): ?array
    {
        try {
            $response = Http::withToken($credentials['token'])
                ->timeout(30)
                ->get("{$credentials['base_url']}/list/officer/branch/{$branchCode}", [
                    'page' => 1,
                    'limit' => 1000
                ]);

            if ($response->successful()) {
                $apiData = $response->json();
                $apiEmployees = $apiData['data'] ?? [];

                foreach ($apiEmployees as $apiEmployee) {
                    $apiPin = trim($apiEmployee['Pin'] ?? '');
                    if ($apiPin === $employeeNumber) {
                        return $apiEmployee;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("API employee search failed: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Search for employee in local database by branch and employee number.
     * Performs comprehensive database lookup with branch relationship joining.
     *
     * @param string $branchCode
     * @param string $employeeNumber
     * @return object|null
     */
    private function searchEmployeeInDatabaseByBranch(string $branchCode, string $employeeNumber): ?object
    {
        return DB::table('employees as e')
            ->join('sites as s', 'e.site_code', '=', 's.site_code')
            ->join('branches as b', 's.branch_code', '=', 'b.branch_code')
            ->select(
                'e.id', 'e.employee_number', 'e.timegate_id', 'e.first_name',
                'e.last_name', 'e.email', 'e.status', 'e.site_code',
                's.name as site_name', 'b.branch_code', 'b.branch_name'
            )
            ->where('b.branch_code', $branchCode)
            ->where(function($query) use ($employeeNumber) {
                $query->where('e.employee_number', $employeeNumber)
                    ->orWhere('e.employee_number', 'REGEXP', "^{$employeeNumber}(_temp_[0-9]+)?$");
            })
            ->first();
    }

    /**
     * Format API employee data for consistent response structure.
     * Standardizes API employee information for client consumption.
     *
     * @param array $apiEmployee
     * @return array
     */
    private function formatApiEmployeeData(array $apiEmployee): array
    {
        return [
            'employee_number' => $apiEmployee['Pin'] ?? '',
            'timegate_id' => $apiEmployee['TimeGateId'] ?? '',
            'full_name' => trim(($apiEmployee['FirstName'] ?? '') . ' ' . ($apiEmployee['LastName'] ?? '')),
            'first_name' => $apiEmployee['FirstName'] ?? '',
            'last_name' => $apiEmployee['LastName'] ?? '',
            'email' => $apiEmployee['EmailAddress'] ?? '',
            'site_code' => $apiEmployee['SiteCode'] ?? '',
            'terminated_date' => $apiEmployee['TerminatedDate'] ?? null,
            'is_active' => is_null($apiEmployee['TerminatedDate'] ?? null)
        ];
    }

    /**
     * Format database employee data for consistent response structure.
     * Standardizes database employee information for client consumption.
     *
     * @param object $dbEmployee
     * @return array
     */
    private function formatDatabaseEmployeeData(object $dbEmployee): array
    {
        $cleanEmployeeNumber = preg_replace('/_temp_\d+$/', '', $dbEmployee->employee_number);

        return [
            'id' => $dbEmployee->id,
            'employee_number' => $cleanEmployeeNumber,
            'raw_employee_number' => $dbEmployee->employee_number,
            'timegate_id' => $dbEmployee->timegate_id,
            'full_name' => $dbEmployee->first_name . ' ' . $dbEmployee->last_name,
            'first_name' => $dbEmployee->first_name,
            'last_name' => $dbEmployee->last_name,
            'email' => $dbEmployee->email,
            'status' => $dbEmployee->status,
            'site' => [
                'code' => $dbEmployee->site_code,
                'name' => $dbEmployee->site_name
            ],
            'branch' => [
                'code' => $dbEmployee->branch_code,
                'name' => $dbEmployee->branch_name
            ]
        ];
    }

    /**
     * Handle employee not found scenario with debugging information.
     * Provides comprehensive debugging data when employee searches fail.
     *
     * @param string $branchCode
     * @param string $employeeNumber
     * @param array $searchResults
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleEmployeeNotFound(string $branchCode, string $employeeNumber, array $searchResults): \Illuminate\Http\JsonResponse
    {
        $sampleEmployees = DB::table('employees as e')
            ->join('sites as s', 'e.site_code', '=', 's.site_code')
            ->join('branches as b', 's.branch_code', '=', 'b.branch_code')
            ->select('e.employee_number', 'e.first_name', 'e.last_name', 's.name as site_name')
            ->where('b.branch_code', $branchCode)
            ->orderBy('e.employee_number')
            ->limit(10)
            ->get()
            ->map(function($emp) {
                $emp->display_employee_number = preg_replace('/_temp_\d+$/', '', $emp->employee_number);
                return $emp;
            });

        return response()->json([
            'success' => false,
            'message' => "Employee number {$employeeNumber} not found in branch {$branchCode}",
            'employee' => null,
            'sources' => $searchResults,
            'debug_info' => [
                'branch_code' => $branchCode,
                'total_employees_in_db' => $sampleEmployees->count(),
                'sample_db_employees' => $sampleEmployees
            ]
        ], 404);
    }

    /**
     * Insert new branch record into database.
     * Creates new branch entry with complete API data mapping.
     *
     * @param array $apiBranch
     * @param string|null $timeGateId
     * @return void
     */
    private function insertNewBranch(array $apiBranch, ?string $timeGateId): void
    {
        DB::table('branches')->insert([
            'branch_code' => $apiBranch['Code'],
            'branch_name' => $apiBranch['Name'],
            'TimeGateId' => $timeGateId,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Check if branch requires updating based on API data.
     * Compares current database state with API data to determine update necessity.
     *
     * @param object $existingBranch
     * @param array $apiBranch
     * @param string|null $timeGateId
     * @return bool
     */
    private function branchRequiresUpdate(object $existingBranch, array $apiBranch, ?string $timeGateId): bool
    {
        return $existingBranch->branch_name !== $apiBranch['Name'] ||
            $existingBranch->TimeGateId !== $timeGateId ||
            $existingBranch->active != 1;
    }

    /**
     * Update existing branch record with API data.
     * Synchronizes branch information with latest API data.
     *
     * @param string $branchCode
     * @param array $apiBranch
     * @param string|null $timeGateId
     * @return void
     */
    private function updateExistingBranch(string $branchCode, array $apiBranch, ?string $timeGateId): void
    {
        DB::table('branches')
            ->where('branch_code', $branchCode)
            ->update([
                'branch_name' => $apiBranch['Name'],
                'TimeGateId' => $timeGateId,
                'active' => 1,
                'updated_at' => now(),
            ]);
    }

    /**
     * Insert new site record into database.
     * Creates new site entry with complete API data and branch relationships.
     *
     * @param array $apiSite
     * @param string|null $timeGateId
     * @param string|null $branchCode
     * @param int|null $branchId
     * @return void
     */
    private function insertNewSite(array $apiSite, ?string $timeGateId, ?string $branchCode, ?int $branchId): void
    {
        DB::table('sites')->insert([
            'site_code' => $apiSite['Code'],
            'name' => $apiSite['Name'],
            'branch_code' => $branchCode,
            'branch_id' => $branchId,
            'TimeGateId' => $timeGateId,
            'customer_id' => null,
            'address' => null,
            'city' => null,
            'county' => null,
            'postal_code' => null,
            'description' => null,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Check if site requires updating based on API data.
     * Compares current database state with API data to determine update necessity.
     *
     * @param object $existingSite
     * @param array $apiSite
     * @param string|null $timeGateId
     * @param string|null $branchCode
     * @param int|null $branchId
     * @return bool
     */
    private function siteRequiresUpdate(object $existingSite, array $apiSite, ?string $timeGateId, ?string $branchCode, ?int $branchId): bool
    {
        return $existingSite->name !== $apiSite['Name'] ||
            $existingSite->branch_code !== $branchCode ||
            $existingSite->branch_id !== $branchId ||
            $existingSite->TimeGateId !== $timeGateId ||
            (int)$existingSite->active !== 1;
    }

    /**
     * Update existing site record with API data.
     * Synchronizes site information with latest API data and branch relationships.
     *
     * @param string $siteCode
     * @param array $apiSite
     * @param string|null $timeGateId
     * @param string|null $branchCode
     * @param int|null $branchId
     * @return void
     */
    private function updateExistingSite(string $siteCode, array $apiSite, ?string $timeGateId, ?string $branchCode, ?int $branchId): void
    {
        DB::table('sites')
            ->where('site_code', $siteCode)
            ->update([
                'name' => $apiSite['Name'],
                'branch_code' => $branchCode,
                'branch_id' => $branchId,
                'TimeGateId' => $timeGateId,
                'active' => 1,
                'updated_at' => now(),
            ]);
    }

    /**
     * Handle synchronization exceptions with comprehensive error logging and response.
     * Provides standardized error handling and detailed logging for all sync operations.
     *
     * @param string $operation
     * @param \Exception $exception
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleSyncException(string $operation, \Exception $exception, array $errors = []): \Illuminate\Http\JsonResponse
    {
        $errorMessage = "{$operation} failed: " . $exception->getMessage();

        // Log comprehensive error details
        Log::error("Synchronization Exception Details", [
            'operation' => $operation,
            'error_message' => $exception->getMessage(),
            'error_type' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'additional_errors' => $errors,
            'memory_usage' => memory_get_peak_usage(true),
            'execution_time' => microtime(true) - ($_SERVER["REQUEST_TIME_FLOAT"] ?? microtime(true))
        ]);

        return response()->json([
            'success' => false,
            'error' => $errorMessage,
            'errors' => $errors,
            'debug_info' => [
                'error_type' => get_class($exception),
                'error_file' => basename($exception->getFile()),
                'error_line' => $exception->getLine()
            ],
            'timestamp' => now()
        ], 500);
    }

    /**
     * Generate standardized error response with comprehensive error information.
     * Creates consistent error response format across all controller methods.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    private function errorResponse(string $message, int $statusCode = 400): \Illuminate\Http\JsonResponse
    {
        Log::error("API Error Response", [
            'message' => $message,
            'status_code' => $statusCode,
            'request_url' => request()->url(),
            'request_method' => request()->method(),
            'user_agent' => request()->userAgent(),
            'ip_address' => request()->ip()
        ]);

        return response()->json([
            'success' => false,
            'error' => $message,
            'timestamp' => now()
        ], $statusCode);
    }
}