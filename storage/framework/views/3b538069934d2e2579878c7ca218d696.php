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
        <style>
            /* Header styling */
            .header-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
                padding: 0.5rem 0;
            }

            .header-left {
                display: flex;
                align-items: center;
                gap: 1rem;
                min-width: 0;
                flex: 1;
            }

            .back-button {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem;
                color: #6b7280;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.15s ease-in-out;
                background-color: white;
                flex-shrink: 0;
            }

            .back-button:hover {
                color: var(--primary-colour);
                border-color: var(--primary-colour);
                background-color: #fef7f0;
                transform: translateX(-2px);
            }

            @media (prefers-color-scheme: dark) {
                .back-button {
                    color: #9ca3af;
                    border-color: #4b5563;
                    background-color: #1f2937;
                }

                .back-button:hover {
                    color: var(--primary-colour);
                    border-color: var(--primary-colour);
                    background-color: rgba(167, 98, 44, 0.1);
                }
            }

            .header-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0;
                word-break: break-word;
            }

            @media (prefers-color-scheme: dark) {
                .header-title {
                    color: #d1d5db;
                }
            }

            .create-user-btn {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.5rem;
                font-size: 0.875rem;
                font-weight: 700;
                color: white;
                background-color: var(--primary-colour);
                border: 2px solid var(--primary-colour);
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.15s ease-in-out;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                white-space: nowrap;
                min-width: fit-content;
            }

            .create-user-btn:hover {
                background-color: var(--secondary-colour);
                border-color: var(--secondary-colour);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-user-btn:active {
                transform: translateY(0);
            }

            /* Mobile header adjustments */
            @media (max-width: 768px) {
                .header-container {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 0.75rem;
                }

                .header-left {
                    width: 100%;
                    justify-content: flex-start;
                }

                .header-title {
                    font-size: 1rem;
                }

                .create-user-btn {
                    width: 100%;
                    justify-content: center;
                    padding: 0.875rem;
                }
            }

            @media (max-width: 480px) {
                .header-title {
                    font-size: 0.95rem;
                }

                .create-user-btn {
                    font-size: 0.8rem;
                    padding: 0.75rem;
                }
            }
        </style>
        <div class="header-container">
            <div class="header-left">
                <a href="<?php echo e(route('settings.index')); ?>" class="back-button" title="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Manage Users</h2>
            </div>
            <a href="<?php echo e(route('settings.users.create')); ?>" class="create-user-btn">
                Create User
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <style>
        /* Main container */
        .main-container {
            padding: 1.5rem 0.75rem;
            color: #374151;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        @media (prefers-color-scheme: dark) {
            .main-container {
                color: #d1d5db;
                background-color: #111827;
            }
        }

        .content-wrapper {
            max-width: 80rem;
            margin: 0 auto;
        }

        /* Archived row styling */
        .archived-row {
            background-color: #fef2f2 !important;
            opacity: 0.8;
        }

        @media (prefers-color-scheme: dark) {
            .archived-row {
                background-color: rgba(239, 68, 68, 0.1) !important;
            }
        }

        .archived-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        @media (prefers-color-scheme: dark) {
            .archived-badge {
                background-color: rgba(239, 68, 68, 0.2);
                color: #fca5a5;
                border-color: rgba(239, 68, 68, 0.3);
            }
        }

        /* Filter and search container */
        .filters-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        @media (prefers-color-scheme: dark) {
            .filters-container {
                background-color: #1f2937;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        .filters-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
        }

        .filter-row {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .filter-label {
                color: #d1d5db;
            }
        }

        /* Checkbox styling */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .checkbox-input {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary-colour);
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: #374151;
            cursor: pointer;
        }

        @media (prefers-color-scheme: dark) {
            .checkbox-label {
                color: #d1d5db;
            }
        }

        /* Search input group */
        .search-input-group {
            display: flex;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            overflow: hidden;
            background: white;
            transition: all 0.15s ease-in-out;
        }

        .search-input-group:focus-within {
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .search-input-group {
                background: #374151;
                border-color: #4b5563;
            }

            .search-input-group:focus-within {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            outline: none;
            font-size: 1rem;
            background: transparent;
            color: #111827;
            min-width: 0;
        }

        @media (prefers-color-scheme: dark) {
            .search-input {
                color: #e5e7eb;
            }
        }

        .search-input::placeholder {
            color: #6b7280;
        }

        @media (prefers-color-scheme: dark) {
            .search-input::placeholder {
                color: #9ca3af;
            }
        }

        .search-button {
            padding: 0.75rem 1rem;
            background-color: var(--primary-colour);
            border: none;
            color: white;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: background-color 0.15s ease-in-out;
            white-space: nowrap;
        }

        .search-button:hover {
            background-color: var(--secondary-colour);
        }

        /* Role filter select */
        .role-select {
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            color: #111827;
            font-size: 1rem;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            width: 100%;
        }

        .role-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .role-select:hover {
            border-color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .role-select {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .role-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .role-select:hover {
                border-color: #6b7280;
            }
        }

        /* Clear filters button */
        .clear-filters-btn {
            padding: 0.75rem 1rem;
            background-color: #6b7280;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .clear-filters-btn:hover {
            background-color: #4b5563;
        }

        /* Table container - Mobile responsive */
        .table-container {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        @media (prefers-color-scheme: dark) {
            .table-container {
                background-color: #1f2937;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        /* Mobile-first table styling */
        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .users-table th {
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background-color: #e5e7eb;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
        }

        @media (prefers-color-scheme: dark) {
            .users-table th {
                background-color: #374151;
                color: #d1d5db;
                border-bottom-color: #4b5563;
            }
        }

        .users-table td {
            padding: 0.875rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }

        @media (prefers-color-scheme: dark) {
            .users-table td {
                color: #d1d5db;
                border-bottom-color: #374151;
            }
        }

        .users-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .users-table tbody tr:last-child td {
            border-bottom: none;
        }

        .users-table tbody tr:hover {
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            .users-table tbody tr:hover {
                background-color: #374151;
            }
        }

        /* Role badges - Dynamic colors */
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: capitalize;
            white-space: nowrap;
            border: 1px solid;
        }

        /* Dynamic role color classes */
        .role-color-0 {
            background-color: #fef3c7;
            color: #92400e;
            border-color: #f59e0b;
        }

        .role-color-1 {
            background-color: #e0e7ff;
            color: #3730a3;
            border-color: #6366f1;
        }

        .role-color-2 {
            background-color: #d1fae5;
            color: #065f46;
            border-color: #10b981;
        }

        .role-color-3 {
            background-color: #fce7f3;
            color: #831843;
            border-color: #ec4899;
        }

        .role-color-4 {
            background-color: #ddd6fe;
            color: #5b21b6;
            border-color: #8b5cf6;
        }

        .role-color-5 {
            background-color: #fed7aa;
            color: #9a3412;
            border-color: #ea580c;
        }

        .role-color-6 {
            background-color: #cffafe;
            color: #155e75;
            border-color: #06b6d4;
        }

        .role-color-7 {
            background-color: #fef9c3;
            color: #854d0e;
            border-color: #eab308;
        }

        .role-color-8 {
            background-color: #ede9fe;
            color: #6b21a8;
            border-color: #a855f7;
        }

        .role-color-9 {
            background-color: #fbcfe8;
            color: #9f1239;
            border-color: #f43f5e;
        }

        @media (prefers-color-scheme: dark) {
            .role-color-0 {
                background-color: rgba(251, 191, 36, 0.2);
                color: #fbbf24;
                border-color: #f59e0b;
            }

            .role-color-1 {
                background-color: rgba(99, 102, 241, 0.2);
                color: #a5b4fc;
                border-color: #6366f1;
            }

            .role-color-2 {
                background-color: rgba(16, 185, 129, 0.2);
                color: #6ee7b7;
                border-color: #10b981;
            }

            .role-color-3 {
                background-color: rgba(236, 72, 153, 0.2);
                color: #f9a8d4;
                border-color: #ec4899;
            }

            .role-color-4 {
                background-color: rgba(139, 92, 246, 0.2);
                color: #c4b5fd;
                border-color: #8b5cf6;
            }

            .role-color-5 {
                background-color: rgba(234, 88, 12, 0.2);
                color: #fdba74;
                border-color: #ea580c;
            }

            .role-color-6 {
                background-color: rgba(6, 182, 212, 0.2);
                color: #67e8f9;
                border-color: #06b6d4;
            }

            .role-color-7 {
                background-color: rgba(234, 179, 8, 0.2);
                color: #fde047;
                border-color: #eab308;
            }

            .role-color-8 {
                background-color: rgba(168, 85, 247, 0.2);
                color: #d8b4fe;
                border-color: #a855f7;
            }

            .role-color-9 {
                background-color: rgba(244, 63, 94, 0.2);
                color: #fda4af;
                border-color: #f43f5e;
            }
        }

        /* Action buttons - Mobile optimized */
        .action-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-links a, .action-links button {
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
            border: none;
            cursor: pointer;
            background: none;
            text-align: center;
            width: 100%;
        }

        .edit-link {
            color: #2563eb;
            border: 1px solid transparent;
        }

        .edit-link:hover {
            color: #1d4ed8;
            background-color: #eff6ff;
            border-color: #bfdbfe;
        }

        .archive-btn {
            color: #f59e0b;
            border: 1px solid transparent;
        }

        .archive-btn:hover {
            color: #d97706;
            background-color: #fffbeb;
            border-color: #fed7aa;
        }

        .unarchive-btn {
            color: #059669;
            border: 1px solid transparent;
        }

        .unarchive-btn:hover {
            color: #047857;
            background-color: #ecfdf5;
            border-color: #a7f3d0;
        }

        @media (prefers-color-scheme: dark) {
            .edit-link {
                color: #60a5fa;
            }

            .edit-link:hover {
                color: #93c5fd;
                background-color: rgba(59, 130, 246, 0.1);
                border-color: rgba(59, 130, 246, 0.3);
            }

            .archive-btn {
                color: #fbbf24;
            }

            .archive-btn:hover {
                color: #fcd34d;
                background-color: rgba(245, 158, 11, 0.1);
                border-color: rgba(245, 158, 11, 0.3);
            }

            .unarchive-btn {
                color: #34d399;
            }

            .unarchive-btn:hover {
                color: #6ee7b7;
                background-color: rgba(5, 150, 105, 0.1);
                border-color: rgba(5, 150, 105, 0.3);
            }
        }

        /* Empty state */
        .empty-message {
            text-align: center;
            padding: 2rem 1rem;
            color: #6b7280;
            font-size: 0.95rem;
        }

        @media (prefers-color-scheme: dark) {
            .empty-message {
                color: #9ca3af;
            }
        }

        /* Pagination container */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        /* Results summary */
        .results-summary {
            background-color: white;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        @media (prefers-color-scheme: dark) {
            .results-summary {
                background-color: #1f2937;
                color: #9ca3af;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            }
        }

        /* Desktop responsive adjustments */
        @media (min-width: 768px) {
            .main-container {
                padding: 2rem 1rem;
            }

            .filters-container {
                padding: 1.5rem;
            }

            .filters-form {
                flex-direction: row;
                align-items: end;
                flex-wrap: wrap;
            }

            .filter-row {
                flex-direction: row;
                flex: 1;
                min-width: 300px;
            }

            .filter-group:first-child {
                flex: 2;
                min-width: 300px;
            }

            .filter-group:not(:first-child) {
                flex: 1;
                min-width: 150px;
            }

            .clear-filters-btn {
                width: auto;
                min-width: 120px;
            }

            .users-table th,
            .users-table td {
                padding: 1rem 1.25rem;
            }

            .action-links {
                flex-direction: row;
                gap: 0.75rem;
            }

            .action-links a, .action-links button {
                width: auto;
                font-size: 0.875rem;
            }
        }

        @media (min-width: 1024px) {
            .main-container {
                padding: 2.5rem 1rem;
            }

            .users-table th,
            .users-table td {
                padding: 1.25rem 1.5rem;
            }
        }

        /* Ultra-wide screen adjustments */
        @media (min-width: 1400px) {
            .content-wrapper {
                max-width: 90rem;
            }
        }

        /* Mobile table scroll */
        @media (max-width: 767px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .users-table {
                min-width: 600px;
            }

            .users-table th:last-child,
            .users-table td:last-child {
                padding-right: 1rem;
                min-width: 120px;
            }
        }

        /* Very small mobile adjustments */
        @media (max-width: 375px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .filters-container {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }

            .users-table th,
            .users-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }

            .role-badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }

            .search-button {
                padding: 0.75rem 0.75rem;
                font-size: 0.8rem;
            }
        }
    </style>

    <?php
        // Get all available roles for the filter
        $allRoles = \App\Models\Role::orderBy('name')->get();

        // Function to assign consistent color index to role
        function getRoleColorIndex($roleId, $totalRoles) {
            return ($roleId - 1) % 10; // Cycle through 10 color variations
        }
    ?>

    <div class="main-container">
        <div class="content-wrapper">
            <!-- Filters and Search -->
            <div class="filters-container">
                <form method="GET" action="<?php echo e(route('settings.users.index')); ?>" class="filters-form">
                    <div class="filter-row">
                        <!-- Search -->
                        <div class="filter-group">
                            <label class="filter-label">Search Users</label>
                            <div class="search-input-group">
                                <input
                                    type="text"
                                    name="search"
                                    value="<?php echo e(request('search')); ?>"
                                    placeholder="Search by name or email"
                                    class="search-input"
                                />
                                <button type="submit" class="search-button">Search</button>
                            </div>
                        </div>

                        <!-- Role Filter -->
                        <div class="filter-group">
                            <label class="filter-label">Filter by Role</label>
                            <select name="role" class="role-select" onchange="this.form.submit()">
                                <option value="">All Roles</option>
                                <?php $__currentLoopData = $allRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->name); ?>" <?php echo e(request('role') === $role->name ? 'selected' : ''); ?>>
                                        <?php echo e($role->display_name ?? ucfirst($role->name)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <!-- Show Archived Checkbox -->
                    <div class="checkbox-group">
                        <input
                            type="checkbox"
                            id="show_archived"
                            name="show_archived"
                            value="1"
                            class="checkbox-input"
                            <?php echo e(request('show_archived') ? 'checked' : ''); ?>

                            onchange="this.form.submit()"
                        >
                        <label for="show_archived" class="checkbox-label">Show archived users</label>
                    </div>

                    <!-- Clear Filters -->
                    <?php if(request('search') || request('role') || request('show_archived')): ?>
                        <div class="filter-group">
                            <a href="<?php echo e(route('settings.users.index')); ?>" class="clear-filters-btn">
                                Clear Filters
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Results Summary -->
            <?php if(isset($users) && $users->total() > 0): ?>
                <div class="results-summary">
                    Showing <?php echo e($users->firstItem()); ?> to <?php echo e($users->lastItem()); ?> of <?php echo e($users->total()); ?>

                    <?php echo e(request('show_archived') ? 'archived' : 'active'); ?> users
                    <?php if(request('search')): ?>
                        for "<?php echo e(request('search')); ?>"
                    <?php endif; ?>
                    <?php if(request('role')): ?>
                        with role "<?php echo e(ucfirst(request('role'))); ?>"
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Users Table -->
            <div class="table-container">
                <table class="users-table">
                    <thead>
                    <tr>
                        <th>
                            <a href="<?php echo e(route('settings.users.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']))); ?>">
                                Name
                                <?php if(request('sort') === 'name'): ?>
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <?php if(request('direction') === 'asc'): ?>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        <?php else: ?>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        <?php endif; ?>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo e(route('settings.users.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']))); ?>">
                                Email
                                <?php if(request('sort') === 'email'): ?>
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <?php if(request('direction') === 'asc'): ?>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        <?php else: ?>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        <?php endif; ?>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>
                            <a href="<?php echo e(route('settings.users.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']))); ?>">
                                Created
                                <?php if(request('sort') === 'created_at'): ?>
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <?php if(request('direction') === 'asc'): ?>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        <?php else: ?>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        <?php endif; ?>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($user->isArchived() ? 'archived-row' : ''); ?>">
                            <td>
                                <div>
                                    <div style="font-weight: 600;"><?php echo e($user->name); ?></div>
                                    <?php if($user->email_verified_at): ?>
                                        <div style="font-size: 0.75rem; color: #16a34a;">✓ Verified</div>
                                    <?php else: ?>
                                        <div style="font-size: 0.75rem; color: #ca8a04;">⚠ Unverified</div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                                <?php if($user->role): ?>
                                    <?php
                                        $colorIndex = getRoleColorIndex($user->role->id, $allRoles->count());
                                    ?>
                                    <span class="role-badge role-color-<?php echo e($colorIndex); ?>">
                                        <?php echo e($user->role->display_name ?? ucfirst($user->role->name)); ?>

                                    </span>
                                <?php else: ?>
                                    <span style="color: #6b7280;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($user->isArchived()): ?>
                                    <span class="archived-badge">Archived</span>
                                <?php else: ?>
                                    <span style="color: #059669; font-weight: 600; font-size: 0.875rem;">Active</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($user->created_at->format('M j, Y')); ?></td>
                            <td>
                                <div class="action-links">
                                    <?php if(!$user->isArchived()): ?>
                                        <a href="<?php echo e(route('settings.users.edit', $user)); ?>" class="edit-link">
                                            Edit
                                        </a>
                                        <?php if($user->id !== auth()->id()): ?>
                                            <form method="POST" action="<?php echo e(route('settings.users.destroy', $user)); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to archive this user? They will be hidden from active users but their data will be preserved.')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="archive-btn">
                                                    Archive
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if(auth()->user()->isAdmin()): ?>
                                            <a href="<?php echo e(route('settings.users.index', ['unarchive' => $user->id])); ?>"
                                               class="unarchive-btn"
                                               onclick="return confirm('Are you sure you want to reactivate this user?')">
                                                Unarchive
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="empty-message">
                                <?php if(request('search') || request('role')): ?>
                                    <div style="text-align: center;">
                                        <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">No users found</h3>
                                        <p style="color: #6b7280; margin-bottom: 1rem; font-size: 0.875rem;">
                                            No <?php echo e(request('show_archived') ? 'archived' : 'active'); ?> users match your current search criteria.
                                        </p>
                                        <a href="<?php echo e(route('settings.users.index')); ?>" class="clear-filters-btn" style="display: inline-block; width: auto; max-width: 200px;">
                                            Clear Filters
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div style="text-align: center;">
                                        <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">
                                            No <?php echo e(request('show_archived') ? 'archived' : ''); ?> users found
                                        </h3>
                                        <p style="color: #6b7280; margin-bottom: 1.5rem; font-size: 0.875rem;">
                                            <?php if(request('show_archived')): ?>
                                                No users have been archived yet.
                                            <?php else: ?>
                                                Get started by creating your first user.
                                            <?php endif; ?>
                                        </p>
                                        <?php if(!request('show_archived')): ?>
                                            <a href="<?php echo e(route('settings.users.create')); ?>" class="create-user-btn" style="display: inline-block; width: auto; max-width: 200px;">
                                                Create User
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if(isset($users) && $users->hasPages()): ?>
                <div class="pagination-container">
                    <?php echo e($users->appends(request()->query())->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/settings/users/index.blade.php ENDPATH**/ ?>