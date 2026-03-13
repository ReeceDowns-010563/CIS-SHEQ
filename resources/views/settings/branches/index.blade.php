<x-app-layout>
    <x-slot name="header">
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

            .create-branch-btn {
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

            .create-branch-btn:hover {
                background-color: var(--secondary-colour);
                border-color: var(--secondary-colour);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-branch-btn:active {
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

                .create-branch-btn {
                    width: 100%;
                    justify-content: center;
                    padding: 0.875rem;
                }
            }

            @media (max-width: 480px) {
                .header-title {
                    font-size: 0.95rem;
                }

                .create-branch-btn {
                    font-size: 0.8rem;
                    padding: 0.75rem;
                }
            }
        </style>
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.sites.index') }}" class="back-button" title="Back to Sites">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Manage Branches</h2>
            </div>
            <a href="{{ route('settings.branches.create') }}" class="create-branch-btn">
                Add New Branch
            </a>
        </div>
    </x-slot>

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
        .branches-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .branches-table th {
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
            .branches-table th {
                background-color: #374151;
                color: #d1d5db;
                border-bottom-color: #4b5563;
            }
        }

        .branches-table td {
            padding: 0.875rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }

        @media (prefers-color-scheme: dark) {
            .branches-table td {
                color: #d1d5db;
                border-bottom-color: #374151;
            }
        }

        .branches-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .branches-table tbody tr:last-child td {
            border-bottom: none;
        }

        .branches-table tbody tr:hover {
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            .branches-table tbody tr:hover {
                background-color: #374151;
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

            .clear-filters-btn {
                width: auto;
                min-width: 120px;
            }

            .branches-table th,
            .branches-table td {
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

            .branches-table th,
            .branches-table td {
                padding: 1.25rem 1.5rem;
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
                            <label for="search" class="filter-label">Search Branches</label>
                            <div class="search-input-group">
                                <input type="text"
                                       name="search"
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by branch code or name..."
                                       class="search-input">
                                <button type="submit" class="search-button">Search</button>
                            </div>
                        </div>
                    </div>

                    @if(request('search'))
                        <a href="{{ route('settings.branches.index') }}" class="clear-filters-btn">Clear Filters</a>
                    @endif
                </form>
            </div>

            <!-- Branches Table -->
            <div class="table-container">
                <table class="branches-table">
                    <thead>
                    <tr>
                        <th>Branch Code</th>
                        <th>Branch Name</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($branches as $branch)
                        <tr>
                            <td>
                                    <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-sm">
                                        {{ $branch->branch_code }}
                                    </span>
                            </td>
                            <td class="font-medium">{{ $branch->branch_name }}</td>
                            <td>{{ $branch->created_at->format('M j, Y') }}</td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('settings.branches.edit', $branch) }}" class="edit-link">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('settings.branches.destroy', $branch) }}"
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this branch?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-message">
                                No branches found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                {{ $branches->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
