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

            .create-employee-btn {
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

            .create-employee-btn:hover {
                background-color: var(--secondary-colour);
                border-color: var(--secondary-colour);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-employee-btn:active {
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

                .create-employee-btn {
                    width: 100%;
                    justify-content: center;
                    padding: 0.875rem;
                }
            }

            @media (max-width: 480px) {
                .header-title {
                    font-size: 0.95rem;
                }

                .create-employee-btn {
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
                <h2 class="header-title">Manage Employees</h2>
            </div>
            <a href="<?php echo e(route('settings.employees.create')); ?>" class="create-employee-btn">
                Add New Employee
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

        /* Filter select elements */
        .site-select, .status-select, .branch-select {
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

        .site-select:focus, .status-select:focus, .branch-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .site-select:hover, .status-select:hover, .branch-select:hover {
            border-color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .site-select, .status-select, .branch-select {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .site-select:focus, .status-select:focus, .branch-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .site-select:hover, .status-select:hover, .branch-select:hover {
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
        .employees-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .employees-table th {
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
            .employees-table th {
                background-color: #374151;
                color: #d1d5db;
                border-bottom-color: #4b5563;
            }
        }

        .employees-table td {
            padding: 0.875rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }

        @media (prefers-color-scheme: dark) {
            .employees-table td {
                color: #d1d5db;
                border-bottom-color: #374151;
            }
        }

        .employees-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .employees-table tbody tr:last-child td {
            border-bottom: none;
        }

        .employees-table tbody tr:hover {
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            .employees-table tbody tr:hover {
                background-color: #374151;
            }
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .status-active {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #22c55e;
        }

        .status-leaver {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        @media (prefers-color-scheme: dark) {
            .status-active {
                background-color: rgba(34, 197, 94, 0.2);
                color: #4ade80;
                border-color: #22c55e;
            }

            .status-leaver {
                background-color: rgba(239, 68, 68, 0.2);
                color: #f87171;
                border-color: #ef4444;
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

        .delete-btn {
            color: #dc2626;
            border: 1px solid transparent;
        }

        .delete-btn:hover {
            color: #b91c1c;
            background-color: #fef2f2;
            border-color: #fecaca;
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

            .delete-btn {
                color: #f87171;
            }

            .delete-btn:hover {
                color: #fca5a5;
                background-color: rgba(239, 68, 68, 0.1);
                border-color: rgba(239, 68, 68, 0.3);
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

        /* Employee number styling */
        .employee-number {
            font-size: 0.75rem;
            color: #6b7280;
        }

        @media (prefers-color-scheme: dark) {
            .employee-number {
                color: #9ca3af;
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

            .employees-table th,
            .employees-table td {
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

            .employees-table th,
            .employees-table td {
                padding: 1.25rem 1.5rem;
            }
        }

        /* Mobile table scroll */
        @media (max-width: 767px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .employees-table {
                min-width: 800px;
            }

            .employees-table th:last-child,
            .employees-table td:last-child {
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

            .employees-table th,
            .employees-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }

            .search-button {
                padding: 0.75rem 0.75rem;
                font-size: 0.8rem;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            <!-- Filters -->
            <div class="filters-container">
                <form method="GET" class="filters-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="search" class="filter-label">Search Employees</label>
                            <div class="search-input-group">
                                <input type="text"
                                       name="search"
                                       id="search"
                                       value="<?php echo e(request('search')); ?>"
                                       placeholder="Search by name, email, or employee number..."
                                       class="search-input">
                                <button type="submit" class="search-button">Search</button>
                            </div>
                        </div>

                        <div class="filter-group">
                            <label for="site_id" class="filter-label">Site</label>
                            <select name="site_id" id="site_id" class="site-select">
                                <option value="">All Sites</option>
                                <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($site->id); ?>" <?php echo e(request('site_id') == $site->id ? 'selected' : ''); ?>>
                                        <?php echo e($site->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="branch_id" class="filter-label">Branch</label>
                            <select name="branch_id" id="branch_id" class="branch-select">
                                <option value="">All Branches</option>
                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($branch->id); ?>" <?php echo e(request('branch_id') == $branch->id ? 'selected' : ''); ?>>
                                        <?php echo e($branch->branch_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="status" class="filter-label">Status</label>
                            <select name="status" id="status" class="status-select">
                                <option value="">All Status</option>
                                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="leaver" <?php echo e(request('status') === 'leaver' ? 'selected' : ''); ?>>Leaver</option>
                            </select>
                        </div>
                    </div>

                    <?php if(request()->hasAny(['search', 'site_id', 'branch_id', 'status'])): ?>
                        <a href="<?php echo e(route('settings.employees.index')); ?>" class="clear-filters-btn">Clear Filters</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- employees Table -->
            <div class="table-container">
                <table class="employees-table">
                    <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Contact</th>
                        <th>Site</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div>
                                    <div class="font-medium"><?php echo e($employee->full_name); ?></div>
                                    <div class="employee-number">#<?php echo e($employee->employee_number); ?></div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div><?php echo e($employee->email); ?></div>
                                    <?php if($employee->number): ?>
                                        <div class="employee-number"><?php echo e($employee->number); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><?php echo e($employee->site->name ?? 'N/A'); ?></td>
                            <td><?php echo e($employee->site->branch->branch_name ?? '-'); ?></td> 
                            <td>
                                    <span class="status-badge status-<?php echo e($employee->status); ?>">
                                        <?php echo e(ucfirst($employee->status)); ?>

                                    </span>
                            </td>
                            <td>
                                <div class="action-links">
                                    <a href="<?php echo e(route('settings.employees.edit', $employee)); ?>" class="edit-link">
                                        Edit
                                    </a>
                                    <form method="POST" action="<?php echo e(route('settings.employees.destroy', $employee)); ?>"
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="empty-message">
                                No employees found.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <?php echo e($employees->appends(request()->query())->links()); ?>

            </div>
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
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/settings/employees/index.blade.php ENDPATH**/ ?>