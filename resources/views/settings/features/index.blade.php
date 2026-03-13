<x-app-layout>
    <x-slot name="header">
        <style>
            :root {
                --brand: var(--primary-colour);
                --brand-dark: var(--secondary-colour);
                --radius: 0.5rem;
                --transition: 0.15s ease-in-out;
            }

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
                flex: 1;
                min-width: 0;
            }

            .back-button {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem;
                color: #6b7280;
                border: 1px solid #d1d5db;
                border-radius: var(--radius);
                text-decoration: none;
                transition: var(--transition);
                background-color: white;
                flex-shrink: 0;
            }

            .back-button:hover {
                color: var(--brand);
                border-color: var(--brand);
                background-color: #fef7f0;
                transform: translateX(-2px);
            }

            .back-button:focus-visible {
                outline: 2px solid #2563eb;
                outline-offset: 2px;
            }

            @media (prefers-color-scheme: dark) {
                .back-button {
                    color: #9ca3af;
                    border-color: #4b5563;
                    background-color: #1f2937;
                }

                .back-button:hover {
                    color: var(--brand);
                    border-color: var(--brand);
                    background-color: rgba(167, 98, 44, 0.1);
                }
            }

            .header-info {
                min-width: 0;
                flex: 1;
            }

            .header-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0;
                word-break: break-word;
            }

            .header-description {
                margin-top: 0.25rem;
                font-size: 0.875rem;
                color: #4b5563;
                line-height: 1.4;
            }

            @media (prefers-color-scheme: dark) {
                .header-title {
                    color: #d1d5db;
                }
                .header-description {
                    color: #9ca3af;
                }
            }

            .action-buttons {
                display: flex;
                gap: 0.5rem;
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 2px;
            }

            .action-buttons::-webkit-scrollbar {
                height: 4px;
            }

            .action-buttons::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.1);
                border-radius: 2px;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.25rem;
                font-size: 0.875rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-radius: var(--radius);
                text-decoration: none;
                cursor: pointer;
                transition: var(--transition);
                border: 2px solid transparent;
                white-space: nowrap;
                flex: 0 0 auto;
                justify-content: center;
                min-width: fit-content;
            }

            .btn:focus-visible {
                outline: 2px solid #2563eb;
                outline-offset: 2px;
            }

            .btn-primary {
                background-color: var(--brand);
                color: #fff;
                border-color: var(--brand);
            }

            .btn-primary:hover {
                background-color: var(--brand-dark);
                border-color: var(--brand-dark);
                transform: translateY(-1px);
                box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.15);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .btn-secondary {
                background: transparent;
                color: var(--brand);
                border-color: var(--brand);
            }

            .btn-secondary:hover {
                background: rgba(167, 98, 44, 0.1);
                color: var(--brand-dark);
                border-color: var(--brand-dark);
                transform: translateY(-1px);
            }

            @media (prefers-color-scheme: dark) {
                .btn-secondary {
                    color: var(--brand);
                    border-color: var(--brand);
                }

                .btn-secondary:hover {
                    background: rgba(167, 98, 44, 0.15);
                }
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

                .header-description {
                    font-size: 0.8rem;
                }

                .action-buttons {
                    width: 100%;
                    justify-content: flex-start;
                    gap: 0.75rem;
                }

                .btn {
                    padding: 0.75rem 1rem;
                    font-size: 0.8rem;
                }
            }

            @media (max-width: 480px) {
                .header-title {
                    font-size: 0.95rem;
                }

                .header-description {
                    font-size: 0.75rem;
                }

                .btn {
                    padding: 0.625rem 0.875rem;
                    font-size: 0.75rem;
                }
            }
        </style>

        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.index') }}" class="back-button" title="Back to Settings" aria-label="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="header-info">
                    <h2 class="header-title">Feature Access Control</h2>
                    <p class="header-description">Define which roles can access each named feature (e.g., settings, complaints).</p>
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('settings.features.create') }}" class="btn btn-primary">
                    New Feature
                </a>
                <a href="{{ route('settings.roles.index') }}" class="btn btn-primary">
                    Manage Roles
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        /* Main container - Mobile responsive */
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

        .table-container {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-top: 1rem;
        }

        @media (prefers-color-scheme: dark) {
            .table-container {
                background-color: #1f2937;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        .features-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .features-table th {
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
            .features-table th {
                background-color: #374151;
                color: #d1d5db;
                border-bottom-color: #4b5563;
            }
        }

        .features-table td {
            padding: 0.875rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        @media (prefers-color-scheme: dark) {
            .features-table td {
                color: #d1d5db;
                border-bottom-color: #374151;
            }
        }

        .features-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .features-table tbody tr:last-child td {
            border-bottom: none;
        }

        .features-table tbody tr:hover {
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            .features-table tbody tr:hover {
                background-color: #374151;
            }
        }

        /* Mobile responsive action links */
        .action-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .edit-link {
            color: #2563eb;
            border: 1px solid transparent;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.15s ease-in-out;
            text-align: center;
            font-size: 0.8rem;
        }

        .edit-link:hover {
            color: #1d4ed8;
            background-color: #eff6ff;
            border-color: #bfdbfe;
        }

        .delete-btn {
            background: none;
            color: #dc2626;
            border: 1px solid transparent;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-align: center;
            font-size: 0.8rem;
            width: 100%;
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

        /* Role badges - Mobile optimized */
        .roles-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: capitalize;
            white-space: nowrap;
            line-height: 1;
        }

        .role-admin {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .role-basic {
            background-color: #e0e7ff;
            color: #3730a3;
            border: 1px solid #6366f1;
        }

        .role-user {
            background-color: #f0f9ff;
            color: #075985;
            border: 1px solid #0ea5e9;
        }

        @media (prefers-color-scheme: dark) {
            .role-admin {
                background-color: rgba(251, 191, 36, 0.2);
                color: #fbbf24;
                border-color: #f59e0b;
            }

            .role-basic {
                background-color: rgba(99, 102, 241, 0.2);
                color: #a5b4fc;
                border-color: #6366f1;
            }

            .role-user {
                background-color: rgba(14, 165, 233, 0.2);
                color: #7dd3fc;
                border-color: #0ea5e9;
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

        .create-feature-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: white;
            background-color: var(--brand);
            border: 2px solid var(--brand);
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .create-feature-btn:hover {
            background-color: var(--brand-dark);
            border-color: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        /* Feature key styling */
        .feature-key {
            font-family: 'Courier New', monospace;
            background-color: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        @media (prefers-color-scheme: dark) {
            .feature-key {
                background-color: #374151;
                color: #d1d5db;
                border-color: #4b5563;
            }
        }

        /* Desktop responsive adjustments */
        @media (min-width: 768px) {
            .main-container {
                padding: 2rem 1rem;
            }

            .table-container {
                margin-top: 1.5rem;
            }

            .features-table th,
            .features-table td {
                padding: 1rem 1.25rem;
            }

            .action-links {
                flex-direction: row;
                gap: 0.75rem;
            }

            .edit-link,
            .delete-btn {
                width: auto;
                font-size: 0.875rem;
            }

            .role-badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.75rem;
            }
        }

        @media (min-width: 1024px) {
            .main-container {
                padding: 2.5rem 1rem;
            }

            .features-table th,
            .features-table td {
                padding: 1.25rem 1.5rem;
            }
        }

        /* Mobile table scroll */
        @media (max-width: 767px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .features-table {
                min-width: 700px;
            }

            .features-table th:last-child,
            .features-table td:last-child {
                padding-right: 1rem;
                min-width: 120px;
            }
        }

        /* Very small mobile adjustments */
        @media (max-width: 375px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .features-table th,
            .features-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }

            .role-badge {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }

            .feature-key {
                font-size: 0.75rem;
                padding: 0.2rem 0.4rem;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-500/10 border border-green-400 dark:border-green-500 text-green-700 dark:text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-container">
                <table class="features-table">
                    <thead>
                    <tr>
                        <th>Key</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Allowed Roles</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($features as $feature)
                        <tr>
                            <td>
                                <span class="feature-key">{{ $feature->key }}</span>
                            </td>
                            <td style="font-weight: 600;">{{ $feature->name }}</td>
                            <td>{{ $feature->description ?? '—' }}</td>
                            <td>
                                @if($feature->roles->isEmpty())
                                    <span style="color: #6b7280; font-style: italic;">None</span>
                                @else
                                    <div class="roles-container">
                                        @foreach($feature->roles as $role)
                                            <span class="role-badge role-{{ strtolower($role->name) }}" data-role-name="{{ strtolower($role->name) }}">
                                                {{ ucfirst($role->display_name ?? $role->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ $feature->updated_at?->diffForHumans() ?? $feature->created_at?->diffForHumans() ?? '—' }}
                            </td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('settings.features.edit', $feature) }}" class="edit-link">Edit</a>
                                    <form method="POST" action="{{ route('settings.features.destroy', $feature) }}" style="display: inline;" onsubmit="return confirm('Delete this feature?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-message">
                                <div style="text-align: center;">
                                    <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">No features defined</h3>
                                    <p style="color: #6b7280; margin-bottom: 1.5rem; font-size: 0.875rem;">
                                        Create features like <strong>settings</strong> or <strong>complaints</strong> and assign roles to them.
                                    </p>
                                    <a href="{{ route('settings.features.create') }}" class="create-feature-btn">
                                        Add Feature
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($features) && $features->hasPages())
                <div class="pagination-container">
                    {{ $features->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to generate consistent color from string
            function stringToColor(str) {
                let hash = 0;
                for (let i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                }

                // Generate HSL color with good saturation and lightness for readability
                const hue = Math.abs(hash) % 360;
                const saturation = 65 + (Math.abs(hash) % 20); // 65-85%
                const lightness = 85 + (Math.abs(hash) % 10);  // 85-95%

                return {
                    background: `hsl(${hue}, ${saturation}%, ${lightness}%)`,
                    text: `hsl(${hue}, ${saturation}%, 25%)`,
                    border: `hsl(${hue}, ${saturation}%, 55%)`
                };
            }

            // Function to generate dark mode colors
            function stringToColorDark(str) {
                let hash = 0;
                for (let i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                }

                const hue = Math.abs(hash) % 360;
                const saturation = 55 + (Math.abs(hash) % 15); // 55-70%
                const lightness = 15 + (Math.abs(hash) % 10);  // 15-25%

                return {
                    background: `hsl(${hue}, ${saturation}%, ${lightness}%)`,
                    text: `hsl(${hue}, ${saturation - 10}%, 75%)`,
                    border: `hsl(${hue}, ${saturation}%, 45%)`
                };
            }

            // Apply colors to role badges that don't have predefined styles
            const roleBadges = document.querySelectorAll('.role-badge');
            const predefinedRoles = ['admin', 'basic', 'user'];
            const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

            roleBadges.forEach(badge => {
                const roleName = badge.getAttribute('data-role-name');

                // Only apply custom colors to roles that don't have predefined CSS classes
                if (!predefinedRoles.includes(roleName)) {
                    const colors = isDarkMode ? stringToColorDark(roleName) : stringToColor(roleName);

                    badge.style.backgroundColor = colors.background;
                    badge.style.color = colors.text;
                    badge.style.borderColor = colors.border;
                }
            });

            // Handle dark mode changes
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    const isNowDark = e.matches;

                    roleBadges.forEach(badge => {
                        const roleName = badge.getAttribute('data-role-name');

                        if (!predefinedRoles.includes(roleName)) {
                            const colors = isNowDark ? stringToColorDark(roleName) : stringToColor(roleName);

                            badge.style.backgroundColor = colors.background;
                            badge.style.color = colors.text;
                            badge.style.borderColor = colors.border;
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>
