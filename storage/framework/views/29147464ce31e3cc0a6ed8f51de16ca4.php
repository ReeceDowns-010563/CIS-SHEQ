<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">My Investigations</h2>
     <?php $__env->endSlot(); ?>

    <?php
        /**
         * Data table configuration for My Investigations interface
         *
         * This section defines the column structure and filter options for displaying
         * incident reports assigned to the current user. The configuration includes:
         * - Column definitions with rendering logic
         * - Filter options for searching and sorting
         * - Interactive elements for status updates and actions
         */

        /**
         * Column definitions for the investigations data table
         * Each column contains:
         * - key: Database field name for sorting
         * - label: Display name in table header
         * - width: Column width as percentage
         * - sortable: Whether column supports sorting
         * - clickable: Whether column triggers row click events
         * - render: Closure function for custom cell rendering
         * - class: Additional CSS classes for styling
         * - no_click: Prevents row click events on specific columns
         */
        $columns = [
            [
                'key' => 'date_of_occurrence',
                'label' => 'Date of Occurrence',
                'width' => '12%',
                'sortable' => true,
                'clickable' => true,
                'render' => function($incident) {
                    // Format date for display, show 'N/A' if no date available
                    return $incident->date_of_occurrence ? $incident->date_of_occurrence->format('d/m/Y') : 'N/A';
                }
            ],
            [
                'key' => 'brief_description',
                'label' => 'Description',
                'width' => '15%',
                'sortable' => true,
                'render' => function($incident) {
                    // Truncate description to 60 characters for table display
                    return '<div class="max-w-xs"><div class="font-medium">' . Str::limit($incident->brief_description, 60) . '</div></div>';
                }
            ],
            [
                'label' => 'Type',
                'width' => '12%',
                'render' => function($incident) {
                    // Display incident type name with fallback for null relationships
                    return $incident->incidentType->name ?? 'N/A';
                }
            ],
            [
                'label' => 'Location',
                'width' => '10%',
                'render' => function($incident) {
                    // Truncate location to 30 characters for compact display
                    return Str::limit($incident->location, 30);
                },
                'class' => 'text-sm text-gray-500 dark:text-gray-400'
            ],
            [
                'label' => 'Branch',
                'width' => '10%',
                'render' => function($incident) {
                    // Display branch name with fallback for null relationships
                    return $incident->branch->branch_name ?? 'N/A';
                }
            ],
            [
                'label' => 'Status',
                'width' => '10%',
                'no_click' => true, // Prevent row click to allow status dropdown interaction
                'class' => 'whitespace-nowrap',
                'render' => function($incident) {
                    // Render different status displays based on archived state
                    if ($incident->archived) {
                        // Archived incidents show read-only status badge
                        return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">' . ucfirst($incident->status) . '</span>';
                    }
                    $user_role = auth()->user()->role_name;
                    //dd($user_role);
                    // Active incidents show interactive status dropdown
                    if ($user_role === 'basic') {
                        return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">' . ucfirst($incident->status) . '</span>';

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
                                <div class="clean-select-option" data-value="open">Open</div>
                                <div class="clean-select-option" data-value="investigating">Investigating</div>
                                <div class="clean-select-option" data-value="closed">Closed</div>
                            </div>
                        </div>';
                    }
                }
            ],
            [
                'label' => 'Comments',
                'width' => '12%',
                'no_click' => true, // Prevent row click to allow comments button interaction
                'class' => 'whitespace-nowrap',
                'render' => function($incident) {
                    // Render comments button with count indicator
                    $count = $incident->comments_count ?? 0;
                    $countDisplay = $count > 0 ? $count : ''; // Only show count if > 0
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
            [
                'label' => 'Actions',
                'width' => '14%',
                'no_click' => true, // Prevent row click to allow action button interactions
                'class' => 'whitespace-nowrap text-sm font-medium',
                'render' => function($incident) {
                    // Render different action sets based on archived state

                    // if user is basic, no actions
                    $user_role = auth()->user()->role_name;
                    if ($user_role === 'basic') {
                        if (!$incident->archived) {
                            // Active incidents show Edit and Archive buttons
                            return '
                            <div class="flex items-center space-x-2">
                                <button onclick="archiveIncident(' . $incident->id . ')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">Archive</button>
                            </div>';
                        } else {
                            // Archived incidents show Restore button
                            return '<button onclick="unarchiveIncident(' . $incident->id . ')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">Restore</button>';
                        }
                    }else{
                        if (!$incident->archived) {
                            // Active incidents show Edit and Archive buttons
                            return '
                            <div class="flex items-center space-x-2">
                                <a href="' . route('incidents.edit', $incident) . '" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">Edit</a>
                                <button onclick="archiveIncident(' . $incident->id . ')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">Archive</button>
                            </div>';
                        } else {
                            // Archived incidents show Restore button
                            return '<button onclick="unarchiveIncident(' . $incident->id . ')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">Restore</button>';
                        }
                    }



                }
            ]
        ];

        /**
         * Filter configuration for the data table
         * Provides filtering options for:
         * - Incident Type: Dropdown selection of available incident types
         * - Branch: Dropdown selection of available branches
         * - Status: Dropdown selection of incident status
         * - Archive Status: Checkbox to toggle archived incidents display
         */
        $filters = [
            [
                'name' => 'incident_type_id',
                'label' => 'Incident Type',
                'type' => 'select',
                'all_option' => 'All Types',
                'options' => isset($incidentTypes) ? $incidentTypes->pluck('name', 'id')->toArray() : []
            ],
            [
                'name' => 'branch_id',
                'label' => 'Branch',
                'type' => 'select',
                'all_option' => 'All Branches',
                'options' => isset($branches) ? $branches->pluck('branch_name', 'id')->toArray() : []
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'all_option' => 'All Statuses',
                'options' => [
                    'open' => 'Open',
                    'investigating' => 'Investigating',
                    'closed' => 'Closed'
                ]
            ],
            [
                'name' => 'archived',
                'label' => 'Show Archived',
                'type' => 'checkbox',
                'value' => '1'
            ]
        ];
    ?>

    
    <?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['id' => 'incidents','data' => $incidents,'columns' => $columns,'filters' => $filters,'searchFields' => ['brief_description', 'location', 'what_happened'],'searchPlaceholder' => 'Search by description, location, or details...','routeName' => 'incidents.my-investigations','showArchived' => true,'emptyMessage' => 'No investigations assigned']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'incidents','data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($incidents),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns),'filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters),'search-fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['brief_description', 'location', 'what_happened']),'search-placeholder' => 'Search by description, location, or details...','route-name' => 'incidents.my-investigations','show-archived' => true,'empty-message' => 'No investigations assigned']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8463834ba515134d5c98b88e1a9dc03)): ?>
<?php $attributes = $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03; ?>
<?php unset($__attributesOriginalc8463834ba515134d5c98b88e1a9dc03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8463834ba515134d5c98b88e1a9dc03)): ?>
<?php $component = $__componentOriginalc8463834ba515134d5c98b88e1a9dc03; ?>
<?php unset($__componentOriginalc8463834ba515134d5c98b88e1a9dc03); ?>
<?php endif; ?>

    

    
    <?php echo $__env->make('incidents.components.comments-modal', [
        'modalId' => 'commentsModal',
        'itemType' => 'incident',
        'title' => 'Comments'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('incidents.components.corrective-actions-modal', [
        'modalId' => 'correctiveActionsModal',
        'title' => 'Corrective Actions Required - Investigation Closure',
        'itemType' => 'incident'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        /**
         * JavaScript functionality for My Investigations interface
         *
         * Handles:
         * - Custom row click navigation
         * - Status update operations with corrective actions integration
         * - Archive/unarchive operations with confirmation
         * - Error handling and user feedback
         */

        /**
         * Handle row click events to navigate to incident edit page
         * Called by data table component when clickable rows are clicked
         *
         * @param {number} incidentId - The ID of the incident to edit
         */
        function customRowClickHandler(incidentId) {
            window.location.href = `/incidents/${incidentId}/edit`;
        }

        /**
         * Update incident status with corrective actions integration
         * Routes completed/closed statuses through corrective actions modal
         * Updates other statuses directly via AJAX
         *
         * @param {number} incidentId - The incident ID to update
         * @param {string} status - The new status value
         * @param {Element} selectElement - The status dropdown DOM element
         */
        function updateItemStatus(incidentId, status, selectElement) {
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI elements to reflect new status without page reload
                        const statusText = selectElement.querySelector('.status-text');
                        statusText.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                        selectElement.className = `clean-select status-${status}`;
                        selectElement.dataset.currentStatus = status;

                        // Update dropdown option selection state
                        selectElement.querySelectorAll('.clean-select-option').forEach(opt => {
                            opt.classList.toggle('selected', opt.dataset.value === status);
                        });
                    } else {
                        // Show error message from server response
                        alert('Error updating status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Status update error:', error);
                    alert('Error updating status');
                });
        }

        /**
         * Archive incident with user confirmation
         * Removes incident from active investigations list
         *
         * @param {number} incidentId - The incident ID to archive
         */
        async function archiveIncident(incidentId) {
            // Confirm action before proceeding
            if (!confirm('Are you sure you want to archive this incident?')) return;

            try {
                const response = await fetch(`/incidents/${incidentId}/archive`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Reload page to reflect changes
                    location.reload();
                } else {
                    alert('Failed to archive incident. Please try again.');
                }
            } catch (error) {
                console.error('Archive error:', error);
                alert('An error occurred. Please try again.');
            }
        }

        /**
         * Restore incident from archive with user confirmation
         * Returns archived incident to active investigations list
         *
         * @param {number} incidentId - The incident ID to restore
         */
        async function unarchiveIncident(incidentId) {
            // Confirm action before proceeding
            if (!confirm('Are you sure you want to restore this incident from archive?')) return;

            try {
                const response = await fetch(`/incidents/${incidentId}/unarchive`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Reload page to reflect changes
                    location.reload();
                } else {
                    alert('Failed to restore incident. Please try again.');
                }
            } catch (error) {
                console.error('Unarchive error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/incidents/my-investigations.blade.php ENDPATH**/ ?>