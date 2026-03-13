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
        <div class="header-container">
            <div class="header-left">
                <a href="<?php echo e(route('settings.index')); ?>" class="back-button" title="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Email Templates</h2>
            </div>
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('settings.index')); ?>">Settings</a></li>
                    <li class="breadcrumb-item active">Email Templates</li>
                </ol>
            </nav>
        </div>
     <?php $__env->endSlot(); ?>

    <style>
        /* Header styling */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
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
        }

        @media (prefers-color-scheme: dark) {
            .header-title {
                color: #d1d5db;
            }
        }

        .breadcrumb-nav {
            font-size: 0.875rem;
        }

        .breadcrumb {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            color: #6b7280;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '>';
            margin: 0 0.5rem;
            color: #9ca3af;
        }

        .breadcrumb-item a {
            color: #6366f1;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: #4f46e5;
        }

        .breadcrumb-item.active {
            color: #374151;
            font-weight: 500;
        }

        @media (prefers-color-scheme: dark) {
            .breadcrumb-item.active {
                color: #d1d5db;
            }
        }

        /* Main container */
        .main-container {
            padding: 2.5rem 1rem;
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

        /* Search and filters */
        .search-filters-container {
            background-color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .search-filters-container {
                background-color: #1f2937;
                border-color: #374151;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: end;
        }

        .search-group {
            flex: 1;
            min-width: 200px;
        }

        .search-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .search-label {
                color: #d1d5db;
            }
        }

        .search-input, .filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            color: #111827;
            font-size: 1rem;
            transition: all 0.15s ease-in-out;
        }

        .search-input:focus, .filter-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .search-input, .filter-select {
                background: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .search-input:focus, .filter-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }
        }

        .search-button {
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-colour);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }

        .search-button:hover {
            background-color: var(--secondary-colour);
        }

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
        }

        .clear-filters-btn:hover {
            background-color: #4b5563;
        }

        /* Templates grid */
        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .template-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .template-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        @media (prefers-color-scheme: dark) {
            .template-card {
                background: #1f2937;
                border-color: #374151;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        .template-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .template-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
        }

        @media (prefers-color-scheme: dark) {
            .template-title {
                color: #f9fafb;
            }
        }

        .template-status {
            margin-left: auto;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: capitalize;
        }

        .status-active {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .status-inactive {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        @media (prefers-color-scheme: dark) {
            .status-active {
                background-color: rgba(16, 185, 129, 0.2);
                color: #6ee7b7;
                border-color: #10b981;
            }
            .status-inactive {
                background-color: rgba(107, 114, 128, 0.2);
                color: #9ca3af;
                border-color: #4b5563;
            }
        }

        .template-meta {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        @media (prefers-color-scheme: dark) {
            .template-meta {
                color: #9ca3af;
            }
        }

        .template-description {
            color: #374151;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .template-description {
                color: #d1d5db;
            }
        }

        .template-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            justify-content: space-between;
        }

        .variable-count {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        @media (prefers-color-scheme: dark) {
            .variable-count {
                color: #9ca3af;
            }
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        .btn-primary:hover {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .btn-success {
            background-color: #10b981;
            color: white;
            border-color: #10b981;
        }

        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .empty-state {
                background: #1f2937;
                border-color: #374151;
            }
        }

        .empty-icon {
            width: 4rem;
            height: 4rem;
            color: #9ca3af;
            margin: 0 auto 1rem;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .empty-title {
                color: #f9fafb;
            }
        }

        .empty-description {
            color: #6b7280;
            margin-bottom: 2rem;
        }

        @media (prefers-color-scheme: dark) {
            .empty-description {
                color: #9ca3af;
            }
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 0.75rem;
            width: 90%;
            max-width: 600px;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @media (prefers-color-scheme: dark) {
            .modal-content {
                background-color: #1f2937;
                color: #e5e7eb;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        @media (prefers-color-scheme: dark) {
            .modal-title {
                color: #f9fafb;
            }
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            margin: -0.5rem;
        }

        .modal-close:hover {
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .modal-close:hover {
                color: #d1d5db;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .form-label {
                color: #d1d5db;
            }
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            color: #111827;
            font-size: 1rem;
            transition: all 0.15s ease-in-out;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .form-input {
                background: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .form-input:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
            border-color: #6b7280;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            border-color: #4b5563;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .main-container {
                padding: 1.5rem 0.5rem;
            }

            .search-form {
                flex-direction: column;
            }

            .templates-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .template-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .action-buttons {
                width: 100%;
            }

            .btn {
                flex: 1;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            <!-- Search and Filters -->
            <div class="search-filters-container">
                <form method="GET" action="<?php echo e(route('settings.emails.templates.index')); ?>" class="search-form">
                    <!-- Search -->
                    <div class="search-group">
                        <label class="search-label">Search Templates</label>
                        <input type="text" name="search" value="<?php echo e($search); ?>"
                               placeholder="Search by name, key, or description..."
                               class="search-input">
                    </div>

                    <!-- Category Filter -->
                    <div class="search-group">
                        <label class="search-label">Category</label>
                        <select name="category" class="filter-select">
                            <option value="">All Categories</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat); ?>" <?php echo e($category === $cat ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($cat)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="search-group">
                        <button type="submit" class="search-button">Search</button>
                    </div>

                    <?php if($search || $category): ?>
                        <div class="search-group">
                            <a href="<?php echo e(route('settings.emails.templates.index')); ?>" class="clear-filters-btn">
                                Clear Filters
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Templates Grid -->
            <?php if($templates->count() > 0): ?>
                <div class="templates-grid">
                    <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="template-card">
                            <div class="template-header">
                                <div>
                                    <h3 class="template-title"><?php echo e($template->name); ?></h3>
                                    <div class="template-meta">
                                        <strong>Key:</strong> <?php echo e($template->key); ?> |
                                        <?php if($template->category): ?>
                                            <strong>Category:</strong> <?php echo e(ucfirst($template->category)); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="template-status">
                                    <span class="status-badge status-<?php echo e($template->is_active ? 'active' : 'inactive'); ?>">
                                        <?php echo e($template->is_active ? 'Active' : 'Inactive'); ?>

                                    </span>
                                </div>
                            </div>

                            <?php if($template->description): ?>
                                <p class="template-description">
                                    <?php echo e(Str::limit($template->description, 150)); ?>

                                </p>
                            <?php endif; ?>

                            <div class="template-actions">
                                <span class="variable-count">
                                    <?php
                                        $variableCount = method_exists($template, 'getVariablesList') ? count($template->getVariablesList()) : 0;
                                    ?>
                                    <?php echo e($variableCount); ?> <?php echo e(Str::plural('variable', $variableCount)); ?>

                                </span>

                                <div class="action-buttons">
                                    <a href="<?php echo e(route('settings.emails.templates.show', $template)); ?>" class="btn btn-primary">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sendTest', $template)): ?>
                                        <button class="btn btn-success" onclick="openTestModal(<?php echo e($template->id); ?>, '<?php echo e(e($template->name)); ?>')">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Test
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($templates->hasPages()): ?>
                    <div class="mt-8">
                        <?php echo e($templates->links()); ?>

                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="empty-title">No Email Templates Found</h3>
                    <p class="empty-description">
                        <?php if($search || $category): ?>
                            Try adjusting your search criteria or filters.
                        <?php else: ?>
                            No email templates have been configured yet.
                        <?php endif; ?>
                    </p>
                    <?php if($search || $category): ?>
                        <a href="<?php echo e(route('settings.emails.templates.index')); ?>" class="clear-filters-btn">
                            Clear Filters
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Test Email Modal -->
    <div class="modal" id="testEmailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Send Test Email</h3>
                <button type="button" class="modal-close" onclick="closeTestModal()">&times;</button>
            </div>

            <form id="testEmailForm" onsubmit="sendTestEmail(event)">
                <div class="form-group">
                    <label for="recipientEmail" class="form-label">Recipient Email *</label>
                    <input type="email" class="form-input" id="recipientEmail" required>
                </div>

                <div id="variablesSection" style="display: none;">
                    <h4 class="form-label">Variable Overrides</h4>
                    <div id="variablesList"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeTestModal()">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Send Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentTemplateId = null;

        function openTestModal(templateId, templateName) {
            currentTemplateId = templateId;
            document.querySelector('#testEmailModal .modal-title').textContent = `Send Test for: ${templateName}`;

            // Fetch template variables to populate the modal
            fetch(`/settings/emails/templates/${templateId}/preview`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const variablesSection = document.getElementById('variablesSection');
                    const variablesList = document.getElementById('variablesList');
                    variablesList.innerHTML = ''; // Clear previous variables

                    if (data.success && data.variables && data.variables.length > 0) {
                        data.variables.forEach(variable => {
                            const div = document.createElement('div');
                            div.className = 'form-group';
                            div.innerHTML = `
                                <label for="var_${variable}" class="form-label">${variable}</label>
                                <input type="text" class="form-input"
                                       name="variables[${variable}]" id="var_${variable}"
                                       placeholder="Override value for ${variable}">
                            `;
                            variablesList.appendChild(div);
                        });
                        variablesSection.style.display = 'block';
                    } else {
                        variablesSection.style.display = 'none';
                    }

                    document.getElementById('testEmailModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching template variables:', error);
                    alert('Could not load template variables. Please try again.');
                });
        }

        function closeTestModal() {
            document.getElementById('testEmailModal').style.display = 'none';
            document.getElementById('testEmailForm').reset();
            document.getElementById('variablesList').innerHTML = '';
        }

        function sendTestEmail(event) {
            event.preventDefault();

            const recipientEmail = document.getElementById('recipientEmail').value;
            const variables = {};
            const inputs = document.querySelectorAll('#variablesList .form-input');

            inputs.forEach(input => {
                const varName = input.name.match(/\[(.*?)\]/)[1];
                if (input.value.trim()) {
                    variables[varName] = input.value;
                }
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/settings/emails/templates/${currentTemplateId}/send-test`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    recipient_email: recipientEmail,
                    variables: variables
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeTestModal();
                    } else {
                        throw new Error(data.message || 'An unknown error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error sending test email:', error);
                    alert(`Failed to send test email: ${error.message}`);
                });
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('testEmailModal');
            if (event.target == modal) {
                closeTestModal();
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('testEmailModal');
            if (event.key === "Escape" && modal.style.display === "block") {
                closeTestModal();
            }
        });
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
<?php endif; ?>
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/settings/emails/templates/index.blade.php ENDPATH**/ ?>