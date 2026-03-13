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
                <a href="{{ route('settings.sites.index') }}" class="back-button" title="Back to Sites">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Create New Site</h2>
            </div>
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
            max-width: 4xl;
            margin: 0 auto;
        }

        /* Form container */
        .form-container {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        @media (prefers-color-scheme: dark) {
            .form-container {
                background-color: #1f2937;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.25);
            }
        }

        .form-header {
            background-color: #f8fafc;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .form-header {
                background-color: #374151;
                border-bottom-color: #4b5563;
            }
        }

        .form-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        @media (prefers-color-scheme: dark) {
            .form-title {
                color: #e5e7eb;
            }
        }

        .form-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }

        @media (prefers-color-scheme: dark) {
            .form-subtitle {
                color: #9ca3af;
            }
        }

        .form-content {
            padding: 2rem;
        }

        /* Form sections */
        .form-section {
            margin-bottom: 2rem;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .section-title {
                color: #d1d5db;
                border-bottom-color: #4b5563;
            }
        }

        /* Form grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .form-grid.two-column {
            grid-template-columns: 1fr 1fr;
        }

        .form-grid.three-column {
            grid-template-columns: 1fr 1fr 1fr;
        }

        @media (max-width: 768px) {
            .form-grid.two-column,
            .form-grid.three-column {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        /* Labels */
        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        @media (prefers-color-scheme: dark) {
            .form-label {
                color: #d1d5db;
            }
        }

        .required-indicator {
            color: #dc2626;
            font-size: 1rem;
        }

        /* Form inputs */
        .form-input {
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            color: #1f2937;
            background-color: white;
            transition: all 0.15s ease-in-out;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .form-input:hover {
            border-color: #9ca3af;
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .form-input {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .form-input:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .form-input:hover {
                border-color: #6b7280;
            }

            .form-input::placeholder {
                color: #9ca3af;
            }
        }

        /* Select inputs */
        .form-select {
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            color: #1f2937;
            background-color: white;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .form-select:hover {
            border-color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .form-select {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .form-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .form-select:hover {
                border-color: #6b7280;
            }
        }

        /* Custom Dropdown Styles */
        .custom-dropdown {
            position: relative;
        }

        .dropdown-toggle {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            color: #1f2937;
            background-color: white;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-toggle:hover {
            border-color: #9ca3af;
        }

        .dropdown-toggle:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .dropdown-toggle.open {
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-toggle {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .dropdown-toggle:hover {
                border-color: #6b7280;
            }

            .dropdown-toggle:focus,
            .dropdown-toggle.open {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }
        }

        .dropdown-arrow {
            transition: transform 0.2s ease;
        }

        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 50;
            background-color: white;
            border: 2px solid #d1d5db;
            border-top: none;
            border-radius: 0 0 0.5rem 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .dropdown-menu.open {
            display: block;
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-menu {
                background-color: #374151;
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

        .dropdown-search input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
            color: #1f2937;
        }

        .dropdown-search input:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 2px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-search input {
                background-color: #4b5563;
                border-color: #6b7280;
                color: #e5e7eb;
            }

            .dropdown-search input:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 2px rgba(167, 98, 44, 0.2);
            }
        }

        .dropdown-option {
            padding: 0.75rem;
            cursor: pointer;
            transition: background-color 0.15s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .dropdown-option:hover {
            background-color: #f8fafc;
        }

        .dropdown-option.selected {
            background-color: var(--primary-colour);
            color: white;
        }

        .dropdown-option.hidden {
            display: none;
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-option {
                border-bottom-color: #4b5563;
            }

            .dropdown-option:hover {
                background-color: #4b5563;
            }

            .dropdown-option.selected {
                background-color: var(--primary-colour);
                color: white;
            }
        }

        .branch-code {
            font-family: monospace;
            background-color: #f3f4f6;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            margin-right: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .branch-code {
                background-color: #4b5563;
            }
        }

        .dropdown-option.selected .branch-code {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Textarea */
        .form-textarea {
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            color: #1f2937;
            background-color: white;
            resize: vertical;
            min-height: 100px;
            transition: all 0.15s ease-in-out;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        .form-textarea:hover {
            border-color: #9ca3af;
        }

        .form-textarea::placeholder {
            color: #9ca3af;
        }

        @media (prefers-color-scheme: dark) {
            .form-textarea {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .form-textarea:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }

            .form-textarea:hover {
                border-color: #6b7280;
            }

            .form-textarea::placeholder {
                color: #9ca3af;
            }
        }

        /* Checkbox */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background-color: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.15s ease-in-out;
        }

        .checkbox-group:hover {
            border-color: var(--primary-colour);
            background-color: #fef7f0;
        }

        @media (prefers-color-scheme: dark) {
            .checkbox-group {
                background-color: #374151;
                border-color: #4b5563;
            }

            .checkbox-group:hover {
                border-color: var(--primary-colour);
                background-color: rgba(167, 98, 44, 0.1);
            }
        }

        .checkbox-input {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary-colour);
            background-color: white;
            border: 2px solid #d1d5db;
            border-radius: 0.25rem;
            cursor: pointer;
        }

        .checkbox-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .checkbox-input {
                background-color: #374151;
                border-color: #4b5563;
            }

            .checkbox-input:focus {
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }
        }

        .checkbox-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
        }

        @media (prefers-color-scheme: dark) {
            .checkbox-label {
                color: #d1d5db;
            }
        }

        /* Error styling */
        .form-error {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-input.error,
        .form-select.error,
        .form-textarea.error,
        .dropdown-toggle.error {
            border-color: #dc2626;
        }

        .form-input.error:focus,
        .form-select.error:focus,
        .form-textarea.error:focus,
        .dropdown-toggle.error:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Form actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1.5rem 2rem;
            background-color: #f8fafc;
            border-top: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .form-actions {
                background-color: #374151;
                border-top-color: #4b5563;
            }
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: var(--primary-colour);
            color: white;
            border-color: var(--primary-colour);
        }

        .btn-primary:hover {
            background-color: #924f25;
            border-color: #924f25;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
            border-color: #6b7280;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            border-color: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 640px) {
            .form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-container {
                padding: 1.5rem 0.5rem;
            }

            .form-content {
                padding: 1.5rem;
            }

            .form-actions {
                padding: 1.25rem 1.5rem;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            <div class="form-container">
                <div class="form-header">
                    <h3 class="form-title">Site Information</h3>
                    <p class="form-subtitle">Add a new site to your system. Fields marked with * are required.</p>
                </div>

                <form method="POST" action="{{ route('settings.sites.store') }}">
                    @csrf

                    <div class="form-content">
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h4 class="section-title">Basic Information</h4>
                            <div class="form-grid three-column">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        Site Name
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        class="form-input @error('name') error @enderror"
                                        placeholder="Enter site name"
                                        required
                                    />
                                    @error('name')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="customer_id" class="form-label">
                                        Customer
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <select
                                        id="customer_id"
                                        name="customer_id"
                                        class="form-select @error('customer_id') error @enderror"
                                        required
                                    >
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="branch_id" class="form-label">
                                        Branch
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <div class="custom-dropdown">
                                        <div class="dropdown-toggle @error('branch_id') error @enderror" id="branchDropdown">
                                            <span class="dropdown-text">
                                                @if(old('branch_id'))
                                                    @php
                                                        $selectedBranch = $branches->find(old('branch_id'));
                                                    @endphp
                                                    @if($selectedBranch)
                                                        <span class="branch-code">{{ $selectedBranch->branch_code }}</span>{{ $selectedBranch->branch_name }}
                                                    @else
                                                        Select Branch
                                                    @endif
                                                @else
                                                    Select Branch
                                                @endif
                                            </span>
                                            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                        <div class="dropdown-menu" id="branchDropdownMenu">
                                            <div class="dropdown-search">
                                                <input type="text" placeholder="Search branches..." id="branchSearch">
                                            </div>
                                            <div class="dropdown-option selected" data-value="">
                                                <em>No Branch</em>
                                            </div>
                                            @foreach($branches as $branch)
                                                <div class="dropdown-option" data-value="{{ $branch->id }}" data-search="{{ strtolower($branch->branch_code . ' ' . $branch->branch_name) }}">
                                                    <span class="branch-code">{{ $branch->branch_code }}</span>{{ $branch->branch_name }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="branch_id" id="branchIdInput" value="{{ old('branch_id') }}">
                                    </div>
                                    @error('branch_id')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group full-width">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea
                                        id="description"
                                        name="description"
                                        class="form-textarea @error('description') error @enderror"
                                        placeholder="Enter site description or notes"
                                        rows="3"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="form-section">
                            <h4 class="section-title">Address Information</h4>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="address" class="form-label">Street Address</label>
                                    <input
                                        type="text"
                                        id="address"
                                        name="address"
                                        value="{{ old('address') }}"
                                        class="form-input @error('address') error @enderror"
                                        placeholder="Enter street address"
                                    />
                                    @error('address')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-grid three-column">
                                    <div class="form-group">
                                        <label for="city" class="form-label">City</label>
                                        <input
                                            type="text"
                                            id="city"
                                            name="city"
                                            value="{{ old('city') }}"
                                            class="form-input @error('city') error @enderror"
                                            placeholder="Enter city"
                                        />
                                        @error('city')
                                        <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="county" class="form-label">County</label>
                                        <input
                                            type="text"
                                            id="county"
                                            name="county"
                                            value="{{ old('county') }}"
                                            class="form-input @error('county') error @enderror"
                                            placeholder="Enter county"
                                        />
                                        @error('county')
                                        <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="postal_code" class="form-label">Postal Code</label>
                                        <input
                                            type="text"
                                            id="postal_code"
                                            name="postal_code"
                                            value="{{ old('postal_code') }}"
                                            class="form-input @error('postal_code') error @enderror"
                                            placeholder="Enter postal code"
                                        />
                                        @error('postal_code')
                                        <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="form-section">
                            <h4 class="section-title">Site Status</h4>
                            <div class="checkbox-group">
                                <input
                                    type="checkbox"
                                    id="active"
                                    name="active"
                                    value="1"
                                    class="checkbox-input"
                                    {{ old('active', true) ? 'checked' : '' }}
                                />
                                <label for="active" class="checkbox-label">
                                    Site is active and operational
                                </label>
                            </div>
                            @error('active')
                            <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('settings.sites.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Create Site
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('branchDropdown');
            const dropdownMenu = document.getElementById('branchDropdownMenu');
            const dropdownArrow = dropdown.querySelector('.dropdown-arrow');
            const dropdownText = dropdown.querySelector('.dropdown-text');
            const hiddenInput = document.getElementById('branchIdInput');
            const searchInput = document.getElementById('branchSearch');
            const options = dropdownMenu.querySelectorAll('.dropdown-option');

            // Toggle dropdown
            dropdown.addEventListener('click', function() {
                const isOpen = dropdownMenu.classList.contains('open');
                if (isOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });

            function openDropdown() {
                dropdownMenu.classList.add('open');
                dropdown.classList.add('open');
                dropdownArrow.classList.add('rotated');
                searchInput.focus();
            }

            function closeDropdown() {
                dropdownMenu.classList.remove('open');
                dropdown.classList.remove('open');
                dropdownArrow.classList.remove('rotated');
                searchInput.value = '';
                filterOptions('');
            }

            // Handle option selection
            options.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    const text = this.innerHTML;

                    hiddenInput.value = value;
                    dropdownText.innerHTML = text || 'Select Branch ';

                    // Update selected state
                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    closeDropdown();
                });
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                filterOptions(searchTerm);
            });

            function filterOptions(searchTerm) {
                options.forEach(option => {
                    const searchData = option.getAttribute('data-search') || '';
                    if (searchData.includes(searchTerm) || option.getAttribute('data-value') === '') {
                        option.classList.remove('hidden');
                    } else {
                        option.classList.add('hidden');
                    }
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    closeDropdown();
                }
            });

            // Prevent dropdown close when clicking inside menu
            dropdownMenu.addEventListener('click', function(e) {
                if (e.target === searchInput) {
                    e.stopPropagation();
                }
            });

            // Set initial selected state based on old value
            const currentValue = hiddenInput.value;
            if (currentValue) {
                const selectedOption = dropdownMenu.querySelector(`[data-value="${currentValue}"]`);
                if (selectedOption) {
                    selectedOption.classList.add('selected');
                    dropdownText.innerHTML = selectedOption.innerHTML;
                }
                // Remove selected from "No Branch" option
                const noBranchOption = dropdownMenu.querySelector('[data-value=""]');
                if (noBranchOption) {
                    noBranchOption.classList.remove('selected');
                }
            }
        });
    </script>
</x-app-layout>
