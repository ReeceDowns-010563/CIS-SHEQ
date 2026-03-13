@props([
    'id' => 'datatable',
    'data',
    'columns' => [],
    'filters' => [],
    'searchFields' => [],
    'searchPlaceholder' => 'Search...',
    'routeName',
    'sortable' => true,
    'showArchived' => false,
    'emptyMessage' => 'No data found',
    'emptyIcon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
])

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
        background-color: var(--primary-colour, #6366f1);
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
        display: block;
        margin-top: .75rem;
    }
    .filter-dropdown label:first-child {
        margin-top: 0;
    }
    .dark .filter-dropdown label {
        color: #d1d5db;
    }

    .filter-dropdown select,
    .filter-dropdown input[type="date"],
    .filter-dropdown input[type="text"] {
        width: 100%;
        padding: .5rem;
        margin-top: .25rem;
        background: #fff;
        border: 1px solid #e5e7eb;
        color: #111827;
        border-radius: .5rem;
        font-size: .875rem;
    }
    .dark .filter-dropdown select,
    .dark .filter-dropdown input[type="date"],
    .dark .filter-dropdown input[type="text"] {
        background: #374151;
        border-color: #4b5563;
        color: #e5e7eb;
    }

    .filter-dropdown .apply-btn {
        width: 100%;
        padding: .5rem;
        margin-top: 1rem;
        background: var(--primary-colour, #6366f1);
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

    /* Table styles */
    .table-row-hover:hover {
        background-color: rgba(243, 244, 246, 0.5);
        cursor: pointer;
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
        min-width: 1200px;
    }

    @media (max-width: 1024px) {
        table { min-width: 1000px; }
    }

    @media (max-width: 768px) {
        table { min-width: 800px; }
        .table-wrapper {
            border-radius: 8px;
            margin-left: -1.5rem;
            margin-right: -1.5rem;
            border-left: none;
            border-right: none;
        }
        th, td {
            padding: 8px 6px;
            font-size: 12px;
        }
    }

    /* Status dropdown styles */
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

    /* Status Colors */
    .status-open .clean-select-button,
    .status-pending .clean-select-button {
        color: #d97706;
        background: #fef3c7;
        border-color: #fcd34d;
    }

    .status-investigating .clean-select-button {
        color: #2563eb;
        background: #dbeafe;
        border-color: #93c5fd;
    }

    .status-completed .clean-select-button {
        color: #059669;
        background: #ecfdf5;
        border-color: #a7f3d0;
    }

    .status-closed .clean-select-button {
        color: #dc2626;
        background: #fef2f2;
        border-color: #fca5a5;
    }

    /* Comments Button */
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

    .comments-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
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
</style>

<div class="py-12">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                <!-- Search and Filter Section -->
                <div class="search-container">
                    <form method="GET" action="{{ route($routeName) }}" class="search-input-group">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ $searchPlaceholder }}" class="search-input">

                        <!-- Preserve existing filters -->
                        @foreach($filters as $filter)
                            @if(request()->has($filter['name']) && $filter['name'] !== 'search')
                                <input type="hidden" name="{{ $filter['name'] }}" value="{{ request($filter['name']) }}">
                            @endif
                        @endforeach

                        <button type="submit" class="search-button">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>

                    @if(!empty($filters))
                        <div style="position: relative;">
                            <button id="filterButton-{{ $id }}" class="filter-btn" onclick="toggleFilters('{{ $id }}', event)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </button>

                            <div id="filterDropdown-{{ $id }}" class="filter-dropdown" style="display: none;">
                                <form method="GET" action="{{ route($routeName) }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">

                                    @foreach($filters as $filter)
                                        @if($filter['type'] === 'select')
                                            <label for="filter_{{ $filter['name'] }}">{{ $filter['label'] }}</label>
                                            <select name="{{ $filter['name'] }}" id="filter_{{ $filter['name'] }}">
                                                <option value="">{{ $filter['all_option'] ?? 'All' }}</option>
                                                @foreach($filter['options'] as $value => $label)
                                                    <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif($filter['type'] === 'date')
                                            <label for="filter_{{ $filter['name'] }}">{{ $filter['label'] }}</label>
                                            <input type="date" id="filter_{{ $filter['name'] }}" name="{{ $filter['name'] }}" value="{{ request($filter['name']) }}">
                                        @elseif($filter['type'] === 'checkbox')
                                            <label>
                                                <input type="checkbox" name="{{ $filter['name'] }}" value="{{ $filter['value'] ?? 1 }}" {{ request($filter['name']) ? 'checked' : '' }}>
                                                {{ $filter['label'] }}
                                            </label>
                                        @endif
                                    @endforeach

                                    <button type="submit" class="apply-btn">Apply Filters</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Results Summary -->
                @if(method_exists($data, 'count') && method_exists($data, 'total'))
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $data->count() }} of {{ $data->total() }} results
                            @if($showArchived && request('archived'))
                                (archived)
                            @endif
                        </p>
                    </div>
                @endif

                <!-- Table -->
                <div class="table-wrapper">
                    <table class="w-full bg-white dark:bg-gray-800" id="{{ $id }}-table">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @foreach($columns as $column)
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                    style="width: {{ $column['width'] ?? 'auto' }};">
                                    @if($sortable && isset($column['sortable']) && $column['sortable'])
                                        <a href="{{ route($routeName, array_merge(request()->query(), ['sort' => $column['key'], 'direction' => request('sort') == $column['key'] && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                           class="hover:underline">
                                            {{ $column['label'] }}
                                            @if(request('sort') == $column['key'])
                                                <span class="ml-1">{{ request('direction') == 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    @else
                                        {{ $column['label'] }}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse($data as $item)
                            <tr class="table-row-hover {{ isset($item->archived) && $item->archived ? 'archived-row' : '' }}"
                                @if(isset($columns[0]['clickable']) && $columns[0]['clickable'])
                                    onclick="handleRowClick(event, {{ $item->id }})"
                                @endif>
                                @foreach($columns as $column)
                                    <td class="px-4 py-4 {{ $column['class'] ?? 'text-sm text-gray-900 dark:text-gray-100' }}"
                                        @if(isset($column['no_click']) && $column['no_click'])
                                            onclick="event.stopPropagation()"
                                        @endif>
                                        {!! $column['render']($item) !!}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) }}" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center space-y-2">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $emptyIcon }}"></path>
                                        </svg>
                                        <p class="text-lg font-medium">{{ $emptyMessage }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($data, 'hasPages') && $data->hasPages())
                    <div class="mt-6">
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Generic filter toggle function
    function toggleFilters(tableId, event) {
        event.stopPropagation();
        const dropdown = document.getElementById(`filterDropdown-${tableId}`);
        const button = document.getElementById(`filterButton-${tableId}`);

        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            const buttonRect = button.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const dropdownHeight = 400;

            const spaceBelow = viewportHeight - buttonRect.bottom;
            const spaceAbove = buttonRect.top;

            if (spaceBelow >= dropdownHeight || spaceBelow >= spaceAbove) {
                dropdown.style.top = (buttonRect.bottom + window.scrollY + 8) + 'px';
            } else {
                dropdown.style.top = (buttonRect.top + window.scrollY - dropdownHeight - 8) + 'px';
            }

            dropdown.style.left = (buttonRect.right - 240) + 'px';
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    // Generic row click handler
    function handleRowClick(event, itemId) {
        if (event.target.closest('.clean-select, .assigned-select, .comments-btn, .action-links, [onclick]')) {
            return;
        }
        // This can be overridden in the parent template
        if (typeof customRowClickHandler === 'function') {
            customRowClickHandler(itemId);
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        document.querySelectorAll('[id^="filterDropdown-"]').forEach(dropdown => {
            const tableId = dropdown.id.replace('filterDropdown-', '');
            const button = document.getElementById(`filterButton-${tableId}`);
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Close other dropdowns
        document.querySelectorAll('.clean-select, .assigned-select').forEach(select => {
            if (!select.contains(event.target)) {
                select.classList.remove('open');
            }
        });
    });

    // Status dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Status dropdowns
        document.querySelectorAll('.clean-select').forEach(select => {
            const button = select.querySelector('.clean-select-button');
            const dropdown = select.querySelector('.clean-select-dropdown');
            const options = select.querySelectorAll('.clean-select-option');

            if (!button || !dropdown) return;

            button.addEventListener('click', function(e) {
                e.stopPropagation();

                document.querySelectorAll('.clean-select, .assigned-select').forEach(s => {
                    if (s !== select) s.classList.remove('open');
                });

                const isOpen = select.classList.contains('open');

                if (!isOpen) {
                    const rect = button.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;
                    const dropdownHeight = 200;

                    const spaceBelow = viewportHeight - rect.bottom - 10;
                    const spaceAbove = rect.top - 10;

                    dropdown.style.left = rect.left + 'px';
                    dropdown.style.width = rect.width + 'px';

                    if (spaceBelow >= Math.min(dropdownHeight, 120) || spaceBelow >= spaceAbove) {
                        dropdown.style.top = (rect.bottom + 4) + 'px';
                        dropdown.style.bottom = 'auto';
                    } else {
                        dropdown.style.bottom = (viewportHeight - rect.top + 4) + 'px';
                        dropdown.style.top = 'auto';
                    }
                }

                select.classList.toggle('open');
            });

            options.forEach(option => {
                option.addEventListener('click', function() {
                    const newValue = this.dataset.value;
                    const itemId = select.dataset.itemId || select.dataset.incidentId || select.dataset.complaintId;
                    const currentValue = select.dataset.currentStatus || select.dataset.currentValue;

                    if (newValue !== currentValue && typeof updateItemStatus === 'function') {
                        updateItemStatus(itemId, newValue, select);
                    }

                    select.classList.remove('open');
                });
            });
        });
    });
</script>
