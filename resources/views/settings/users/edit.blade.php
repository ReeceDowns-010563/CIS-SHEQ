<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit User</h2>
    </x-slot>

    <style>
        /* Copy all the same styles from create.blade.php - same exact CSS */
        .form-container {
            max-width: 48rem;
            margin: 0 auto;
            margin-top: 1.5rem;
            background-color: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .form-container {
                background-color: #1f2937;
                color: #e5e7eb;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.25);
            }
        }

        /* All the same CSS from create.blade.php - access-container, dropdown styles, chips, etc. */
        .access-container {
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: #f9fafb;
            border-radius: 0.75rem;
            border: 2px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .access-container {
                background-color: #111827;
                border-color: #374151;
            }
        }

        .access-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .access-title {
                color: #d1d5db;
            }
        }

        /* Include all dropdown, chip, and form styles from create.blade.php - same exact CSS */
        .dropdown-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .dropdown-button {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            text-align: left;
            font-size: 1rem;
        }

        .dropdown-button:hover {
            border-color: var(--primary-colour);
        }

        .dropdown-button:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-button {
                background: #374151;
                border-color: #4b5563;
                color: white;
            }

            .dropdown-button:hover {
                border-color: var(--primary-colour);
            }

            .dropdown-button:focus {
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 50;
            max-height: 400px;
            overflow: hidden;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-menu {
                background: #374151;
                border-color: #4b5563;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
            }
        }

        .dropdown-search {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-search {
                border-bottom-color: #4b5563;
            }
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            outline: none;
            font-size: 0.875rem;
        }

        .search-input:focus {
            border-color: var(--primary-colour);
        }

        @media (prefers-color-scheme: dark) {
            .search-input {
                background: #1f2937;
                border-color: #4b5563;
                color: white;
            }
        }

        .dropdown-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .dropdown-item {
            padding: 0.75rem;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: background-color 0.15s ease-in-out;
        }

        .dropdown-item:hover {
            background: #f9fafb;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-item {
                border-bottom-color: #374151;
            }

            .dropdown-item:hover {
                background: #1f2937;
            }
        }

        .dropdown-item.special {
            background: #f0f9ff;
            font-weight: 600;
            color: #0369a1;
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-item.special {
                background: #1e3a8a;
                color: #93c5fd;
            }
        }

        .checkbox-input {
            width: 1rem;
            height: 1rem;
            accent-color: var(--primary-colour);
        }

        .chips-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        @media (max-width: 768px) {
            .chips-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        .chips-section {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        @media (prefers-color-scheme: dark) {
            .chips-section {
                border-color: #374151;
            }
        }

        .chips-title {
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #374151;
            font-size: 0.875rem;
        }

        @media (prefers-color-scheme: dark) {
            .chips-title {
                color: #f9fafb;
            }
        }

        .chips-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            min-height: 2rem;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.5rem;
            background: #e0e7ff;
            color: #3730a3;
            border-radius: 9999px;
            font-size: 0.75rem;
            transition: all 0.2s ease-in-out;
        }

        .chip.special {
            background: #065f46;
            color: #a7f3d0;
        }

        .chip.implicit {
            background: #f3f4f6;
            color: #6b7280;
            font-style: italic;
        }

        @media (prefers-color-scheme: dark) {
            .chip {
                background: #4c1d95;
                color: #c4b5fd;
            }

            .chip.implicit {
                background: #374151;
                color: #9ca3af;
            }
        }

        .chip-remove {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 0.125rem;
            border-radius: 50%;
            transition: background-color 0.15s ease-in-out;
            line-height: 1;
        }

        .chip-remove:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        /* Original form styles - same as create.blade.php */
        .error-container {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .error-container {
                background-color: rgba(185, 28, 28, 0.1);
                border-color: #dc2626;
                color: #f87171;
            }
        }

        .error-list {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .form-label {
                color: #d1d5db;
            }
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            color: #111827;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .form-input:hover, .form-select:hover {
            border-color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .form-input, .form-select {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            }

            .form-input:focus, .form-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .form-input:hover, .form-select:hover {
                border-color: #6b7280;
            }
        }

        .helper-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        @media (prefers-color-scheme: dark) {
            .helper-text {
                color: #9ca3af;
            }
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            gap: 1rem;
        }

        .cancel-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            color: white;
            background-color: #6b7280;
            border: 2px solid #6b7280;
            border-radius: 0.5rem;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color: #4b5563;
            border-color: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .update-btn {
            padding: 0.75rem 2rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: white;
            background-color: var(--primary-colour);
            border: 2px solid var(--primary-colour);
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .update-btn:hover {
            background-color: #924f25;
            border-color: #924f25;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .update-btn:active {
            transform: translateY(0);
        }

        .form-title {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }

        @media (prefers-color-scheme: dark) {
            .form-title {
                border-bottom-color: #374151;
            }
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .form-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .button-container {
                flex-direction: column;
                width: 100%;
            }

            .cancel-btn, .update-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <div class="py-10 px-4">
        <div class="form-container">
            <div class="form-title">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Edit User Account</h3>
            </div>

            @if ($errors->any())
                <div class="error-container">
                    <strong>Please fix these errors:</strong>
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.users.update', $user) }}" id="userForm">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                required
                                value="{{ old('name', $user->name) }}"
                                class="form-input"
                                placeholder="Enter full name"
                            />
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                value="{{ old('email', $user->email) }}"
                                class="form-input"
                                placeholder="Enter email address"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role_id" class="form-label">User Role</label>
                        <select
                            id="role_id"
                            name="role_id"
                            required
                            class="form-select"
                        >
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ (string) old('role_id', $user->role_id) === (string) $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name ?? ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-input"
                                placeholder="Leave blank to keep current password"
                            />
                            <div class="helper-text">Only fill this if you want to change the password</div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="form-input"
                                placeholder="Confirm new password"
                            />
                        </div>
                    </div>
                </div>

                <!-- Access Control Section -->
                <div class="access-container">
                    <h3 class="access-title">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Access Control
                    </h3>

                    <!-- Branches Dropdown -->
                    <div class="dropdown-container">
                        <label class="form-label">Branches Access</label>
                        <div class="dropdown-button" data-dropdown="branches">
                            <span id="branchesPlaceholder">Select branches...</span>
                            <svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div class="dropdown-menu" id="branchesDropdown">
                            <div class="dropdown-search">
                                <input type="text" class="search-input" placeholder="Search branches..." data-search="branches">
                            </div>
                            <div class="dropdown-list" id="branchesList">
                                <!-- Populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Sites Dropdown -->
                    <div class="dropdown-container">
                        <label class="form-label">Sites Access</label>
                        <div class="dropdown-button" data-dropdown="sites">
                            <span id="sitesPlaceholder">Select sites...</span>
                            <svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div class="dropdown-menu" id="sitesDropdown">
                            <div class="dropdown-search">
                                <input type="text" class="search-input" placeholder="Search sites..." data-search="sites">
                            </div>
                            <div class="dropdown-list" id="sitesList">
                                <!-- Populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Selected Items -->
                    <div class="chips-container">
                        <div class="chips-section">
                            <h4 class="chips-title">Selected Branches</h4>
                            <div class="chips-list" id="selectedBranches">
                                <!-- Populated by JavaScript -->
                            </div>
                        </div>

                        <div class="chips-section">
                            <h4 class="chips-title">Selected Sites</h4>
                            <div class="chips-list" id="selectedSites">
                                <!-- Populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden form fields -->
                <input type="hidden" name="all_branches" id="allBranchesInput" value="0">
                <input type="hidden" name="all_sites" id="allSitesInput" value="0">

                <!-- Buttons -->
                <div class="button-container">
                    <a href="{{ route('settings.users.index') }}" class="cancel-btn">
                        Cancel
                    </a>
                    <button type="submit" class="update-btn">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        class UserAccessManager {
            constructor() {
                this.branches = @json($branchesData);
                this.sites = @json($sitesData);
                this.selectedBranches = new Set();
                this.selectedSites = new Set();
                this.allBranches = false;
                this.allSites = false;
                this.implicitBranches = new Set();

                this.init();
            }

            init() {
                this.loadCurrentSelections();
                this.setupEventListeners();
                this.updateUI();
            }

            loadCurrentSelections() {
                // Load current access settings
                @if($user->accessSettings)
                    this.allBranches = {{ $user->accessSettings->all_branches ? 'true' : 'false' }};
                this.allSites = {{ $user->accessSettings->all_sites ? 'true' : 'false' }};
                @endif

                // Load selected branches
                @if($user->accessibleBranches->count() > 0)
                    @foreach($user->accessibleBranches as $branch)
                    this.selectedBranches.add({{ $branch->id }});
                @endforeach
                @endif

                // Load selected sites
                @if($user->accessibleSites->count() > 0)
                    @foreach($user->accessibleSites as $site)
                    this.selectedSites.add({{ $site->id }});
                @endforeach
                @endif
            }

            setupEventListeners() {
                document.querySelectorAll('[data-dropdown]').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const type = e.currentTarget.dataset.dropdown;
                        this.toggleDropdown(type);
                    });
                });

                document.querySelectorAll('[data-search]').forEach(input => {
                    input.addEventListener('input', (e) => {
                        const type = e.currentTarget.dataset.search;
                        this.handleSearch(type, e.target.value);
                    });
                });

                document.getElementById('userForm').addEventListener('submit', (e) => {
                    this.prepareFormSubmission();
                });

                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.dropdown-container')) {
                        this.closeAllDropdowns();
                    }
                });
            }

            toggleDropdown(type) {
                const dropdown = document.getElementById(`${type}Dropdown`);
                const button = document.querySelector(`[data-dropdown="${type}"]`);
                const arrow = button.querySelector('svg');

                const isOpen = dropdown.classList.contains('show');

                this.closeAllDropdowns();

                if (!isOpen) {
                    dropdown.classList.add('show');
                    arrow.style.transform = 'rotate(180deg)';
                    this.renderDropdownItems(type);
                }
            }

            closeAllDropdowns() {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
                document.querySelectorAll('[data-dropdown] svg').forEach(arrow => {
                    arrow.style.transform = 'rotate(0deg)';
                });
            }

            renderDropdownItems(type, searchTerm = '') {
                const listElement = document.getElementById(`${type}List`);
                const items = type === 'branches' ? this.branches : this.sites;
                

                listElement.innerHTML = '';

                const allOption = this.createAllOption(type);
                listElement.appendChild(allOption);

                const filteredItems = items.filter(item => {
                  const term = searchTerm?.toLowerCase() || '';
                  return (item.name?.toLowerCase().includes(term)) ||
                         (type === 'sites' && item.branch_name?.toLowerCase().includes(term));
                });


                const visibleItems = filteredItems.slice(0, 15);

                visibleItems.forEach(item => {
                    const itemElement = this.createDropdownItem(type, item);
                    listElement.appendChild(itemElement);
                });
            }

            createAllOption(type) {
                const item = document.createElement('div');
                item.className = 'dropdown-item special';

                const isSelected = type === 'branches' ? this.allBranches : this.allSites;

                item.innerHTML = `
                    <input type="checkbox" class="checkbox-input" ${isSelected ? 'checked' : ''}>
                    <span>All ${type.charAt(0).toUpperCase() + type.slice(1)}</span>
                    <div style="margin-left: auto;">
                        <span style="font-size: 0.75rem; background: #3b82f6; color: white; padding: 0.125rem 0.5rem; border-radius: 9999px;">Meta</span>
                    </div>
                `;

                item.addEventListener('click', (e) => {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = item.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                    }
                    this.handleAllOptionToggle(type, item.querySelector('input[type="checkbox"]').checked);
                });

                return item;
            }

            createDropdownItem(type, data) {
                const item = document.createElement('div');
                item.className = 'dropdown-item';

                // Check if this item should be checked based on current state
                let isSelected = false;
                if (type === 'branches') {
                    isSelected = this.allBranches || this.selectedBranches.has(data.id);
                } else {
                    isSelected = this.allSites || this.selectedSites.has(data.id);
                }

                const secondaryText = type === 'sites' ? `<div style="font-size: 0.75rem; color: #6b7280;">${data.branch_name}</div>` : '';

                item.innerHTML = `
                    <input type="checkbox" class="checkbox-input" ${isSelected ? 'checked' : ''}>
                    <div style="flex: 1;">
                        <div>${data.name}</div>
                        ${secondaryText}
                    </div>
                `;

                item.addEventListener('click', (e) => {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = item.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                    }
                    this.handleItemToggle(type, data.id, item.querySelector('input[type="checkbox"]').checked);
                });

                return item;
            }

            handleAllOptionToggle(type, isSelected) {
                if (type === 'branches') {
                    this.allBranches = isSelected;
                    if (isSelected) {
                        // Clear individual selections when "All" is selected
                        this.selectedBranches.clear();
                        this.implicitBranches.clear();
                    }
                } else {
                    this.allSites = isSelected;
                    if (isSelected) {
                        // Clear individual selections when "All" is selected
                        this.selectedSites.clear();
                        // Don't clear implicitBranches here as sites still need their branches
                    }
                }

                this.updateUI();
                // Re-render the current dropdown to update checkboxes
                const openDropdown = document.querySelector('.dropdown-menu.show');
                if (openDropdown) {
                    const dropdownType = openDropdown.id === 'branchesDropdown' ? 'branches' : 'sites';
                    const searchInput = openDropdown.querySelector('.search-input');
                    this.renderDropdownItems(dropdownType, searchInput.value);
                }
            }

            handleItemToggle(type, id, isSelected) {
                if (type === 'branches') {
                    // Turn off "All Branches" when individual branch is selected/deselected
                    if (this.allBranches) {
                        this.allBranches = false;
                    }

                    if (isSelected) {
                        this.selectedBranches.add(id);
                    } else {
                        this.selectedBranches.delete(id);
                    }
                } else {
                    // Turn off "All Sites" when individual site is selected/deselected
                    if (this.allSites) {
                        this.allSites = false;
                    }

                    if (isSelected) {
                        this.selectedSites.add(id);
                        const site = this.sites.find(s => s.id === id);
                        if (site) {
                            this.implicitBranches.add(site.branch_id);
                            this.selectedBranches.add(site.branch_id);
                        }
                    } else {
                        this.selectedSites.delete(id);
                        const site = this.sites.find(s => s.id === id);
                        if (site) {
                            const otherSitesInBranch = Array.from(this.selectedSites)
                                .some(siteId => {
                                    const s = this.sites.find(st => st.id === siteId);
                                    return s && s.branch_id === site.branch_id;
                                });

                            if (!otherSitesInBranch) {
                                this.implicitBranches.delete(site.branch_id);
                                if (this.implicitBranches.has(site.branch_id) === false) {
                                    this.selectedBranches.delete(site.branch_id);
                                }
                            }
                        }
                    }
                }

                this.updateUI();
                // Re-render the current dropdown to update checkboxes
                const openDropdown = document.querySelector('.dropdown-menu.show');
                if (openDropdown) {
                    const dropdownType = openDropdown.id === 'branchesDropdown' ? 'branches' : 'sites';
                    const searchInput = openDropdown.querySelector('.search-input');
                    this.renderDropdownItems(dropdownType, searchInput.value);
                }
            }

            handleSearch(type, searchTerm) {
                this.renderDropdownItems(type, searchTerm);
            }

            updateUI() {
                this.updatePlaceholders();
                this.updateChips();
            }

            updatePlaceholders() {
                const branchesPlaceholder = document.getElementById('branchesPlaceholder');
                const sitesPlaceholder = document.getElementById('sitesPlaceholder');

                if (this.allBranches) {
                    branchesPlaceholder.textContent = 'All Branches';
                } else if (this.selectedBranches.size > 0) {
                    branchesPlaceholder.textContent = `${this.selectedBranches.size} branch${this.selectedBranches.size > 1 ? 'es' : ''} selected`;
                } else {
                    branchesPlaceholder.textContent = 'Select branches...';
                }

                if (this.allSites) {
                    sitesPlaceholder.textContent = 'All Sites';
                } else if (this.selectedSites.size > 0) {
                    sitesPlaceholder.textContent = `${this.selectedSites.size} site${this.selectedSites.size > 1 ? 's' : ''} selected`;
                } else {
                    sitesPlaceholder.textContent = 'Select sites...';
                }
            }

            updateChips() {
                this.updateBranchChips();
                this.updateSiteChips();
            }

            updateBranchChips() {
                const container = document.getElementById('selectedBranches');
                container.innerHTML = '';

                if (this.allBranches) {
                    const chip = this.createChip('All Branches', 'special');
                    container.appendChild(chip);
                    return;
                }

                Array.from(this.selectedBranches).forEach(branchId => {
                    const branch = this.branches.find(b => b.id === branchId);
                    if (branch) {
                        const isImplicit = this.implicitBranches.has(branchId);
                        const chip = this.createChip(
                            branch.name,
                            isImplicit ? 'implicit' : 'normal',
                            !isImplicit ? () => this.removeBranch(branchId) : null,
                            isImplicit ? 'auto' : null
                        );
                        container.appendChild(chip);
                    }
                });

                if (this.selectedBranches.size === 0 && !this.allBranches) {
                    container.innerHTML = '<div style="color: #6b7280; font-size: 0.875rem;">No branches selected</div>';
                }
            }

            updateSiteChips() {
                const container = document.getElementById('selectedSites');
                container.innerHTML = '';

                if (this.allSites) {
                    const chip = this.createChip('All Sites', 'special');
                    container.appendChild(chip);
                    return;
                }

                Array.from(this.selectedSites).forEach(siteId => {
                    const site = this.sites.find(s => s.id === siteId);
                    if (site) {
                        const chip = this.createChip(
                            site.name,
                            'normal',
                            () => this.removeSite(siteId)
                        );
                        container.appendChild(chip);
                    }
                });

                if (this.selectedSites.size === 0 && !this.allSites) {
                    container.innerHTML = '<div style="color: #6b7280; font-size: 0.875rem;">No sites selected</div>';
                }
            }

            createChip(text, type = 'normal', onRemove = null, badge = null) {
                const chip = document.createElement('div');
                chip.className = `chip ${type}`;

                let badgeHtml = badge ? `<span style="font-size: 0.625rem; padding: 0.125rem 0.375rem; background: #4b5563; color: white; border-radius: 9999px;">${badge}</span>` : '';

                chip.innerHTML = `
                    <span>${text}</span>
                    ${badgeHtml}
                    ${onRemove ? '<button type="button" class="chip-remove">×</button>' : ''}
                `;

                if (onRemove) {
                    chip.querySelector('.chip-remove').addEventListener('click', onRemove);
                }

                return chip;
            }

            removeBranch(branchId) {
                this.selectedBranches.delete(branchId);
                this.sites.filter(site => site.branch_id === branchId).forEach(site => {
                    this.selectedSites.delete(site.id);
                });
                this.updateUI();
            }

            removeSite(siteId) {
                this.selectedSites.delete(siteId);
                const site = this.sites.find(s => s.id === siteId);
                if (site) {
                    const otherSitesInBranch = Array.from(this.selectedSites)
                        .some(id => {
                            const s = this.sites.find(st => st.id === id);
                            return s && s.branch_id === site.branch_id;
                        });

                    if (!otherSitesInBranch && this.implicitBranches.has(site.branch_id)) {
                        this.implicitBranches.delete(site.branch_id);
                        this.selectedBranches.delete(site.branch_id);
                    }
                }
                this.updateUI();
            }

            prepareFormSubmission() {
                document.getElementById('allBranchesInput').value = this.allBranches ? '1' : '0';
                document.getElementById('allSitesInput').value = this.allSites ? '1' : '0';

                const form = document.getElementById('userForm');

                form.querySelectorAll('.dynamic-input').forEach(input => input.remove());

                // Only add individual branches if "All Branches" is not selected
                if (!this.allBranches) {
                    Array.from(this.selectedBranches).forEach(branchId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'branches[]';
                        input.value = branchId;
                        input.className = 'dynamic-input';
                        form.appendChild(input);
                    });
                }

                // Only add individual sites if "All Sites" is not selected
                if (!this.allSites) {
                    Array.from(this.selectedSites).forEach(siteId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'sites[]';
                        input.value = siteId;
                        input.className = 'dynamic-input';
                        form.appendChild(input);
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new UserAccessManager();
        });
    </script>
</x-app-layout>
