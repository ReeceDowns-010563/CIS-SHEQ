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
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">System Settings</h2>
     <?php $__env->endSlot(); ?>

    <style>
        /* Main container */
        .settings-container {
            padding: 2.5rem 1rem;
            color: #374151;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        @media (prefers-color-scheme: dark) {
            .settings-container {
                color: #d1d5db;
                background-color: #111827;
            }
        }

        .content-wrapper {
            max-width: 80rem;
            margin: 0 auto;
        }

        /* Page header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .page-title {
                color: #f9fafb;
            }
        }

        .page-subtitle {
            font-size: 1.125rem;
            color: #6b7280;
            max-width: 42rem;
            margin: 0 auto;
        }

        @media (prefers-color-scheme: dark) {
            .page-subtitle {
                color: #9ca3af;
            }
        }

        /* Settings grid */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        /* Setting card */
        .setting-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .setting-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-colour);
            text-decoration: none;
            color: inherit;
        }

        @media (prefers-color-scheme: dark) {
            .setting-card {
                background-color: #1f2937;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }

            .setting-card:hover {
                border-color: var(--primary-colour);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.6), 0 4px 6px -2px rgba(0, 0, 0, 0.4);
            }
        }

        /* Card content - make it grow to push footer to bottom */
        .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Card footer */
        .card-footer {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        @media (prefers-color-scheme: dark) {
            .card-footer {
                border-top-color: #374151;
            }
        }

        /* Card icon */
        .card-icon {
            width: 4rem;
            height: 4rem;
            background-color: var(--primary-colour);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .setting-card:hover .card-icon {
            background-color: var(--secondary-colour);
            transform: scale(1.05);
        }

        .card-icon svg {
            width: 2rem;
            height: 2rem;
            color: white;
        }

        /* Card content */
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem;
        }

        @media (prefers-color-scheme: dark) {
            .card-title {
                color: #f9fafb;
            }
        }

        .card-description {
            font-size: 0.875rem;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .card-description {
                color: #9ca3af;
            }
        }

        /* Card features list */
        .card-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .card-features li {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .card-features li {
                color: #d1d5db;
            }
        }

        .card-features li:before {
            content: "✓";
            display: inline-block;
            width: 1rem;
            height: 1rem;
            background-color: #10b981;
            color: white;
            border-radius: 50%;
            font-size: 0.625rem;
            font-weight: 700;
            text-align: center;
            line-height: 1rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        /* Card footer */
        .card-footer {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        @media (prefers-color-scheme: dark) {
            .card-footer {
                border-top-color: #374151;
            }
        }

        .card-status {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-active {
            color: #10b981;
        }

        .status-coming-soon {
            color: #f59e0b;
        }

        .card-arrow {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary-colour);
            transition: transform 0.3s ease;
        }

        .setting-card:hover .card-arrow {
            transform: translateX(0.25rem);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .settings-container {
                padding: 1.5rem 0.5rem;
            }

            .settings-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .setting-card {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.75rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }
        }

        @media (max-width: 640px) {
            .card-icon {
                width: 3rem;
                height: 3rem;
            }

            .card-icon svg {
                width: 1.5rem;
                height: 1.5rem;
            }

            .card-title {
                font-size: 1.125rem;
            }
        }
    </style>

    <div class="settings-container">
        <div class="content-wrapper">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">System Settings</h1>
                <p class="page-subtitle">
                    Manage and configure your system settings. Access user management, customer management, API configuration, and other administrative tools.
                </p>
            </div>

            <!-- Settings Grid -->
            <div class="settings-grid">

                <!-- User Management Card -->
                <a href="<?php echo e(route('settings.users.index')); ?>" class="setting-card">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">User Management</h3>
                        <p class="card-description">
                            Create, edit, and manage user accounts. Control user roles, permissions, and access levels throughout the system.
                        </p>

                        <ul class="card-features">
                            <li>Create and edit user accounts</li>
                            <li>Manage user roles and permissions</li>
                            <li>Search and filter users</li>
                            <li>Bulk user operations</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-active">Active</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <!-- Customer Management Card -->
                <a href="<?php echo e(route('settings.customers.index')); ?>" class="setting-card">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197v1M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">Customer Management</h3>
                        <p class="card-description">
                            Manage customer information and records. Add, edit, and organize client details for efficient customer relationship management.
                        </p>

                        <ul class="card-features">
                            <li>Add and edit customer details</li>
                            <li>Track customer information</li>
                            <li>Customer contact management</li>
                            <li>Search and filter customers</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-active">Active</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <!-- Site Management Card -->
                <a href="<?php echo e(route('settings.sites.index')); ?>" class="setting-card">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">Site Management</h3>
                        <p class="card-description">
                            Configure and manage site locations, parking zones, and facility information. Control site-specific settings and configurations.
                        </p>

                        <ul class="card-features">
                            <li>Add and edit site locations</li>
                            <li>Manage site administrators</li>
                            <li>Site-specific settings</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-active">Active</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <!-- Employee Management Card -->
                <a href="<?php echo e(route('settings.employees.index')); ?>" class="setting-card">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">Employee Management</h3>
                        <p class="card-description">
                            Manage employee records across all sites. Add, edit, and organize employee information including contact details and site assignments.
                        </p>

                        <ul class="card-features">
                            <li>Add and edit employee details</li>
                            <li>Site assignment management</li>
                            <li>Employee status tracking</li>
                            <li>Search and filter employees</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-active">Active</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <!-- Security & Permissions Card -->
                <a href="<?php echo e(route('settings.features.index')); ?>" class="setting-card">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">Security & Permissions</h3>
                        <p class="card-description">
                            Manage security settings, role-based permissions, and access control throughout the system.
                        </p>

                        <ul class="card-features">
                            <li>Role management</li>
                            <li>Permission settings</li>
                            <li>Security logs</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-active">Active</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <!-- Branding Management Card -->
                <a href="<?php echo e(route('settings.branding.index')); ?>" class="setting-card">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42" />
                        </svg>

                    </div>

                    <div class="card-content">
                        <h3 class="card-title">Branding Management</h3>
                        <p class="card-description">
                            Customise your company branding for a personalised experience.
                        </p>

                        <ul class="card-features">
                            <li>Upload company logo</li>
                            <li>Button & UI colours</li>
                            <li>Live theme previews</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-active">Active</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <!-- Email Templates Card -->
                <a href="<?php echo e(route('settings.emails.templates.index')); ?>" class="setting-card">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">Email Templates</h3>
                        <p class="card-description">
                            Manage and customize email templates for incident reports, notifications, and automated communications.
                        </p>

                        <ul class="card-features">
                            <li>View templates</li>
                            <li>Test email delivery</li>
                            <li>View email audit logs</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-active">Active</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <!-- System Monitoring Card -->
                <div class="setting-card" style="cursor: not-allowed; opacity: 0.7;">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />
                        </svg>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">System Monitoring</h3>
                        <p class="card-description">
                            View performance metrics and real-time system health status.
                        </p>

                        <ul class="card-features">
                            <li>CPU & memory usage</li>
                            <li>Live service uptime</li>
                            <li>Error tracking</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-coming-soon">Coming Soon</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>

                <!-- System Documentation Card -->
                <a href="<?php echo e(route('settings.documentation.index')); ?>" class="setting-card">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>

                    </div>

                    <div class="card-content">
                        <h3 class="card-title">System Documentation</h3>
                        <p class="card-description">
                            Access all technical and user documentation in one place.
                        </p>

                        <ul class="card-features">
                            <li>API documentation</li>
                            <li>Setup guides</li>
                            <li>GitHub & sitemap</li>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <span class="card-status status-active">Active</span>
                        <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

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
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/settings/index.blade.php ENDPATH**/ ?>