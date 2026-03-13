  <x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Download Reports
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="download-container">
            <!-- Header -->
            <div class="download-header">
                <h1>Export Complaints Data</h1>
                <p>Generate comprehensive reports for analysis and compliance</p>
            </div>

            <!-- Content -->
            <div class="download-content">
                <form action="{{ route('complaints.export') }}" method="POST" id="exportForm">
                    @csrf

                    <!-- Export Type Selection -->
                    <div class="export-options">
                        <!-- CSV Report -->
                        <label class="export-option selected" for="csv">
                            <input type="radio" id="csv" name="format" value="csv" class="export-option-radio" checked>
                            <div class="export-option-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="export-option-title">CSV Export</div>
                            <div class="export-option-description">
                                Complete complaint records in CSV format. Compatible with Excel and other spreadsheet applications for easy analysis.
                            </div>
                        </label>

                        <!-- PDF Report -->
                        <label class="export-option" for="pdf" onclick="window.location.href='{{ route('reports.export') }}'">
                            <input type="radio" id="pdf" name="format" value="pdf" class="export-option-radio">
                            <div class="export-option-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </div>
                            <div class="export-option-title">PDF Report</div>
                            <div class="export-option-description">
                                Generate and download comprehensive monthly reports in PDF format with charts and detailed analytics.
                            </div>
                        </label>
                    </div>

                    <!-- Additional Filters -->
                    <div class="filter-row" style="display: flex; gap:1rem; flex-wrap: wrap; margin-bottom:1.5rem;">
                        <div class="filter-item">
                            <label for="date_received_from" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Received from</label>
                            <input type="date" name="date_received_from" id="date_received_from" value="{{ old('date_received_from') }}" class="mt-1 block w-full rounded border-gray-300" />
                            @error('date_received_from')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="filter-item">
                            <label for="date_received_to" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Received to</label>
                            <input type="date" name="date_received_to" id="date_received_to" value="{{ old('date_received_to') }}" class="mt-1 block w-full rounded border-gray-300" />
                            @error('date_received_to')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Site Selection -->
                    <div class="site-selection">
                        <div class="site-selection-title">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Choose Sites
                        </div>

                        <div class="multi-select-container">
                            <div class="multi-select-dropdown" id="siteSelectorBtn" tabindex="0">
                                <span class="multi-select-text placeholder" id="selectedText">Select sites...</span>
                                <svg class="multi-select-arrow" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                <!-- Hidden inputs for site_ids[] go here -->
                                <div id="siteIdsContainer" style="display:none;"></div>
                            </div>

                            <!-- Selected chips shown beneath the dropdown -->
                            <div id="selectedSitesContainer" class="selected-sites-wrapper" aria-live="polite" style="margin-top:0.75rem;"></div>

                            <div class="multi-select-options" id="siteOptions">
                                <!-- Search input -->
                                <div class="search-container">
                                    <input
                                        type="text"
                                        id="siteSearch"
                                        class="search-input"
                                        placeholder="Search sites..."
                                        autocomplete="off"
                                    />
                                    <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>

                                <!-- Options container -->
                                <div class="options-container" id="optionsContainer">
                                    <div class="multi-select-option" data-value="" data-name="All Sites">
                                        All Sites
                                    </div>
                                    @foreach($sites as $site)
                                        <div class="multi-select-option" data-value="{{ $site->id }}" data-name="{{ $site->name }}">
                                            {{ $site->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @error('site_ids')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @error('site_ids.*')
                        <div class="text-red-500 text-sm mt-1">One of the selected sites is invalid.</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="submit" class="btn-primary">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download CSV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Professional container styling */
        .download-container {
            max-width: 800px;
            margin: 0 auto;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .dark .download-container {
            background: linear-gradient(135deg, #1f2937 0%, #111825 100%);
            border-color: #374151;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        /* Header section */
        .download-header {
            background: linear-gradient(135deg, var(--primary-colour) 0%, var(--secondary-colour) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .download-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .download-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .download-header p {
            font-size: 1.125rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Force data parameter labels to black */
        .filter-item label {
            color: #000 !important;
        }

        /* Content section */
        .download-content {
            padding: 2.5rem;
        }

        /* Export option cards */
        .export-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .export-option {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: #ffffff;
        }

        .dark .export-option {
            background: #374151;
            border-color: #4b5563;
        }

        .export-option:hover:not(.export-option-disabled) {
            border-color: var(--primary-colour);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(167, 98, 44, 0.15);
        }

        .export-option.selected {
            border-color: var(--primary-colour);
            background: #fef7ed;
            box-shadow: 0 10px 25px -5px rgba(167, 98, 44, 0.25);
        }

        .dark .export-option.selected {
            background: rgba(167, 98, 44, 0.1);
            border-color: var(--primary-colour);
        }

        /* Disabled export option */
        .export-option-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .export-option-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .export-option-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-colour) 0%, var(--secondary-colour) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: white;
        }

        .export-option-icon-disabled {
            background: #9ca3af;
        }

        .export-option-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .dark .export-option-title {
            color: #f9fafb;
        }

        .export-option-description {
            font-size: 0.875rem;
            color: #6b7280;
            line-height: 1.5;
        }

        .dark .export-option-description {
            color: #9ca3af;
        }

        /* Site selection section */
        .site-selection {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .dark .site-selection {
            background: #1f2937;
            border-color: #374151;
        }

        .site-selection-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .dark .site-selection-title {
            color: #f9fafb;
        }

        .site-selection-title svg {
            margin-right: 0.5rem;
            color: var(--primary-colour);
        }

        /* Dropdown styling - Drop-up version */
        .multi-select-container {
            position: relative;
        }

        .multi-select-dropdown {
            width: 100%;
            min-height: 48px;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .dark .multi-select-dropdown {
            background: #374151;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .multi-select-dropdown:hover,
        .multi-select-dropdown.open {
            border-color: var(--primary-colour);
        }

        .multi-select-dropdown:focus-within {
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.15);
            outline: none;
        }

        .multi-select-dropdown.open {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .multi-select-text {
            flex: 1;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .multi-select-text {
            color: #e5e7eb;
        }

        .multi-select-text.placeholder {
            color: #9ca3af;
        }

        .multi-select-arrow {
            transition: transform 0.2s ease;
            color: #6b7280;
            margin-left: 0.5rem;
        }

        .multi-select-dropdown.open .multi-select-arrow {
            transform: rotate(180deg);
        }

        /* Drop-up options positioning */
        .multi-select-options {
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            margin-bottom: 0;
            background: white;
            border: 2px solid var(--primary-colour);
            border-bottom: none;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            max-height: 300px;
            overflow: hidden;
            z-index: 999;
            display: none;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
        }

        .dark .multi-select-options {
            background: #374151;
            border-color: var(--primary-colour);
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.3);
        }

        .multi-select-options.show {
            display: block;
        }

        /* Search container */
        .search-container {
            position: relative;
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            background: white;
        }

        .dark .search-container {
            border-bottom-color: #4b5563;
            background: #374151;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            background: white;
            color: #374151;
            outline: none;
        }

        .dark .search-input {
            background: #1f2937;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .search-input:focus {
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 2px rgba(167, 98, 44, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        /* Options container with scroll */
        .options-container {
            max-height: 200px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-colour) #f8fafc;
        }

        .dark .options-container {
            scrollbar-color: var(--primary-colour) #1f2937;
        }

        /* Custom scrollbar */
        .options-container::-webkit-scrollbar {
            width: 6px;
        }

        .options-container::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .dark .options-container::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .options-container::-webkit-scrollbar-thumb {
            background-color: var(--primary-colour);
            border-radius: 20px;
        }

        .multi-select-option {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background-color 0.15s ease;
            border-bottom: 1px solid #f3f4f6;
            user-select: none;
            color: #374151;
        }

        .multi-select-option.selected {
            background: var(--primary-colour);
            color: #fff;
        }

        .multi-select-option.hidden {
            display: none;
        }

        .dark .multi-select-option {
            border-bottom-color: #4b5563;
            color: #e2e7eb;
        }

        .dark .multi-select-option.selected {
            background: var(--primary-colour);
            color: #fff;
        }

        .multi-select-option:hover {
            background: rgba(167, 98, 44, 0.1);
        }

        .dark .multi-select-option:hover {
            background: rgba(167, 98, 44, 0.1);
        }

        .multi-select-option:last-child {
            border-bottom: none;
        }

        /* Selected site chips */
        .selected-sites-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .selected-site-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--primary-colour);
            color: #fff;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            position: relative;
            line-height: 1;
            font-weight: 500;
        }

        .selected-site-chip .remove-site {
            cursor: pointer;
            font-weight: 600;
            padding: 0 4px;
        }

        /* No results message */
        .no-results {
            padding: 1rem;
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            border-bottom: 1px solid #f3f4f6;
        }

        .dark .no-results {
            color: #6b7280;
            border-bottom-color: #4b5563;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding-top: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-colour) 0%, var(--secondary-colour) 100%);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .download-container {
                margin: 1rem;
                border-radius: 12px;
            }

            .download-content {
                padding: 1.5rem;
            }

            .export-options {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('siteSelectorBtn');
            const dropdown = document.getElementById('siteOptions');
            const selectedText = document.getElementById('selectedText');
            const exportOptions = document.querySelectorAll('.export-option:not(.export-option-disabled)');
            const siteOptions = document.querySelectorAll('.multi-select-option');
            const siteIdsContainer = document.getElementById('siteIdsContainer');
            const selectedSitesContainer = document.getElementById('selectedSitesContainer');
            const searchInput = document.getElementById('siteSearch');
            const optionsContainer = document.getElementById('optionsContainer');

            let dropdownOpen = false;
            const selectedSiteIds = new Set();
            let allSites = Array.from(siteOptions).map(option => ({
                element: option,
                value: option.dataset.value,
                name: option.dataset.name.toLowerCase()
            }));

            // Search functionality
            function filterSites() {
                const searchTerm = searchInput.value.toLowerCase();
                let visibleCount = 0;
                let hasResults = false;

                // Remove existing no-results message
                const existingNoResults = optionsContainer.querySelector('.no-results');
                if (existingNoResults) {
                    existingNoResults.remove();
                }

                allSites.forEach(site => {
                    const matches = site.name.includes(searchTerm);

                    if (matches) {
                        site.element.classList.remove('hidden');
                        hasResults = true;

                        // Show only first 10 results for non-empty search, or all if search is empty
                        if (searchTerm === '' || visibleCount < 10) {
                            site.element.style.display = 'block';
                            visibleCount++;
                        } else {
                            site.element.style.display = 'none';
                        }
                    } else {
                        site.element.classList.add('hidden');
                        site.element.style.display = 'none';
                    }
                });

                // Show "All Sites" option only when search is empty
                const allSitesOption = optionsContainer.querySelector('[data-value=""]');
                if (allSitesOption) {
                    if (searchTerm === '') {
                        allSitesOption.style.display = 'block';
                    } else {
                        allSitesOption.style.display = 'none';
                    }
                }

                // Show no results message if needed
                if (!hasResults && searchTerm !== '') {
                    const noResults = document.createElement('div');
                    noResults.className = 'no-results';
                    noResults.textContent = 'No sites found matching "' + searchInput.value + '"';
                    optionsContainer.appendChild(noResults);
                }
            }

            // Limit initial display to 10 sites
            function limitInitialDisplay() {
                let visibleCount = 0;
                allSites.forEach(site => {
                    if (site.value !== '') { // Skip "All Sites" option
                        if (visibleCount < 10) {
                            site.element.style.display = 'block';
                            visibleCount++;
                        } else {
                            site.element.style.display = 'none';
                        }
                    }
                });
            }

            // Initialize with limited display
            limitInitialDisplay();

            // Search input event
            searchInput.addEventListener('input', filterSites);

            // Prevent search input from closing dropdown
            searchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Clear search when dropdown closes
            function clearSearch() {
                searchInput.value = '';
                filterSites();
                limitInitialDisplay();
            }

            // Update the visible text summary
            function updateDropdownText() {
                if (selectedSiteIds.size === 0) {
                    selectedText.textContent = 'All Sites';
                    selectedText.classList.remove('placeholder');
                } else if (selectedSiteIds.size === 1) {
                    const singleId = [...selectedSiteIds][0];
                    const singleOption = allSites.find(s => s.value === singleId);
                    selectedText.textContent = singleOption ? singleOption.element.dataset.name : '1 site selected';
                } else {
                    selectedText.textContent = selectedSiteIds.size + ' sites selected';
                }
            }

            function updateHiddenInputs() {
                siteIdsContainer.innerHTML = '';
                if (selectedSiteIds.size === 0) {
                    siteIdsContainer.style.display = 'none';
                    return;
                }
                selectedSiteIds.forEach(id => {
                    const inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = 'site_ids[]';
                    inp.value = id;
                    siteIdsContainer.appendChild(inp);
                });
                siteIdsContainer.style.display = 'block';
            }

            function renderSelectedChips() {
                selectedSitesContainer.innerHTML = '';
                if (selectedSiteIds.size === 0) {
                    const chip = document.createElement('div');
                    chip.className = 'selected-site-chip';
                    chip.textContent = 'All Sites';
                    selectedSitesContainer.appendChild(chip);
                    return;
                }

                selectedSiteIds.forEach(id => {
                    const siteData = allSites.find(s => s.value === id);
                    const name = siteData ? siteData.element.dataset.name : id;
                    const chip = document.createElement('div');
                    chip.className = 'selected-site-chip';

                    const label = document.createElement('span');
                    label.textContent = name;

                    const remove = document.createElement('span');
                    remove.className = 'remove-site';
                    remove.setAttribute('aria-label', `Remove ${name}`);
                    remove.textContent = '×';
                    remove.addEventListener('click', function(e) {
                        e.stopPropagation();
                        selectedSiteIds.delete(id);
                        if (siteData) siteData.element.classList.remove('selected');
                        updateDropdownText();
                        updateHiddenInputs();
                        renderSelectedChips();
                    });

                    chip.appendChild(label);
                    chip.appendChild(remove);
                    selectedSitesContainer.appendChild(chip);
                });
            }

            // initial state
            updateDropdownText();
            renderSelectedChips();

            // Export option toggle
            exportOptions.forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.export-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;
                });
            });

            // Dropdown toggle
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownOpen = !dropdownOpen;
                if (dropdownOpen) {
                    dropdownBtn.classList.add('open');
                    dropdown.classList.add('show');
                    // Focus search input when opening
                    setTimeout(() => searchInput.focus(), 100);
                } else {
                    dropdownBtn.classList.remove('open');
                    dropdown.classList.remove('show');
                    clearSearch();
                }
            });

            // Multi-select logic
            siteOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    const value = this.dataset.value;

                    if (value === '') {
                        selectedSiteIds.clear();
                        siteOptions.forEach(o => o.classList.remove('selected'));
                        this.classList.add('selected');
                    } else {
                        const allSitesOption = [...siteOptions].find(o => o.dataset.value === '');
                        if (allSitesOption) {
                            allSitesOption.classList.remove('selected');
                        }

                        if (selectedSiteIds.has(value)) {
                            selectedSiteIds.delete(value);
                            this.classList.remove('selected');
                        } else {
                            selectedSiteIds.add(value);
                            this.classList.add('selected');
                        }
                    }

                    updateDropdownText();
                    updateHiddenInputs();
                    renderSelectedChips();
                });
            });

            // Click outside to close
            document.addEventListener('click', function() {
                if (dropdownOpen) {
                    dropdownBtn.classList.remove('open');
                    dropdown.classList.remove('show');
                    dropdownOpen = false;
                    clearSearch();
                }
            });

            // Keyboard accessibility
            dropdownBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    dropdownBtn.click();
                }
            });

            dropdown.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdownBtn.click();
                    dropdownBtn.focus();
                }
            });
        });
    </script>
</x-app-layout>
