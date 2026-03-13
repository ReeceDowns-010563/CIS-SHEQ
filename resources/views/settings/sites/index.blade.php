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

            .create-site-btn {
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

            .create-site-btn:hover {
                background-color: var(--secondary-colour);
                border-color: var(--secondary-colour);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-site-btn:active {
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

                .create-site-btn {
                    width: 100%;
                    justify-content: center;
                    padding: 0.875rem;
                }
            }

            @media (max-width: 480px) {
                .header-title {
                    font-size: 0.95rem;
                }

                .create-site-btn {
                    font-size: 0.8rem;
                    padding: 0.75rem;
                }
            }
        </style>
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.index') }}" class="back-button" title="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Manage Sites</h2>
            </div>
            <a href="{{ route('settings.sites.create') }}" class="create-site-btn">
                Add New Site
            </a>
            <a href="{{ route('settings.branches.index') }}" class="create-site-btn">
                Manage Branch
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

        /* Filter select elements */
        .customer-select, .status-select, .branch-select {
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

        .customer-select:focus, .status-select:focus, .branch-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .customer-select:hover, .status-select:hover, .branch-select:hover {
            border-color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .customer-select, .status-select, .branch-select {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .customer-select:focus, .status-select:focus, .branch-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .customer-select:hover, .status-select:hover, .branch-select:hover {
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
        .sites-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .sites-table th {
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
            .sites-table th {
                background-color: #374151;
                color: #d1d5db;
                border-bottom-color: #4b5563;
            }
        }

        .sites-table th a {
            color: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: color 0.15s ease-in-out;
        }

        .sites-table th a:hover {
            color: var(--primary-colour);
        }

        .sites-table td {
            padding: 0.875rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }

        @media (prefers-color-scheme: dark) {
            .sites-table td {
                color: #d1d5db;
                border-bottom-color: #374151;
            }
        }

        .sites-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .sites-table tbody tr:last-child td {
            border-bottom: none;
        }

        .sites-table tbody tr:hover {
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            .sites-table tbody tr:hover {
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

        .status-inactive {
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

            .status-inactive {
                background-color: rgba(239, 68, 68, 0.2);
                color: #f87171;
                border-color: #ef4444;
            }
        }

        /* Branch code styling */
        .branch-code {
            font-family: monospace;
            background-color: #f3f4f6;
            padding: 0.125rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        @media (prefers-color-scheme: dark) {
            .branch-code {
                background-color: #374151;
                color: #d1d5db;
                border-color: #4b5563;
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

        /* Site ID styling */
        .site-id {
            color: #6b7280;
            font-size: 0.75rem;
            font-weight: 500;
        }

        @media (prefers-color-scheme: dark) {
            .site-id {
                color: #9ca3af;
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

            .sites-table th,
            .sites-table td {
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

            .sites-table th,
            .sites-table td {
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

            .sites-table {
                min-width: 900px;
            }

            .sites-table th:last-child,
            .sites-table td:last-child {
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

            .sites-table th,
            .sites-table td {
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
            <!-- Filters and Search -->
            <div class="filters-container">
                <form method="GET" action="{{ route('settings.sites.index') }}" class="filters-form">
                    <div class="filter-row">
                        <!-- Search -->
                        <div class="filter-group">
                            <label class="filter-label">Search Sites</label>
                            <div class="search-input-group">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search by site name, address, or ID"
                                    class="search-input"
                                />
                                <button type="submit" class="search-button">Search</button>
                            </div>
                        </div>

                        <!-- Customer Filter -->
                        <div class="filter-group">
                            <label class="filter-label">Filter by Customer</label>
                            <select name="customer_id" class="customer-select" onchange="this.form.submit()">
                                <option value="">All Customers</option>
                                @foreach($customers ?? [] as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Branch Filter -->
                        <div class="filter-group">
                            <label class="filter-label">Filter by Branch</label>
                            <select name="branch_code" class="branch-select" onchange="this.form.submit()">
                                <option value="">All Branches</option>
                                @foreach($branches ?? [] as $branch)
                                    <option value="{{ $branch->branch_code }}" {{ request('branch_code') == $branch->branch_code ? 'selected' : '' }}>
                                        {{ $branch->branch_code }} - {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="filter-group">
                            <label class="filter-label">Filter by Status</label>
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    @if(request('search') || request('customer_id') || request('branch_code') || request('status'))
                        <div class="filter-group">
                            <a href="{{ route('settings.sites.index') }}" class="clear-filters-btn">
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Results Summary -->
            @if(isset($sites) && $sites->total() > 0)
                <div class="results-summary">
                    Showing {{ $sites->firstItem() }} to {{ $sites->lastItem() }} of {{ $sites->total() }} sites
                    @if(request('search'))
                        for "{{ request('search') }}"
                    @endif
                    @if(request('customer_id'))
                        for customer "{{ $customers->find(request('customer_id'))->name ?? 'Unknown' }}"
                    @endif
                    @if(request('branch_code'))
                        for branch "{{ $branches->where('branch_code', request('branch_code'))->first()->branch_name ?? 'Unknown' }}"
                    @endif
                    @if(request('status'))
                        with status "{{ ucfirst(request('status')) }}"
                    @endif
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-500/10 border border-green-400 dark:border-green-500 text-green-700 dark:text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Sites Table -->
            <div class="table-container">
                <table class="sites-table">
                    <thead>
                    <tr>
                        <th>
                            <a href="{{ route('settings.sites.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                Site Name
                                @if(request('sort') === 'name')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(request('direction') === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('settings.sites.index', array_merge(request()->query(), ['sort' => 'customer_id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                Customer
                                @if(request('sort') === 'customer_id')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(request('direction') === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('settings.sites.index', array_merge(request()->query(), ['sort' => 'branch_code', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                Branch
                                @if(request('sort') === 'branch_code')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(request('direction') === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>
                            <a href="{{ route('settings.sites.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                Created
                                @if(request('sort') === 'created_at')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(request('direction') === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sites ?? [] as $site)
                        <tr>
                            <td>
                                <div>
                                    <div style="font-weight: 600;">{{ $site->name }}</div>
                                    @if($site->site_code)
                                        <div class="site-id">Code: {{ $site->site_code }}</div>
                                    @else
                                        <div class="site-id">ID: {{ $site->id }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $site->customer->name ?? 'N/A' }}</td>
                            <td>
                                @if($site->branch)
                                    <span class="branch-code">{{ $site->branch->branch_code }}</span>
                                @else
                                    <span style="color: #9ca3af;">—</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @if($site->address)
                                        <div>{{ $site->address }}</div>
                                        @if($site->city || $site->county || $site->postal_code)
                                            <div style="font-size: 0.75rem; color: #6b7280;">
                                                {{ collect([$site->city, $site->county, $site->postal_code])->filter()->join(', ') }}
                                            </div>
                                        @endif
                                    @else
                                        <span style="color: #9ca3af;">—</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($site->contact_name || $site->contact_phone)
                                    <div>
                                        @if($site->contact_name)
                                            <div style="font-weight: 500;">{{ $site->contact_name }}</div>
                                        @endif
                                        @if($site->contact_phone)
                                            <div style="font-size: 0.75rem; color: #6b7280;">{{ $site->contact_phone }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span style="color: #9ca3af;">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $site->active ? 'status-active' : 'status-inactive' }}">
                                    {{ $site->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $site->created_at->format('M j, Y') }}</td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('settings.sites.edit', $site) }}" class="edit-link">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('settings.sites.destroy', $site) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this site? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-message">
                                @if(request('search') || request('customer_id') || request('branch_code') || request('status'))
                                    <div style="text-align: center;">
                                        <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">No sites found</h3>
                                        <p style="color: #6b7280; margin-bottom: 1rem; font-size: 0.875rem;">
                                            No sites match your current search criteria.
                                        </p>
                                        <a href="{{ route('settings.sites.index') }}" class="clear-filters-btn" style="display: inline-block; width: auto; max-width: 200px;">
                                            Clear Filters
                                        </a>
                                    </div>
                                @else
                                    <div style="text-align: center;">
                                        <svg style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">No sites found</h3>
                                        <p style="color: #6b7280; margin-bottom: 1.5rem; font-size: 0.875rem;">Get started by adding your first site.</p>
                                        <a href="{{ route('settings.sites.create') }}" class="create-site-btn" style="display: inline-block; width: auto; max-width: 200px;">
                                            Add New Site
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($sites) && $sites->hasPages())
                <div class="pagination-container">
                    {{ $sites->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
