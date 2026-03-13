<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">My Investigations</h2>
    </x-slot>

    <!-- Make sure CSRF token meta tag is in your <head> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .search-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            position: relative;
        }

        @media (max-width: 768px) {
            .search-container {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
        }

        .search-input-group {
            display: flex;
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            overflow: hidden;
            background: #fff;
        }
        .dark .search-input-group {
            border-color: #4b5563;
            background: #374151;
        }
        .search-input {
            flex: 1;
            padding: .5rem 1rem;
            border: none;
            outline: none;
            color: #111827;
            background: #fff;
            font-size: 1rem;
        }
        .dark .search-input {
            background: #374151;
            color: #e5e7eb;
        }
        .search-input::placeholder {
            color: #9ca3af;
        }
        .dark .search-input::placeholder {
            color: #6b7280;
        }
        .search-button {
            padding: .5rem 1rem;
            background-color: var(--primary-colour);
            border: none;
            color: #fff;
            cursor: pointer;
            transition: opacity 0.2s ease;
            border-top-right-radius: .5rem;
            border-bottom-right-radius: .5rem;
        }
        .search-button:hover {
            opacity: 0.9;
        }
        .filter-btn {
            padding: .5rem;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            cursor: pointer;
            color: #374151;
        }
        .dark .filter-btn {
            background: #1f2937;
            border-color: #4b5563;
            color: #e5e7eb;
        }
        .filter-btn:hover {
            background: #e5e7eb;
        }
        .dark .filter-btn:hover {
            background: #374151;
        }
        .filter-dropdown {
            position: fixed;
            top: auto;
            right: auto;
            margin-top: .5rem;
            width: 15rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,.15);
            z-index: 1000;
            padding: 0.75rem;
            max-height: 400px;
            overflow-y: auto;
        }
        .dark .filter-dropdown {
            background: #1f2937;
            border-color: #4b5563;
            box-shadow: 0 10px 25px rgba(0,0,0,.4);
        }
        .filter-dropdown label {
            color: #374151;
            font-size: .875rem;
        }
        .dark .filter-dropdown label {
            color: #d1d5db;
        }
        .filter-dropdown select {
            width: 100%;
            padding: .5rem;
            margin-top: .5rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #111827;
            border-radius: .5rem;
            font-size: .875rem;
        }
        .dark .filter-dropdown select {
            background: #374151;
            border-color: #4b5563;
            color: #e5e7eb;
        }
        .filter-dropdown .apply-btn {
            width: 100%;
            padding: .5rem;
            margin-top: .5rem;
            background: var(--primary-colour);
            color: #fff;
            border: none;
            border-radius: .5rem;
            cursor: pointer;
            font-size: .875rem;
            transition: opacity 0.2s ease;
        }
        .filter-dropdown .apply-btn:hover {
            opacity: 0.9;
        }

        /* Table row hover effects */
        .table-row-hover:hover {
            background-color: rgba(243, 244, 246, 0.5);
        }
        .dark .table-row-hover:hover {
            background-color: rgba(55, 65, 81, 0.5);
        }
        .archived-row {
            background-color: rgba(229, 231, 235, 0.3);
        }
        .dark .archived-row {
            background-color: rgba(55, 65, 81, 0.3);
        }

        /* Extended table wrapper */
        .table-wrapper {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #6e6e6e;
            overflow-x: auto;
            width: 100%;
        }
        .dark .table-wrapper {
            border-color: #4b5563;
        }

        table {
            width: 100%;
            min-width: 1200px; /* Extended width for better spacing */
        }

        /* Mobile responsive table */
        @media (max-width: 1024px) {
            table {
                min-width: 1000px;
            }
        }

        @media (max-width: 768px) {
            table {
                min-width: 800px;
            }

            .table-wrapper {
                border-radius: 8px;
            }
        }

        /* Status dropdown with ROUNDED CORNERS and NO WHITE BACKGROUND - FIXED Z-INDEX */
        .clean-select {
            position: relative;
            display: inline-block;
            min-width: 120px;
        }

        .clean-select-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 8px 12px;
            border: 2px solid transparent;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            min-height: 36px;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .clean-select-button:hover {
            opacity: 0.8;
        }

        .clean-select.open .clean-select-button {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            z-index: 50001;
        }

        .clean-select-arrow {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
            color: currentColor;
            flex-shrink: 0;
        }

        .clean-select.open .clean-select-arrow {
            transform: rotate(180deg);
        }

        .clean-select-dropdown {
            position: fixed;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2), 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 50000;
            max-height: 200px;
            overflow-y: auto;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-4px);
            transition: all 0.15s ease;
            min-width: 120px;
        }

        .dark .clean-select-dropdown {
            background: #1f2937;
            border-color: #4b5563;
        }

        .clean-select.open .clean-select-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .clean-select-option {
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .dark .clean-select-option {
            color: #e5e7eb;
        }

        .clean-select-option:first-child {
            border-radius: 11px 11px 0 0;
        }

        .clean-select-option:last-child {
            border-radius: 0 0 11px 11px;
        }

        .clean-select-option:hover {
            background: #f1f5f9;
        }

        .dark .clean-select-option:hover {
            background: #374151;
        }

        .clean-select-option.selected {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .dark .clean-select-option.selected {
            background: #1e40af;
            color: #bfdbfe;
        }

        /* Status Colors - these now style the button directly */
        .status-open .clean-select-button {
            color: #059669;
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .status-closed .clean-select-button {
            color: #dc2626;
            background: #fef2f2;
            border-color: #fca5a5;
        }

        /* Comments Button Styles */
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

        .comments-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .comments-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comments-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .comments-text {
            font-weight: 500;
        }

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

        .comments-count:empty,
        .comments-count[data-count="0"] {
            display: none;
        }

        /* Mobile responsive comments button */
        @media (max-width: 768px) {
            .comments-btn {
                padding: 6px 12px;
                font-size: 13px;
                min-width: 110px;
            }

            .comments-icon {
                width: 16px;
                height: 16px;
            }
        }

        /* Modal Styles */
        .comments-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
        }

        .comments-modal-content {
            position: relative;
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: modalSlideIn 0.3s ease;
        }

        @media (max-width: 768px) {
            .comments-modal-content {
                width: 95%;
                margin: 10% auto;
                max-height: 85vh;
            }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .comments-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .comments-modal-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .comments-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .comments-close:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .comments-modal-body {
            padding: 24px;
            max-height: 50vh;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .comments-modal-body {
                padding: 16px;
                max-height: 60vh;
            }
        }

        .comments-list {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .comment-item {
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 12px;
            background: #f9fafb;
            position: relative;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .comment-author {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .comment-date {
            font-size: 12px;
            color: #6b7280;
        }

        .comment-text {
            color: #374151;
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .comment-actions {
            display: flex;
            gap: 8px;
        }

        .comment-delete {
            background: #ef4444;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .comment-delete:hover {
            background: #dc2626;
        }

        .comment-form {
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .comment-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .comment-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .comment-submit {
            margin-top: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .comment-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .comment-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .no-comments {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 40px 20px;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .table-wrapper {
                margin-left: -1.5rem;
                margin-right: -1.5rem;
                border-radius: 0;
                border-left: none;
                border-right: none;
            }

            th, td {
                padding: 8px 4px;
                font-size: 12px;
            }

            .clean-select {
                min-width: 100px;
            }

            .clean-select-button {
                padding: 6px 8px;
                font-size: 12px;
                min-height: 32px;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Search and Filter Section -->
                    <div class="search-container">
                        <form method="GET" action="{{ route('complaints.my-investigations') }}" class="search-input-group">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search by name or PCN number..." class="search-input">
                            <!-- Hidden fields to preserve other filter states -->
                            <input type="hidden" name="site_id" value="{{ request('site_id') }}">
                            <input type="hidden" name="archived" value="{{ request('archived') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                            <button type="submit" class="search-button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>

                        <div style="position: relative;">
                            <button id="filterButton" class="filter-btn" onclick="toggleFilters(event)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </button>

                            <div id="filterDropdown" class="filter-dropdown" style="display: none;">
                                <form method="GET" action="{{ route('complaints.my-investigations') }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">

                                    <label for="filter_site">Site</label>
                                    <select name="site_id" id="filter_site">
                                        <option value="">All Sites</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>
                                                {{ $site->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if(auth()->user()->role_id === 1)
                                        <label for="filter_archived" style="margin-top: 1rem; display: block;">Show Archived</label>
                                        <select name="archived" id="filter_archived">
                                            <option value="0" {{ request('archived') == '0' || !request('archived') ? 'selected' : '' }}>Active Only</option>
                                            <option value="1" {{ request('archived') == '1' ? 'selected' : '' }}>Archived Only</option>
                                        </select>
                                    @endif

                                    <button type="submit" class="apply-btn">Apply Filters</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Extended table display -->
                    <div class="table-wrapper">
                        <table class="w-full bg-white dark:bg-gray-800" id="complaintsTable">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 12%;">
                                    <a href="{{ route('complaints.my-investigations', array_merge(request()->query(), ['sort' => 'date_received', 'direction' => request('sort') == 'date_received' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:underline">
                                        Date Received
                                        @if(request('sort') == 'date_received')
                                            <span class="ml-1">{{ request('direction') == 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 15%;">
                                    <a href="{{ route('complaints.my-investigations', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:underline">
                                        Name
                                        @if(request('sort') == 'name')
                                            <span class="ml-1">{{ request('direction') == 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 12%;">
                                    <a href="{{ route('complaints.my-investigations', array_merge(request()->query(), ['sort' => 'pcn_number', 'direction' => request('sort') == 'pcn_number' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:underline">
                                        PCN Number
                                        @if(request('sort') == 'pcn_number')
                                            <span class="ml-1">{{ request('direction') == 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 10%;">
                                    <a href="{{ route('complaints.my-investigations', array_merge(request()->query(), ['sort' => 'site_id', 'direction' => request('sort') == 'site_id' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:underline">
                                        Site
                                        @if(request('sort') == 'site_id')
                                            <span class="ml-1">{{ request('direction') == 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 10%;">
                                    Status
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 12%;">
                                    Comments
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 14%;">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse($complaints as $complaint)
                                <tr class="table-row-hover {{ $complaint->archived ? 'archived-row' : '' }}">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $complaint->date_received }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $complaint->name }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $complaint->pcn_number }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $complaint->site ? $complaint->site->name : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                        @if($complaint->archived)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Archived
                                            </span>
                                        @else
                                            <div class="clean-select status-{{ $complaint->status }}"
                                                 data-complaint-id="{{ $complaint->id }}"
                                                 data-current-status="{{ $complaint->status }}"
                                                 @if($complaint->archived) data-disabled="true" @endif>
                                                <div class="clean-select-button">
                                                    <span class="status-text">{{ ucfirst($complaint->status) }}</span>
                                                    @if(!$complaint->archived)
                                                        <svg class="clean-select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                @if(!$complaint->archived)
                                                    <div class="clean-select-dropdown">
                                                        <div class="clean-select-option" data-value="open">Open</div>
                                                        <div class="clean-select-option" data-value="closed">Closed</div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                        <!-- Comments Button -->
                                        <div class="comments-container">
                                            <button
                                                type="button"
                                                class="comments-btn"
                                                onclick="openCommentsModal({{ $complaint->id }})"
                                                data-complaint-id="{{ $complaint->id }}"
                                            >
                                                <svg class="comments-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span class="comments-text">Comments</span>
                                                <span class="comments-count" id="comments-count-{{ $complaint->id }}" data-count="{{ $complaint->comments_count ?? 0 }}">
                                                    @if(($complaint->comments_count ?? 0) > 0)
                                                        {{ $complaint->comments_count }}
                                                    @endif
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            @if(!$complaint->archived)
                                                <a href="{{ route('complaints.edit-investigation', $complaint) }}"
                                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('complaints.archive-from-investigations', $complaint) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200" onclick="return confirm('Are you sure you want to archive this complaint? This will move it to the archived section.')">
                                                        Archive
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('complaints.unarchive', $complaint) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200" onclick="return confirm('Are you sure you want to restore this complaint from archive?')">
                                                        Restore
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center space-y-2">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">No investigations assigned</p>
                                            <p class="text-sm">You don't have any complaints assigned for investigation.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($complaints->hasPages())
                        <div class="mt-6">
                            {{ $complaints->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Modal -->
    <div id="commentsModal" class="comments-modal">
        <div class="comments-modal-content">
            <div class="comments-modal-header">
                <h3 class="comments-modal-title">Comments</h3>
                <button type="button" class="comments-close" onclick="closeCommentsModal()">&times;</button>
            </div>
            <div class="comments-modal-body">
                <div id="commentsLoader" style="text-align: center; padding: 20px;">
                    <div class="loading-spinner"></div>
                    <p style="margin-top: 10px; color: #6b7280;">Loading comments...</p>
                </div>

                <div id="commentsContent" style="display: none;">
                    <div class="comments-list" id="commentsList">
                        <!-- Comments will be loaded here -->
                    </div>

                    <form class="comment-form" id="commentForm">
                        @csrf
                        <textarea
                            id="commentInput"
                            class="comment-input"
                            placeholder="Add your comment..."
                            required
                            maxlength="1000"
                        ></textarea>
                        <button type="submit" class="comment-submit" id="commentSubmitBtn">
                            Post Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentComplaintId = null;

        function toggleFilters(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('filterDropdown');
            const button = document.getElementById('filterButton');

            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                // Position the dropdown relative to the button
                const buttonRect = button.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const dropdownHeight = 400; // max-height from CSS

                // Calculate if dropdown should open upward or downward
                const spaceBelow = viewportHeight - buttonRect.bottom;
                const spaceAbove = buttonRect.top;

                if (spaceBelow >= dropdownHeight || spaceBelow >= spaceAbove) {
                    // Open downward
                    dropdown.style.top = (buttonRect.bottom + window.scrollY + 8) + 'px';
                } else {
                    // Open upward
                    dropdown.style.top = (buttonRect.top + window.scrollY - dropdownHeight - 8) + 'px';
                }

                dropdown.style.left = (buttonRect.right - 240) + 'px'; // 240px = 15rem width
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        }

        // Comments Modal Functions
        function openCommentsModal(complaintId) {
            currentComplaintId = complaintId;
            document.getElementById('commentsModal').style.display = 'block';
            document.getElementById('commentsLoader').style.display = 'block';
            document.getElementById('commentsContent').style.display = 'none';
            document.body.style.overflow = 'hidden';

            loadComments(complaintId);
        }

        function closeCommentsModal() {
            document.getElementById('commentsModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentComplaintId = null;

            // Reset form
            document.getElementById('commentForm').reset();
            document.getElementById('commentSubmitBtn').disabled = false;
        }

        // Close modal when clicking outside
        document.getElementById('commentsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCommentsModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('commentsModal').style.display === 'block') {
                closeCommentsModal();
            }
        });

        async function loadComments(complaintId) {
            try {
                const response = await fetch(`/complaints/${complaintId}/comments`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    displayComments(data.comments);
                    updateCommentsCount(complaintId, data.count);
                } else {
                    showError('Failed to load comments');
                }
            } catch (error) {
                console.error('Error loading comments:', error);
                showError('Error loading comments');
            } finally {
                document.getElementById('commentsLoader').style.display = 'none';
                document.getElementById('commentsContent').style.display = 'block';
            }
        }

        function displayComments(comments) {
            const commentsList = document.getElementById('commentsList');

            if (comments.length === 0) {
                commentsList.innerHTML = '<div class="no-comments">No comments yet. Be the first to comment!</div>';
                return;
            }

            commentsList.innerHTML = comments.map(comment => `
                <div class="comment-item" data-comment-id="${comment.id}">
                    <div class="comment-header">
                        <span class="comment-author">${escapeHtml(comment.user_name)}</span>
                        <span class="comment-date">${comment.created_at}</span>
                    </div>
                    <div class="comment-text">${escapeHtml(comment.comment)}</div>
                    ${comment.can_delete ? `
                        <div class="comment-actions">
                            <button type="button" class="comment-delete" onclick="deleteComment(${comment.id})">
                                Delete
                            </button>
                        </div>
                    ` : ''}
                </div>
            `).join('');
        }

        // Handle comment form submission
        document.getElementById('commentForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('commentSubmitBtn');
            const commentInput = document.getElementById('commentInput');
            const comment = commentInput.value.trim();

            if (!comment) {
                showError('Please enter a comment');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="loading-spinner" style="width: 16px; height: 16px;"></div> Posting...';

            try {
                const response = await fetch(`/complaints/${currentComplaintId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ comment: comment })
                });

                const data = await response.json();

                if (data.success) {
                    commentInput.value = '';
                    loadComments(currentComplaintId); // Reload comments
                    showSuccess('Comment added successfully');
                } else {
                    showError(data.message || 'Failed to add comment');
                }
            } catch (error) {
                console.error('Error posting comment:', error);
                showError('Error posting comment');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Post Comment';
            }
        });

        async function deleteComment(commentId) {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }

            try {
                const response = await fetch(`/complaints/${currentComplaintId}/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    loadComments(currentComplaintId); // Reload comments
                    showSuccess('Comment deleted successfully');
                } else {
                    showError(data.message || 'Failed to delete comment');
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
                showError('Error deleting comment');
            }
        }

        function updateCommentsCount(complaintId, count) {
            const countElement = document.getElementById(`comments-count-${complaintId}`);
            if (countElement) {
                countElement.setAttribute('data-count', count);
                if (count > 0) {
                    countElement.textContent = count;
                    countElement.style.display = 'block';
                } else {
                    countElement.textContent = '';
                    countElement.style.display = 'none';
                }
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showError(message) {
            alert('Error: ' + message);
        }

        function showSuccess(message) {
            alert('Success: ' + message);
        }

        // Status change handlers - NOW USING PHP FORMS
        document.addEventListener('DOMContentLoaded', function() {
            // Add row click handlers for editing complaints
            document.querySelectorAll('tbody tr').forEach(row => {
                // Skip empty state row
                if (row.querySelector('td[colspan]')) return;

                const complaintId = row.querySelector('[data-complaint-id]')?.dataset.complaintId;
                if (complaintId) {
                    row.style.cursor = 'pointer';
                    row.addEventListener('click', function(e) {
                        // Don't trigger if clicking on interactive elements
                        if (e.target.closest('button, .clean-select, .comments-btn, a, form')) {
                            return;
                        }

                        // Navigate to edit investigation page
                        window.location.href = `/complaints/${complaintId}/edit-investigation`;
                    });

                    // Add hover effect
                    row.addEventListener('mouseenter', function() {
                        if (!this.classList.contains('archived-row')) {
                            this.style.backgroundColor = 'rgba(59, 130, 246, 0.05)';
                        }
                    });

                    row.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                    });
                }
            });

            // Status dropdowns - NOW USING PHP FORM SUBMISSIONS
            document.querySelectorAll('.clean-select').forEach(select => {
                if (select.dataset.disabled === 'true') return;

                const button = select.querySelector('.clean-select-button');
                const dropdown = select.querySelector('.clean-select-dropdown');

                if (!button || !dropdown) return;

                button.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Close other dropdowns
                    document.querySelectorAll('.clean-select').forEach(s => {
                        if (s !== select) s.classList.remove('open');
                    });

                    const isOpen = select.classList.contains('open');

                    if (!isOpen) {
                        // Position the dropdown
                        const rect = button.getBoundingClientRect();
                        const viewportHeight = window.innerHeight;
                        const dropdownHeight = 200; // max-height from CSS

                        // Calculate available space
                        const spaceBelow = viewportHeight - rect.bottom - 10;
                        const spaceAbove = rect.top - 10;

                        // Position dropdown
                        dropdown.style.left = rect.left + 'px';
                        dropdown.style.width = rect.width + 'px';

                        if (spaceBelow >= Math.min(dropdownHeight, 120) || spaceBelow >= spaceAbove) {
                            // Open below
                            dropdown.style.top = (rect.bottom + 4) + 'px';
                            dropdown.style.bottom = 'auto';
                        } else {
                            // Open above
                            dropdown.style.bottom = (viewportHeight - rect.top + 4) + 'px';
                            dropdown.style.top = 'auto';
                        }
                    }

                    select.classList.toggle('open');
                });

                dropdown.querySelectorAll('.clean-select-option').forEach(option => {
                    option.addEventListener('click', function() {
                        const newStatus = this.dataset.value;
                        const complaintId = select.dataset.complaintId;
                        const currentStatus = select.dataset.currentStatus;

                        if (newStatus === currentStatus) {
                            select.classList.remove('open');
                            return;
                        }

                        // Create and submit PHP form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/complaints/${complaintId}/status`;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const statusInput = document.createElement('input');
                        statusInput.type = 'hidden';
                        statusInput.name = 'status';
                        statusInput.value = newStatus;

                        form.appendChild(csrfToken);
                        form.appendChild(statusInput);
                        document.body.appendChild(form);
                        form.submit();

                        select.classList.remove('open');
                    });
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.clean-select')) {
                    document.querySelectorAll('.clean-select').forEach(select => {
                        select.classList.remove('open');
                    });
                }

                const dropdown = document.getElementById('filterDropdown');
                const button = document.getElementById('filterButton');
                if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            document.getElementById('filterDropdown').style.display = 'none';
            // Close any open status dropdowns on resize
            document.querySelectorAll('.clean-select').forEach(select => {
                select.classList.remove('open');
            });
        });
    </script>
</x-app-layout>
