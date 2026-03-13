@php use Illuminate\Support\Str; @endphp

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

            .create-guide-btn {
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
            }

            .create-guide-btn:hover {
                background-color: var(--secondary-colour);
                border-color: var(--secondary-colour);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-guide-btn:active {
                transform: translateY(0);
            }

            @media (max-width: 768px) {
                .header-container {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .header-left {
                    width: 100%;
                }
            }
        </style>
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.documentation.index') }}" class="back-button" title="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Manage Guides</h2>
            </div>
            <a href="{{ route('settings.guides.create') }}" class="create-guide-btn">
                Create Guide
            </a>
        </div>
    </x-slot>

    <style>
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

        /* Filter and search container */
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
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

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            min-width: 200px;
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

        .search-input-group {
            display: flex;
            flex: 1;
            min-width: 300px;
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
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-colour);
            border: none;
            color: white;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: background-color 0.15s ease-in-out;
        }

        .search-button:hover {
            background-color: var(--secondary-colour);
        }

        .visibility-select {
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            color: #111827;
            font-size: 1rem;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            min-width: 150px;
        }

        .visibility-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .visibility-select:hover {
            border-color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .visibility-select {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .visibility-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .visibility-select:hover {
                border-color: #6b7280;
            }
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

        /* Table container */
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

        .guides-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .guides-table th {
            padding: 1.25rem 1.5rem;
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
            .guides-table th {
                background-color: #374151;
                color: #d1d5db;
                border-bottom-color: #4b5563;
            }
        }

        .guides-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }

        @media (prefers-color-scheme: dark) {
            .guides-table td {
                color: #d1d5db;
                border-bottom-color: #374151;
            }
        }

        .guides-table tbody tr:hover {
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            .guides-table tbody tr:hover {
                background-color: #374151;
            }
        }

        /* Visibility badge */
        .visibility-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: capitalize;
        }

        .visibility-public {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .visibility-private {
            background-color: #fffbeb;
            color: #78350f;
            border: 1px solid #f59e0b;
        }

        @media (prefers-color-scheme: dark) {
            .visibility-public {
                background-color: rgba(16, 185, 129, 0.2);
                color: #6ee7b7;
                border-color: #10b981;
            }
            .visibility-private {
                background-color: rgba(245, 158, 11, 0.2);
                color: #fcd34d;
                border-color: #f59e0b;
            }
        }

        /* Action buttons */
        .action-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .action-links a, .action-links button {
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            padding: 0.4rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
            border: none;
            cursor: pointer;
            background: none;
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

        .copy-btn {
            background: #f3f4f6;
            padding: 0.35rem 0.6rem;
            border-radius: 0.375rem;
            font-size: 0.65rem;
            border: 1px solid #d1d5db;
        }

        .copy-btn:hover {
            background: #e2e8f0;
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

            .copy-btn {
                background: #1f2937;
                border-color: #374151;
                color: #d1d5db;
            }
        }

        .empty-message {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
            font-size: 1rem;
        }

        @media (prefers-color-scheme: dark) {
            .empty-message {
                color: #9ca3af;
            }
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .results-summary {
            background-color: white;
            padding: 1rem 1.5rem;
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

        /* Responsive */
        @media (max-width: 1024px) {
            .filters-container {
                flex-direction: column;
            }

            .filter-group {
                min-width: auto;
            }

            .search-input-group {
                min-width: auto;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1.5rem 0.5rem;
            }

            .filters-container {
                padding: 1rem;
            }

            .guides-table th,
            .guides-table td {
                padding: 0.75rem 1rem;
            }

            .action-links {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        @media (max-width: 640px) {
            .guides-table th,
            .guides-table td {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }

            .filter-group {
                width: 100%;
            }

            .visibility-select {
                min-width: auto;
                width: 100%;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            <!-- Filters and Search -->
            <div class="filters-container">
                <form method="GET" action="{{ route('settings.guides.index') }}" style="display: flex; flex-wrap: wrap; gap: 1rem; width: 100%;">
                    <!-- Search -->
                    <div class="filter-group" style="flex: 1;">
                        <label class="filter-label">Search Guides</label>
                        <div class="search-input-group">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by title or description"
                                class="search-input"
                            />
                            <button type="submit" class="search-button">Search</button>
                        </div>
                    </div>

                    <!-- Visibility Filter -->
                    <div class="filter-group">
                        <label class="filter-label">Visibility</label>
                        <select name="visibility" class="visibility-select" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="public" {{ request('visibility') === 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ request('visibility') === 'private' ? 'selected' : '' }}>Private</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    @if(request('search') || request('visibility'))
                        <div class="filter-group">
                            <label class="filter-label" style="opacity: 0;">Clear</label>
                            <a href="{{ route('settings.guides.index') }}" class="clear-filters-btn">
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Results Summary -->
            @if(isset($guides) && $guides->total() > 0)
                <div class="results-summary">
                    Showing {{ $guides->firstItem() }} to {{ $guides->lastItem() }} of {{ $guides->total() }} guides
                    @if(request('search'))
                        for "{{ request('search') }}"
                    @endif
                    @if(request('visibility'))
                        with visibility "{{ ucfirst(request('visibility')) }}"
                    @endif
                </div>
            @endif

            <!-- Guides Table -->
            <div class="table-container">
                <table class="guides-table">
                    <thead>
                    <tr>
                        <th>
                            <a href="{{ route('settings.guides.index', array_merge(request()->query(), ['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                Title
                                @if(request('sort') === 'title')
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
                        <th>Visibility</th>
                        <th>
                            <a href="{{ route('settings.guides.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
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
                        <th>Share Link</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($guides as $guide)
                        <tr>
                            <td>
                                <div>
                                    <div class="font-semibold">
                                        <a href="{{ $guide->is_public
                                                    ? route('guides.show', $guide->uuid)
                                                    : ($guide->share_token
                                                        ? route('guides.shared', ['uuid' => $guide->uuid, 'token' => $guide->share_token])
                                                        : '#') }}"
                                           target="_blank" rel="noopener">
                                            {{ $guide->title }}
                                        </a>
                                    </div>
                                    @if($guide->description)
                                        <div class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ Str::limit($guide->description, 60) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($guide->is_public)
                                    <span class="visibility-badge visibility-public">Public</span>
                                @else
                                    <span class="visibility-badge visibility-private">Private</span>
                                @endif
                            </td>
                            <td>{{ $guide->created_at->format('M j, Y') }}</td>
                            <td>
                                <div style="display:flex; gap:6px; flex-wrap: wrap; align-items: center;">
                                    @if($guide->is_public)
                                        <input type="text" readonly value="{{ $guide->getPublicUrl() }}" class="copy-input" style="flex:1; padding:4px 8px; border:1px solid #d1d5db; border-radius:4px; font-size:0.65rem;" id="public-link-{{ $guide->id }}">
                                        <button type="button" class="copy-btn" onclick="navigator.clipboard.writeText('{{ $guide->getPublicUrl() }}')">Copy</button>
                                    @else
                                        @if($guide->share_token)
                                            <input type="text" readonly value="{{ $guide->getSharedUrl() }}" class="copy-input" style="flex:1; padding:4px 8px; border:1px solid #d1d5db; border-radius:4px; font-size:0.65rem;" id="shared-link-{{ $guide->id }}">
                                            <button type="button" class="copy-btn" onclick="navigator.clipboard.writeText('{{ $guide->getSharedUrl() }}')">Copy</button>
                                        @else
                                            <span class="text-xs text-gray-500">No link</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('settings.guides.edit', $guide) }}" class="edit-link">
                                        Edit
                                    </a>

                                    @if(! $guide->is_public)
                                        @if($guide->share_token)
                                            <form method="POST" action="{{ route('settings.guides.revokeShareToken', $guide) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="delete-btn">Revoke Link</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('settings.guides.regenerateShareToken', $guide) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="edit-link">Generate Link</button>
                                            </form>
                                        @endif
                                    @endif

                                    <form method="POST" action="{{ route('settings.guides.destroy', $guide) }}" class="inline" onsubmit="return confirm('Delete this guide? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-message">
                                @if(request('search') || request('visibility'))
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No guides found</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                                            No guides match your current filters.
                                        </p>
                                        <a href="{{ route('settings.guides.index') }}" class="clear-filters-btn">
                                            Clear Filters
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No guides yet</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-6">Start by creating a new guide.</p>
                                        <a href="{{ route('settings.guides.create') }}" class="create-guide-btn">
                                            Create Guide
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
            @if(isset($guides) && $guides->hasPages())
                <div class="pagination-container">
                    {{ $guides->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
