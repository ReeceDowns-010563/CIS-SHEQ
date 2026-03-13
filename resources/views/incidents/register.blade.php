{{--
    Incident Register View - Master List of All Incidents

    This view provides a comprehensive table displaying all incidents in the system with
    advanced filtering, searching, and management capabilities. It serves as the central
    hub for incident oversight and administration.

    Key Features:
    - Real-time status updates with mandatory corrective actions for closures
    - Advanced filtering by type, status, branch, date range, and archive status
    - User assignment management with searchable dropdown interface
    - Integrated comments system for collaborative investigation notes
    - Archive/unarchive functionality for incident lifecycle management
    - Responsive design optimized for desktop and mobile devices
    - Professional styling with consistent color coding and animations

    Security Features:
    - CSRF protection on all AJAX requests
    - Server-side validation for all status changes
    - User permission checking for administrative actions

    Performance Optimizations:
    - Paginated results to handle large datasets
    - Efficient database queries with proper indexing
    - Minimal DOM manipulation for better responsiveness
--}}

<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Accident Register</h2>
    </x-slot>

    @php
        /*
         * Data Table Column Configuration
         *
         * This configuration defines the structure, behavior, and rendering logic
         * for each column in the incident register table. Each column specifies:
         * - Display properties (width, label, styling)
         * - Interactive capabilities (sortable, clickable)
         * - Custom rendering logic for complex data presentation
         * - Event handling exclusions for nested interactive elements
         */
        $columns = [
            // Date of Occurrence Column
            [
                'key' => 'date_of_occurrence',           // Database field for sorting
                'label' => 'Date',                       // Column header text
                'width' => '12%',                        // Responsive column width
                'sortable' => true,                      // Enable click-to-sort functionality
                'clickable' => true,                     // Allow row clicks for navigation
                'render' => function($incident) {
                    // Format date consistently across the application
                    return \Illuminate\Support\Carbon::parse($incident->date_of_occurrence)->format('Y-m-d');
                }
            ],

            // Brief Description Column - Primary incident identifier
            [
                'key' => 'brief_description',
                'label' => 'Description',
                'width' => '22%',                        // Wider column for readability
                'sortable' => true,
                'render' => function($incident) {
                    // Truncate long descriptions to maintain table layout
                    return Str::limit($incident->brief_description, 60);
                }
            ],

            // Incident Type Classification
            [
                'label' => 'Type',
                'width' => '10%',
                'render' => function($incident) {
                    // Safe navigation with null coalescing for missing relationships
                    return $incident->incidentType->name ?? '-';
                }
            ],

            // Branch Location Information
            [
                'label' => 'Branch',
                'width' => '10%',
                'render' => function($incident) {
                    // Use Laravel's optional() helper for safe property access
                    return optional($incident->branch)->branch_name ?? '-';
                }
            ],

            // Site Location Information
            [
                'label' => 'Site',
                'width' => '8%',
                'render' => function($incident) {
                    return $incident->site->name ?? '-';
                }
            ],

            // Status Management Column - Interactive Dropdown with Corrective Actions Integration
            [
                'label' => 'Status',
                'width' => '9%',
                'no_click' => true,                      // Prevent row click events on this column
                'class' => 'whitespace-nowrap',          // Prevent text wrapping
                'render' => function($incident) {
                    // Generate interactive status dropdown with current status styling

                    $user_role = auth()->user()->role_name;

                    if($user_role != 'admin' && $user_role != 'SHEQ'){
                        return '<span class="status-text">' . ucfirst($incident->status) . '</span>';
                    }else{
                        return '
                        <div class="clean-select status-' . $incident->status . '"
                            data-incident-id="' . $incident->id . '"
                            data-current-status="' . $incident->status . '">
                            <div class="clean-select-button">
                                <span class="status-text">' . ucfirst($incident->status) . '</span>
                                <svg class="clean-select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="clean-select-dropdown">
                                ' . collect(['open','investigating','closed'])->map(function($status) use ($incident) {
                                    $selected = $incident->status === $status ? 'selected' : '';
                                    return '<div class="clean-select-option ' . $selected . '" data-value="' . $status . '">' . ucfirst($status) . '</div>';
                                })->implode('') . '
                            </div>
                        </div>';
                    }



                }
            ],

            // User Assignment Management - Searchable Dropdown with User Avatars
            [

                'label' => 'Assigned To',
                'width' => '14%',
                'no_click' => true,
                'class' => 'whitespace-nowrap',
                'render' => function($incident) use ($sheqUsers) {
                    $coordinator = $incident->coordinator;
                    // Generate user assignment dropdown with avatar and search functionality

                    $user_role = auth()->user()->role_name;
                    if($user_role === 'basic'){
                        return $coordinator
                            ? '<span class="user-select-text">' . Str::limit($coordinator->name, 15) . '</span>'
                            : '<div class="user-avatar unassigned">?</div><span class="user-select-text">Unassigned</span>';
                    }else{
                        return '
                        <div class="user-select"
                            data-incident-id="' . $incident->id . '"
                            data-current-user="' . ($incident->coordinator_id ?? '') . '">
                            <div class="user-select-button">
                                ' . ($coordinator
                                    ? '<div class="user-avatar">' . strtoupper(substr($coordinator->name, 0, 1)) . '</div><span class="user-select-text">' . Str::limit($coordinator->name, 15) . '</span>'
                                    : '<div class="user-avatar unassigned">?</div><span class="user-select-text">Unassigned</span>'
                                ) . '
                                <svg class="user-select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="user-select-dropdown">
                                <input type="text" class="user-search-input" placeholder="Search users...">
                                <div class="user-options">
                                    <div class="user-option ' . (!$incident->coordinator_id ? 'selected' : '') . '" data-user-id="">
                                        <div class="user-avatar unassigned">?</div>
                                        <span class="user-option-text">Unassigned</span>
                                    </div>
                                    ' . collect($sheqUsers ?? [])->map(function($user) use ($incident) {
                                        $selected = $incident->coordinator_id == $user->id ? 'selected' : '';
                                        return '<div class="user-option ' . $selected . '" data-user-id="' . $user->id . '">
                                            <div class="user-avatar">' . strtoupper(substr($user->name, 0, 1)) . '</div>
                                            <span class="user-option-text">' . $user->name . '</span>
                                        </div>';
                                    })->implode('') . '
                                </div>
                            </div>
                        </div>';
                    }




                }
            ],

            // Comments Management - Modal Trigger with Count Badge
            [
                'label' => 'Comments',
                'width' => '10%',
                'no_click' => true,
                'class' => 'whitespace-nowrap',
                'render' => function($incident) {
                    $count = $incident->comments_count ?? 0;
                    $countDisplay = $count > 0 ? $count : '';
                    // Generate comments button with conditional count badge
                    return '
                    <div class="comments-container">
                        <button type="button" class="comments-btn" onclick="openCommentsModal(' . $incident->id . ')">
                            <svg class="comments-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>Comments</span>
                            <span class="comments-count" data-count="' . $count . '">' . $countDisplay . '</span>
                        </button>
                    </div>';
                }
            ],

            // Action Buttons - Edit/View, Archive/Unarchive, View Report Operations
            [
                'label' => 'Actions',
                'width' => '15%',
                'no_click' => true,
                'class' => 'whitespace-nowrap text-sm font-medium text-right',
                'render' => function($incident) {
                    $actions = '<div class="action-links">';

                    $user_role = auth()->user()->role_name;

                    if($user_role === 'basic'){
                        // Basic users - no archive/unarchive buttons
                        if (!$incident->archived) {
                            // Show "View" button for basic users instead of "Edit"
                            $actions .= '<a href="' . route('incidents.show', $incident) . '" class="view-link">View</a>';

                            // Show "View Report" button for completed/closed incidents
                            if (in_array($incident->status, ['completed', 'closed'])) {
                                $actions .= '<a href="' . route('incidents.reports.show', $incident) . '" class="view-report-link" target="_blank">View Report</a>';
                            }
                        } else {
                            // Archived incident actions
                            $actions .= '<a href="' . route('incidents.show', $incident) . '" class="view-link">View</a>';

                            if (in_array($incident->status, ['completed', 'closed'])) {
                                $actions .= '<a href="' . route('incidents.reports.show', $incident) . '" class="view-report-link" target="_blank">View Report</a>';
                            }
                        }
                    } else {
                        // SHEQ and Admin users - with archive/unarchive functionality
                        if (!$incident->archived) {
                            // Active incident actions for non-basic users
                            $actions .= '<a href="' . route('incidents.edit', $incident) . '" class="edit-link">Edit</a>';

                            // Show "View Report" button for completed/closed incidents
                            if (in_array($incident->status, ['completed', 'closed'])) {
                                $actions .= '<a href="' . route('incidents.reports.show', $incident) . '" class="view-report-link" target="_blank">View Report</a>';
                            }

                            $actions .= '<button onclick="archiveIncident(' . $incident->id . ')" class="archive-btn">Archive</button>';
                        } else {
                            // Archived incident actions for non-basic users
                            if (in_array($incident->status, ['completed', 'closed'])) {
                                $actions .= '<a href="' . route('incidents.reports.show', $incident) . '" class="view-report-link" target="_blank">View Report</a>';
                            }
                            $actions .= '<button onclick="unarchiveIncident(' . $incident->id . ')" class="unarchive-btn">Unarchive</button>';
                        }
                    }

                    $actions .= '</div>';
                    return $actions;
                }
            ]
        ];

        /*
         * Filter Configuration Array
         *
         * Defines the available filters for data refinement. Each filter specifies:
         * - Filter type (select, date, checkbox)
         * - Available options and their data sources
         * - Default display values and labels
         * - Integration with query parameters for URL persistence
         */

        // Get current user role to conditionally show archive filter
        $currentUserRole = auth()->user()->role_name;

        $filters = [
            // Incident Type Filter
            [
                'name' => 'incident_type',
                'label' => 'Incident Type',
                'type' => 'select',
                'all_option' => 'All Types',
                'options' => $incidentTypes->pluck('name', 'id')->toArray()
            ],

            // Status Filter
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'all_option' => 'All',
                'options' => collect($statuses)->mapWithKeys(function($status) {
                    return [$status => ucfirst($status)];
                })->toArray()
            ],

            // Branch Filter
            [
                'name' => 'branch',
                'label' => 'Branch',
                'type' => 'select',
                'all_option' => 'All Branches',
                'options' => $branches->pluck('branch_name', 'id')->toArray()
            ],

            // Date Range Filters
            [
                'name' => 'date_from',
                'label' => 'From Date',
                'type' => 'date'
            ],
            [
                'name' => 'date_to',
                'label' => 'To Date',
                'type' => 'date'
            ]
        ];

        // Only add archive filter for SHEQ and Admin users
        if ($currentUserRole === 'admin' || $currentUserRole === 'SHEQ') {
            $filters[] = [
                'name' => 'archived',
                'label' => 'Show archived incidents',
                'type' => 'checkbox',
                'value' => '1'
            ];
        }
    @endphp

    {{--
        Data Table Component Integration

        Renders the main incident table using the reusable data-table component.
        Passes all configuration arrays and enables advanced features like
        sorting, filtering, searching, and pagination.
    --}}
    <x-data-table
        id="register"
        :data="$incidents"
        :columns="$columns"
        :filters="$filters"
        :search-fields="['brief_description', 'location']"
        search-placeholder="Search by description or location..."
        route-name="incidents.register"
        :show-archived="true"
        empty-message="No incidents found"
    />

    {{--
        Modal Components Integration

        Includes the necessary modal components for enhanced functionality:
        1. Comments Modal - For collaborative investigation discussions
        2. Corrective Actions Modal - For mandatory closure documentation
    --}}

    {{-- Standard Comments Modal for Incident Discussion --}}
    @include('incidents.components.comments-modal', [
        'modalId' => 'commentsModal',
        'itemType' => 'incident',
        'title' => 'Comments'
    ])

    {{-- Corrective Actions Modal for Investigation Closure --}}
    @include('incidents.components.corrective-actions-modal', [
        'modalId' => 'correctiveActionsModal',
        'title' => 'Corrective Actions Required - Incident Investigation',
        'itemType' => 'incident'
    ])

    {{--
        Register-Specific Styling

        Custom CSS for visual enhancements specific to the incident register.
        Includes status color coding, interactive element styling, and responsive
        design optimizations. Scoped to prevent conflicts with other views.
    --}}
    <style>
        /*
         * Status Color Coding System
         * Visual indicators providing immediate status recognition through color psychology:
         * - Pending: Orange (attention needed)
         * - Investigating: Blue (in progress)
         * - Completed: Green (successful resolution)
         * - Closed: Gray (finalized/archived)
         */
        .status-pending .clean-select-button {
            color: #b45309;
            background: #fffbeb;
            border-color: #fde68a;
        }

        .status-investigating .clean-select-button {
            color: #1d4ed8;
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .status-completed .clean-select-button {
            color: #065f46;
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .status-closed .clean-select-button {
            color: #374151;
            background: #f3f4f6;
            border-color: #e5e7eb;
        }

        /*
         * User Assignment Dropdown Styling
         *
         * Professional interface for user assignment with:
         * - Gradient backgrounds for visual appeal
         * - Smooth animations and transitions
         * - Avatar system for quick user identification
         * - Search functionality for large user lists
         * - Accessibility-compliant focus states
         */
        .user-select {
            position: relative;
            display: inline-block;
            min-width: 180px;
        }

        .user-select-button {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 8px 12px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 36px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1;
        }

        /* Hover Effects for Enhanced User Experience */
        .user-select-button:hover {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Active/Open State Styling */
        .user-select.open .user-select-button {
            background: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            z-index: 50001;
        }

        /* User Avatar Component Styling */
        .user-avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #131F34 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: 600;
            flex-shrink: 0;
        }

        /* Unassigned State Avatar */
        .user-avatar.unassigned {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
        }

        /* User Name Text Styling */
        .user-select-text {
            flex: 1;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Dropdown Arrow with Rotation Animation */
        .user-select-arrow {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
            color: #64748b;
            flex-shrink: 0;
        }

        .user-select.open .user-select-arrow {
            transform: rotate(180deg);
            color: #6366f1;
        }

        /* Dropdown Menu Container */
        .user-select-dropdown {
            position: fixed;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15), 0 10px 15px rgba(0, 0, 0, 0.1);
            z-index: 50000;
            max-height: 280px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-4px);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 180px;
        }

        /* Dropdown Open Animation */
        .user-select.open .user-select-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* User Search Input Field */
        .user-search-input {
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            outline: none;
            background: #ffffff;
            color: #374151;
            border-radius: 11px 11px 0 0;
        }

        .user-search-input::placeholder {
            color: #9ca3af;
        }

        .user-search-input:focus {
            background: #ffffff;
            border-bottom-color: #6366f1;
        }

        /* User Options List Container */
        .user-options {
            max-height: 200px;
            overflow-y: auto;
        }

        /* Individual User Option Styling */
        .user-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .user-option:hover {
            background: #f1f5f9;
        }

        .user-option.selected {
            background: #eff6ff;
            font-weight: 600;
        }

        .user-option-text {
            font-size: 13px;
            color: #374151;
        }

        .user-option.selected .user-option-text {
            color: #2563eb;
        }

        /*
         * Action Links Styling - Redesigned for cleaner appearance
         *
         * Modern card-based button design with:
         * - Clean, minimal styling
         * - Consistent sizing and spacing
         * - Better visual hierarchy
         * - Smooth animations
         * - Professional color scheme
         */
        .action-links {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
            align-items: center;
        }

        .action-links a, .action-links button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            height: 32px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            background: transparent;
            white-space: nowrap;
            text-align: center;
            line-height: 1;
        }

        /* Edit Link Styling - Blue theme */
        .edit-link {
            color: #3b82f6;
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        .edit-link:hover {
            color: #1d4ed8;
            background: #00334C;
            border-color: #93c5fd;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);
        }

        /* View Link Styling - Green theme */
        .view-link {
            color: #10b981;
            border-color: #a7f3d0;
            background: #ecfdf5;
        }

        .view-link:hover {
            color: #059669;
            background: #d1fae5;
            border-color: #6ee7b7;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.15);
        }

        /* View Report Link Styling - Purple theme */
        .view-report-link {
            color: #131F34;
            border-color: #c4b5fd;
            background: #f5f3ff;
        }

        .view-report-link:hover {
            color: #7c3aed;
            background: #ede9fe;
            border-color: #a78bfa;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(139, 92, 246, 0.15);
        }

        /* Archive Button Styling - Orange theme */
        .archive-btn {
            color: #f59e0b;
            border-color: #fed7aa;
            background: #fffbeb;
        }

        .archive-btn:hover {
            color: #d97706;
            background: #fef3c7;
            border-color: #fcd34d;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.15);
        }

        /* Unarchive Button Styling - Green theme */
        .unarchive-btn {
            color: #10b981;
            border-color: #a7f3d0;
            background: #ecfdf5;
        }

        .unarchive-btn:hover {
            color: #059669;
            background: #d1fae5;
            border-color: #6ee7b7;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.15);
        }

        /* Active/Focus states */
        .action-links a:active, .action-links button:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .action-links a:focus, .action-links button:focus {
            outline: 2px solid;
            outline-offset: 2px;
        }

        .edit-link:focus {
            outline-color: #3b82f6;
        }

        .view-link:focus {
            outline-color: #10b981;
        }

        .view-report-link:focus {
            outline-color: #131F34;
        }

        .archive-btn:focus {
            outline-color: #f59e0b;
        }

        .unarchive-btn:focus {
            outline-color: #10b981;
        }

        /*
         * Comments Button Styling
         *
         * Consistent with investigation view design for unified user experience.
         * Features gradient backgrounds, hover animations, and count badges.
         */
        .comments-container {
            display: inline-block;
        }

        .comments-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            min-width: 120px;
            justify-content: center;
        }

        /* Comments Button Hover Effects */
        .comments-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        /* Comments Icon Styling */
        .comments-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Comments Count Badge */
        .comments-count {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        /* Hide Empty Count Badges */
        .comments-count:empty,
        .comments-count[data-count="0"] {
            display: none;
        }

        /* Improved spacing for multiple buttons */
        .action-links:has(.view-report-link) {
            min-width: 200px;
        }

        /* Animation improvements */
        @keyframes buttonPress {
            0% { transform: translateY(-1px); }
            100% { transform: translateY(0); }
        }

        .action-links a:active, .action-links button:active {
            animation: buttonPress 0.1s ease-out;
        }

        /* Tooltip-style enhancement */
        .action-links a, .action-links button {
            position: relative;
        }

        .action-links a::after, .action-links button::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: currentColor;
            transition: width 0.2s ease-in-out;
            border-radius: 1px;
        }

        .action-links a:hover::after, .action-links button:hover::after {
            width: 80%;
        }

        .view-link:hover::after {
            width: 80%;
        }

        /*
         * Filter Panel Scroll Fix
         * Ensures filters remain accessible on smaller screens with proper scrolling
         */
        :global(.filter-panel) {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Ensure filter buttons are always visible */
        :global(.filter-actions) {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 16px;
            border-top: 1px solid #e5e7eb;
            margin-top: auto;
            z-index: 10;
        }

        /*
         * Mobile Responsive Design
         *
         * Optimized layout adjustments for smaller screens:
         * - Reduced padding and font sizes
         * - Stacked action button layout
         * - Adjusted minimum widths for touch targets
         */
        @media (max-width: 768px) {
            .user-select {
                min-width: 100px;
            }

            .user-select-button {
                padding: 6px 8px;
                font-size: 12px;
                min-height: 32px;
            }

            .comments-btn {
                padding: 6px 12px;
                font-size: 13px;
                min-width: 110px;
            }

            .comments-icon {
                width: 16px;
                height: 16px;
            }

            .action-links {
                flex-direction: column;
                gap: 4px;
                align-items: stretch;
            }

            .action-links a, .action-links button {
                min-width: unset;
                width: 100%;
                height: 28px;
                font-size: 11px;
                padding: 4px 8px;
            }

            :global(.filter-panel) {
                max-height: calc(100vh - 150px);
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .edit-link {
                background: rgba(59, 130, 246, 0.1);
            }

            .view-link {
                background: rgba(16, 185, 129, 0.1);
            }

            .view-report-link {
                background: rgba(139, 92, 246, 0.1);
            }

            .archive-btn {
                background: rgba(245, 158, 11, 0.1);
            }

            .unarchive-btn {
                background: rgba(16, 185, 129, 0.1);
            }

            :global(.filter-actions) {
                background: #1f2937;
                border-top-color: #374151;
            }
        }
    </style>

    {{--
        JavaScript Functionality

        Client-side logic for interactive features including:
        - Row navigation and click handling
        - AJAX-based archive/unarchive operations
        - Status update management with corrective actions integration
        - User assignment dropdown functionality
        - Event delegation and cleanup
    --}}
    <script>
        /*
         * Navigation Functions
         * Handle user interaction with table rows and navigation
         */

        /**
         * Custom Row Click Handler
         * Navigates to the appropriate page based on user role.
         * Basic users go to view page, others go to edit page.
         *
         * @param {number} incidentId - The ID of the incident
         */
        function customRowClickHandler(incidentId) {
            // Get user role from PHP
            const userRole = '{{ auth()->user()->role_name }}';

            if (userRole === 'basic') {
                window.location.href = `/incidents/${incidentId}`;
            } else {
                window.location.href = `/incidents/${incidentId}/edit`;
            }
        }

        /*
         * Archive Management Functions
         * Handle incident lifecycle management with proper user confirmation
         * and error handling. Uses AJAX for seamless user experience.
         */

        /**
         * Archive Incident
         * Prompts user for confirmation and archives the specified incident.
         * Provides user feedback and handles potential errors gracefully.
         *
         * @param {number} incidentId - The ID of the incident to archive
         */
        async function archiveIncident(incidentId) {
            // User confirmation to prevent accidental actions
            if (!confirm('Are you sure you want to archive this incident?')) return;

            try {
                const response = await fetch(`/incidents/${incidentId}/archive`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    // Reload page to reflect changes (ensures data consistency)
                    location.reload();
                } else {
                    alert('Error archiving incident: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Archive operation failed:', error);
                alert('Error archiving incident');
            }
        }

        /**
         * Unarchive Incident
         * Prompts user for confirmation and restores the specified incident from archive.
         *
         * @param {number} incidentId - The ID of the incident to restore
         */
        async function unarchiveIncident(incidentId) {
            if (!confirm('Are you sure you want to restore this incident from archive?')) return;

            try {
                const response = await fetch(`/incidents/${incidentId}/unarchive`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error restoring incident: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Unarchive operation failed:', error);
                alert('Error restoring incident');
            }
        }

        /*
         * Status Management Functions
         * Handle incident status updates with mandatory corrective actions
         * integration for completed and closed statuses.
         */

        /**
         * Update Incident Status
         * Routes status changes through appropriate workflows:
         * - Completed/Closed: Requires corrective actions via modal
         * - Other statuses: Direct AJAX update
         *
         * @param {number} incidentId - The ID of the incident to update
         * @param {string} status - The new status to apply
         */
        function updateIncidentStatus(incidentId, status) {
            // Check if status requires mandatory corrective actions
            if (status === 'completed' || status === 'closed') {
                // Open corrective actions modal for required documentation
                openCorrectiveActionsModal(incidentId, status);
                return;
            }

            // For pending/investigating statuses, update directly via AJAX
            fetch(`/incidents/${incidentId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI to reflect new status without page reload
                        const select = document.querySelector(`[data-incident-id="${incidentId}"].clean-select`);
                        const statusText = select.querySelector('.status-text');
                        statusText.textContent = status.charAt(0).toUpperCase() + status.slice(1);

                        // Update CSS classes for visual status indication
                        select.className = `clean-select status-${status}`;

                        // Update selected option in dropdown
                        select.querySelectorAll('.clean-select-option').forEach(opt => {
                            opt.classList.toggle('selected', opt.dataset.value === status);
                        });
                    } else {
                        alert('Error updating status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Status update failed:', error);
                    alert('Error updating status');
                });
        }

        /**
         * Update Incident Assignment
         * Assigns or unassigns users to incidents with real-time UI updates.
         * Handles user avatar and name display changes dynamically.
         *
         * @param {number} incidentId - The ID of the incident to update
         * @param {number|null} userId - The ID of the user to assign (null for unassign)
         */
        function updateIncidentAssignment(incidentId, userId) {
            fetch(`/incidents/${incidentId}/assign`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ coordinator_id: userId || null })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI components to reflect new assignment
                        const select = document.querySelector(`[data-incident-id="${incidentId}"].user-select`);
                        const button = select.querySelector('.user-select-button');
                        const avatar = button.querySelector('.user-avatar');
                        const text = button.querySelector('.user-select-text');

                        if (data.user) {
                            // User assigned - update avatar and text
                            avatar.textContent = data.user.name.charAt(0).toUpperCase();
                            avatar.className = 'user-avatar';
                            text.textContent = data.user.name;
                        } else {
                            // User unassigned - show unassigned state
                            avatar.textContent = '?';
                            avatar.className = 'user-avatar unassigned';
                            text.textContent = 'Unassigned';
                        }

                        // Update selected option in dropdown
                        select.querySelectorAll('.user-option').forEach(opt => {
                            opt.classList.toggle('selected', opt.dataset.userId === (userId ? userId.toString() : ''));
                        });
                        select.dataset.currentUser = userId || '';
                    } else {
                        alert('Error updating assignment: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Assignment update failed:', error);
                    alert('Error updating assignment');
                });
        }

        /*
         * Data Table Integration Functions
         * Bridge functions to connect with the reusable data-table component
         */

        /**
         * Update Item Status - Data Table Bridge
         * Provides compatibility with the generic data-table component
         *
         * @param {number} incidentId - The incident ID
         * @param {string} status - The new status
         * @param {HTMLElement} selectElement - The dropdown element (unused)
         */
        function updateItemStatus(incidentId, status, selectElement) {
            updateIncidentStatus(incidentId, status);
        }

        /*
         * User Interface Event Handlers
         * Initialize interactive elements and manage user interactions
         */
        document.addEventListener('DOMContentLoaded', function() {
            /*
             * User Assignment Dropdown Initialization
             * Set up event listeners and functionality for all user selection dropdowns
             */
            document.querySelectorAll('.user-select').forEach(select => {
                const button = select.querySelector('.user-select-button');
                const dropdown = select.querySelector('.user-select-dropdown');
                const searchInput = dropdown?.querySelector('.user-search-input');
                const options = dropdown?.querySelectorAll('.user-option');

                // Skip initialization if elements are missing
                if (!button || !dropdown) return;

                /**
                 * Dropdown Toggle Handler
                 * Manages dropdown open/close states and positioning
                 */
                button.addEventListener('click', (e) => {
                    e.stopPropagation();

                    // Close other open dropdowns to maintain single-dropdown state
                    document.querySelectorAll('.user-select.open, .clean-select.open').forEach(other => {
                        if (other !== select) other.classList.remove('open');
                    });

                    // Position and show dropdown if not already open
                    if (!select.classList.contains('open')) {
                        const rect = button.getBoundingClientRect();
                        dropdown.style.left = rect.left + 'px';
                        dropdown.style.width = rect.width + 'px';
                        dropdown.style.top = (rect.bottom + 4) + 'px';

                        // Focus search input for immediate typing
                        setTimeout(() => searchInput?.focus(), 100);
                    }
                    select.classList.toggle('open');
                });

                /**
                 * User Search Functionality
                 * Implements real-time filtering of user options based on search input
                 */
                searchInput?.addEventListener('input', (e) => {
                    const searchTerm = e.target.value.toLowerCase();
                    options.forEach(option => {
                        const text = option.querySelector('.user-option-text').textContent.toLowerCase();
                        option.style.display = text.includes(searchTerm) ? 'flex' : 'none';
                    });
                });

                /**
                 * User Selection Handlers
                 * Process user selection and update assignments
                 */
                options?.forEach(option => {
                    option.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const userId = option.dataset.userId;
                        const incidentId = select.dataset.incidentId;

                        // Update assignment and close dropdown
                        updateIncidentAssignment(incidentId, userId || null);
                        select.classList.remove('open');

                        // Reset search filter for next use
                        if (searchInput) {
                            searchInput.value = '';
                            options.forEach(opt => opt.style.display = 'flex');
                        }
                    });
                });
            });

            /**
             * Global Click Handler
             * Closes open dropdowns when clicking outside of them
             */
            document.addEventListener('click', () => {
                document.querySelectorAll('.user-select.open').forEach(select => {
                    select.classList.remove('open');
                });
            });
        });
    </script>
</x-app-layout>
