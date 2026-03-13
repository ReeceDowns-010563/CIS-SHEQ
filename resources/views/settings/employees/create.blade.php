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
                <a href="{{ route('settings.employees.index') }}" class="back-button" title="Back to Employees">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Create New Employee</h2>
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

        /* Error styling */
        .form-error {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-input.error,
        .form-select.error {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Form actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .form-actions {
                border-top-color: #4b5563;
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: 0.5rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 2px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary-colour);
            color: white;
            border-color: var(--primary-colour);
        }

        .btn-primary:hover {
            background-color: var(--secondary-colour);
            border-color: var(--secondary-colour);
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
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper">
            <div class="form-container">
                <div class="form-header">
                    <h1 class="form-title">Create New Employee</h1>
                    <p class="form-subtitle">Add a new employee to the system</p>
                </div>

                <div class="form-content">
                    <form method="POST" action="{{ route('settings.employees.store') }}">
                        @csrf

                        <div class="form-section">
                            <h2 class="section-title">Basic Information</h2>
                            <div class="form-grid two-column">
                                <div class="form-group">
                                    <label for="employee_number" class="form-label">
                                        Employee Number
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text"
                                           name="employee_number"
                                           id="employee_number"
                                           value="{{ old('employee_number') }}"
                                           required
                                           class="form-input @error('employee_number') error @enderror"
                                           placeholder="Enter employee number">
                                    @error('employee_number')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        Status
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <select name="status"
                                            id="status"
                                            required
                                            class="form-select @error('status') error @enderror">
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="leaver" {{ old('status') === 'leaver' ? 'selected' : '' }}>Leaver</option>
                                    </select>
                                    @error('status')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="first_name" class="form-label">
                                        First Name
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text"
                                           name="first_name"
                                           id="first_name"
                                           value="{{ old('first_name') }}"
                                           required
                                           class="form-input @error('first_name') error @enderror"
                                           placeholder="Enter first name">
                                    @error('first_name')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="last_name" class="form-label">
                                        Last Name
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text"
                                           name="last_name"
                                           id="last_name"
                                           value="{{ old('last_name') }}"
                                           required
                                           class="form-input @error('last_name') error @enderror"
                                           placeholder="Enter last name">
                                    @error('last_name')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2 class="section-title">Contact Information</h2>
                            <div class="form-grid two-column">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        Email Address
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           value="{{ old('email') }}"
                                           required
                                           class="form-input @error('email') error @enderror"
                                           placeholder="Enter email address">
                                    @error('email')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="number" class="form-label">Phone Number</label>
                                    <input type="text"
                                           name="number"
                                           id="number"
                                           value="{{ old('number') }}"
                                           class="form-input @error('number') error @enderror"
                                           placeholder="Enter phone number">
                                    @error('number')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2 class="section-title">Work Information</h2>
                            <div class="form-grid two-column">
                                <div class="form-group">
                                    <label for="site_id" class="form-label">
                                        Site
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <select name="site_id"
                                            id="site_id"
                                            required
                                            class="form-select @error('site_id') error @enderror">
                                        <option value="">Select Site</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                                {{ $site->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('site_id')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('settings.employees.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Create Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
