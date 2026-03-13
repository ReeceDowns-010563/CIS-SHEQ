@props([
    'searchValue' => '',
    'searchPlaceholder' => 'Search...',
    'searchName' => 'search',
    'action' => '',
    'method' => 'GET',
    'filters' => [],
    'hiddenFields' => []
])

<div class="search-container">
    <form method="{{ $method }}" action="{{ $action }}" class="search-input-group">
        <input type="text"
               name="{{ $searchName }}"
               value="{{ $searchValue }}"
               placeholder="{{ $searchPlaceholder }}"
               class="search-input">

        <!-- Preserve hidden fields -->
        @foreach($hiddenFields as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach

        <button type="submit" class="search-button">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </form>

    @if(count($filters) > 0)
        <div style="position: relative;">
            <button id="filterButton" class="filter-btn" onclick="toggleFilters(event)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </button>

            <div id="filterDropdown" class="filter-dropdown" style="display: none;">
                <form method="{{ $method }}" action="{{ $action }}">
                    <input type="hidden" name="{{ $searchName }}" value="{{ $searchValue }}">

                    @foreach($filters as $filter)
                        <label for="filter_{{ $filter['name'] }}"
                               @if(!$loop->first) style="margin-top: 1rem; display: block;" @endif>
                            {{ $filter['label'] }}
                        </label>
                        <select name="{{ $filter['name'] }}" id="filter_{{ $filter['name'] }}">
                            <option value="">{{ $filter['placeholder'] ?? 'All' }}</option>
                            @foreach($filter['options'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    @endforeach

                    <button type="submit" class="apply-btn">Apply Filters</button>
                </form>
            </div>
        </div>
    @endif
</div>
