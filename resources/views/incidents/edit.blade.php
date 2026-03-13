<x-app-layout>
    <x-slot name="header">
        <div class="header-container">
            <h2 class="header-title">
                <span class="header-main">Edit Accident</span>
            </h2>
            <div class="status-badge status-{{ $incident->status }}">
                {{ ucfirst($incident->status) }}
            </div>
        </div>
    </x-slot>

    <!-- Success Message Script -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showSuccess('{{ session('success') }}');
            });
        </script>
    @endif

    <div class="page-container">
        <div class="form-container">
            <div class="card">
                <!-- Progress Header -->
                <div class="progress-header">
                    <div class="progress-content">
                        <div class="progress-icon">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="progress-text">Editing Accident</span>
                        </div>
                        <div class="progress-date">
                            Created {{ $incident->created_at->format('M j, Y') }}
                        </div>
                    </div>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="error-container">
                        <div class="error-content">
                            <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="error-text">
                                <h3 class="error-title">Please fix the following errors:</h3>
                                <ul class="error-list">
                                    @foreach ($errors->all() as $error)
                                        <li>
                                            <span class="error-bullet"></span>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('incidents.update', $incident) }}" method="POST" enctype="multipart/form-data" class="form">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Basic Information</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="brief_description" class="form-label">
                                    Description
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="brief_description"
                                    name="brief_description"
                                    value="{{ old('brief_description', $incident->brief_description) }}"
                                    class="form-input"
                                    required
                                    maxlength="255"
                                    placeholder="Enter a brief description..."
                                />
                                <div class="form-help">
                                    Keep it concise and descriptive
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="incident_type_id" class="form-label">
                                    Accident Type
                                    <span class="required">*</span>
                                </label>
                                <div class="searchable-select" data-select="incident_type_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Choose incident type...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search incident types..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Choose incident type...</div>
                                            @foreach($incidentTypes as $type)
                                                <div class="select-option" data-value="{{ $type->id }}">
                                                    {{ $type->name }}
                                                </div>
                                            @endforeach
                                            <div class="select-option" data-value="other">
                                                Other (please specify)
                                            </div>
                                        </div>
                                    </div>
                                    <select id="incident_type_id" name="incident_type_id" class="hidden-select" required>
                                        <option value="">Choose incident type...</option>
                                        @foreach($incidentTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('incident_type_id', $incident->incident_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                        <option value="other" {{ old('incident_type_id', $incident->incident_type_id) === 'other' ? 'selected' : '' }}>
                                            Other (please specify)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group full-width" id="incident_type_other_wrapper" style="display: none;">
                                <label for="incident_type_other_description" class="form-label">
                                    Please describe the incident type
                                    <span class="required">*</span>
                                </label>
                                <textarea
                                    id="incident_type_other_description"
                                    name="incident_type_other_description"
                                    class="form-input"
                                    maxlength="255"
                                    rows="3"
                                    placeholder="Provide a detailed description of the incident type..."
                                    data-validation="incident_type_other_validation"
                                >{{ old('incident_type_other_description', $incident->incident_type_other_description) }}</textarea>
                                <div class="form-help">
                                    Maximum 255 characters
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label for="location" class="form-label">
                                    Location
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="location"
                                    name="location"
                                    value="{{ old('location', $incident->location) }}"
                                    class="form-input"
                                    required
                                    placeholder="Enter specific location..."
                                />
                            </div>

                            <div class="form-group full-width">
                                <label for="what_happened" class="form-label">
                                    What Happened
                                    <span class="required">*</span>
                                </label>
                                <textarea
                                    id="what_happened"
                                    name="what_happened"
                                    rows="4"
                                    class="form-textarea"
                                    required
                                    placeholder="Describe what happened in detail..."
                                >{{ old('what_happened', $incident->what_happened) }}</textarea>
                                <div class="form-help">
                                    Provide a detailed account of the incident
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label for="additional_information" class="form-label">
                                    Additional Information
                                </label>
                                <textarea
                                    id="additional_information"
                                    name="additional_information"
                                    rows="3"
                                    class="form-textarea"
                                    placeholder="Any additional relevant information..."
                                >{{ old('additional_information', $incident->additional_information) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Date and Time Information</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="date_of_occurrence" class="form-label">
                                    Date of Occurrence
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="date_of_occurrence"
                                    name="date_of_occurrence"
                                    value="{{ old('date_of_occurrence', $incident->date_of_occurrence?->format('Y-m-d')) }}"
                                    class="form-input"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="time_of_occurrence" class="form-label">
                                    Time of Occurrence
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="time"
                                    id="time_of_occurrence"
                                    name="time_of_occurrence"
                                    value="{{ old('time_of_occurrence', $incident->time_of_occurrence ? \Carbon\Carbon::parse($incident->time_of_occurrence)->format('H:i') : '') }}"
                                    class="form-input"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Work Location Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Work Location</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="branch_id" class="form-label">
                                    Branch
                                    <span class="required">*</span>
                                </label>
                                <div class="searchable-select" data-select="branch_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Select branch...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search branches..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Select branch...</div>
                                            @foreach($branches as $branch)
                                                <div class="select-option" data-value="{{ $branch->id }}">
                                                    {{ $branch->display_name ?? $branch->branch_name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="branch_id" name="branch_id" class="hidden-select" required>
                                        <option value="">Select branch...</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ old('branch_id', $incident->branch_id) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->display_name ?? $branch->branch_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="site_id" class="form-label">
                                    Site
                                </label>
                                <div class="searchable-select" data-select="site_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Select site...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search sites..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Select site...</div>
                                            @foreach($sites as $site)
                                                <div class="select-option" data-value="{{ $site->id }}" data-branch-id="{{ $site->branch_id }}">
                                                    {{ $site->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="site_id" name="site_id" class="hidden-select">
                                        <option value="">Select site...</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" data-branch-id="{{ $site->branch_id }}" {{ old('site_id', $incident->site_id) == $site->id ? 'selected' : '' }}>
                                                {{ $site->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Affected Person Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Affected Person</h3>
                        </div>

                        <div class="form-group">
                            <label for="affected_person_source" class="form-label">
                                Affected Person Type
                                <span class="required">*</span>
                            </label>
                            <select id="affected_person_source" name="affected_person_source" class="form-input" required>
                                <option value="Employee" {{ old('affected_person_source', $incident->affected_person_source) == 'Employee' ? 'selected' : '' }}>Employee</option>
                                <option value="Customer" {{ old('affected_person_source', $incident->affected_person_source) == 'Customer' ? 'selected' : '' }}>Customer</option>
                                <option value="Other" {{ old('affected_person_source', $incident->affected_person_source) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div id="affected_employee_wrapper" style="{{ old('affected_person_source', $incident->affected_person_source) === 'Employee' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="affected_employee_id" class="form-label">
                                    Affected Employee
                                    <span class="required">*</span>
                                </label>
                                <div class="searchable-select" data-select="affected_employee_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Choose employee...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search employees..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Choose employee...</div>
                                            @foreach($employees as $employee)
                                                <div class="select-option" data-value="{{ $employee->id }}" data-site-id="{{ $employee->site_id }}">
                                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                                    <span class="employee-site-badge">{{ $employee->site->name ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="affected_employee_id" name="affected_employee_id" class="hidden-select">
                                        <option value="">Choose employee...</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" data-site-id="{{ $employee->site_id }}" {{ old('affected_employee_id', $incident->affected_employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="affected_customer_wrapper" style="{{ old('affected_person_source', $incident->affected_person_source) === 'Customer' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="affected_customer_id" class="form-label">
                                    Affected Customer
                                    <span class="required">*</span>
                                </label>
                                <div class="searchable-select" data-select="affected_customer_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Choose customer...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search customers..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Choose customer...</div>
                                            @foreach($customers as $customer)
                                                <div class="select-option" data-value="{{ $customer->id }}">
                                                    {{ $customer->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="affected_customer_id" name="affected_customer_id" class="hidden-select">
                                        <option value="">Choose customer...</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('affected_customer_id', $incident->affected_customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="affected_person_other_wrapper" style="{{ old('affected_person_source', $incident->affected_person_source) === 'Other' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="affected_person_other" class="form-label">
                                    Please Specify
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="affected_person_other"
                                    name="affected_person_other"
                                    value="{{ old('affected_person_other', $incident->affected_person_other) }}"
                                    class="form-input"
                                    placeholder="Enter details about the affected person"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Medical Information</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="treatment_type_id" class="form-label">
                                    Treatment Type
                                    <span class="required">*</span>
                                </label>
                                <div class="searchable-select" data-select="treatment_type_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Select treatment...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search treatments..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Select treatment...</div>
                                            @foreach($treatmentTypes as $treatment)
                                                <div class="select-option" data-value="{{ $treatment->id }}">
                                                    {{ $treatment->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="treatment_type_id" name="treatment_type_id" class="hidden-select" required>
                                        <option value="">Select treatment...</option>
                                        @foreach($treatmentTypes as $treatment)
                                            <option value="{{ $treatment->id }}" {{ old('treatment_type_id', $incident->treatment_type_id) == $treatment->id ? 'selected' : '' }}>
                                                {{ $treatment->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Updated Multi-Select Body Parts Field -->
                            <div class="form-group full-width">
                                <label class="form-label">
                                    Body Parts Affected
                                </label>
                                <div class="multi-select-container" id="body_parts_container">
                                    <div class="multi-select-display" tabindex="0">
                                        <div class="selected-items" id="body_parts_selected">
                                            <span class="placeholder">Select body parts...</span>
                                        </div>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="multi-select-dropdown">
                                        <div class="multi-select-search">
                                            <input type="text" placeholder="Search body parts..." class="search-input">
                                        </div>
                                        <div class="multi-select-options">
                                            @foreach($bodyParts as $bodyPart)
                                                <label class="multi-select-option">
                                                    <input type="checkbox" name="body_part_id[]" value="{{ $bodyPart->id }}"
                                                        {{ (is_array(old('body_part_id', $incident->body_part_id ?? [])) && in_array($bodyPart->id, old('body_part_id', $incident->body_part_id ?? []))) ? 'checked' : '' }}>
                                                    <span class="checkmark"></span>
                                                    <span class="option-text">{{ $bodyPart->name }}</span>
                                                </label>
                                            @endforeach
                                            <label class="multi-select-option">
                                                <input type="checkbox" name="body_part_id[]" value="other"
                                                    {{ (!empty($incident->body_part_other) || (is_array(old('body_part_id', [])) && in_array('other', old('body_part_id', [])))) ? 'checked' : '' }}>
                                                <span class="checkmark"></span>
                                                <span class="option-text">Other</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="body_part_other_container" style="{{ (!empty($incident->body_part_other) || (is_array(old('body_part_id', [])) && in_array('other', old('body_part_id', [])))) ? '' : 'display: none;' }}">
                                <label for="body_part_other" class="form-label">
                                    Specify Other Body Part
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="body_part_other"
                                    name="body_part_other"
                                    value="{{ old('body_part_other', $incident->body_part_other) }}"
                                    class="form-input"
                                    placeholder="Please specify the affected body part"
                                />
                            </div>

                            <div class="form-group">
                                <label for="injury_type_id" class="form-label">
                                    Injury Type
                                </label>
                                <div class="searchable-select" data-select="injury_type_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Select injury type...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search injury types..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Select injury type...</div>
                                            @foreach($injuryTypes as $injuryType)
                                                <div class="select-option" data-value="{{ $injuryType->id }}">
                                                    {{ $injuryType->name }}
                                                </div>
                                            @endforeach
                                            <div class="select-option" data-value="other">Other</div>
                                        </div>
                                    </div>
                                    <select id="injury_type_id" name="injury_type_id" class="hidden-select">
                                        <option value="">Select injury type...</option>
                                        @foreach($injuryTypes as $injuryType)
                                            <option value="{{ $injuryType->id }}" {{ old('injury_type_id', $incident->injury_type_id) == $injuryType->id ? 'selected' : '' }}>
                                                {{ $injuryType->name }}
                                            </option>
                                        @endforeach
                                        <option value="other" {{ (old('injury_type_id') == 'other' || (!$incident->injury_type_id && $incident->injury_type_other)) ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="injury_type_other_container" style="{{ (old('injury_type_id') == 'other' || (!$incident->injury_type_id && $incident->injury_type_other)) ? '' : 'display: none;' }}">
                                <label for="injury_type_other" class="form-label">
                                    Specify Other Nature of Injury/Illness
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="injury_type_other"
                                    name="injury_type_other"
                                    value="{{ old('injury_type_other', $incident->injury_type_other) }}"
                                    class="form-input"
                                    placeholder="Please specify the nature of injury/illness"
                                />
                            </div>

                            <div class="form-group full-width">
                                <label for="physician_details" class="form-label">
                                    Physician / Health Care Provider Details
                                </label>
                                <textarea
                                    id="physician_details"
                                    name="physician_details"
                                    rows="3"
                                    class="form-textarea"
                                    placeholder="Enter physician or medical facility details..."
                                >{{ old('physician_details', $incident->physician_details) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Reported By Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Reported By</h3>
                        </div>

                        <div class="form-group">
                            <label for="reported_by_source" class="form-label">
                                Reported By Type
                                <span class="required">*</span>
                            </label>
                            <select id="reported_by_source" name="reported_by_source" class="form-input" required>
                                <option value="Employee" {{ old('reported_by_source', $incident->reported_by_source) == 'Employee' ? 'selected' : '' }}>Employee</option>
                                <option value="Customer" {{ old('reported_by_source', $incident->reported_by_source) == 'Customer' ? 'selected' : '' }}>Customer</option>
                                <option value="Other" {{ old('reported_by_source', $incident->reported_by_source) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div id="reported_employee_wrapper" style="{{ old('reported_by_source', $incident->reported_by_source) === 'Employee' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="reported_employee_id" class="form-label">
                                    Reported By (Employee)
                                    <span class="required">*</span>
                                </label>
                                <div class="searchable-select" data-select="reported_employee_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Choose employee...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search employees..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Choose employee...</div>
                                            @foreach($employees as $employee)
                                                <div class="select-option" data-value="{{ $employee->id }}" data-site-id="{{ $employee->site_id }}">
                                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                                    <span class="employee-site-badge">{{ $employee->site->name ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="reported_employee_id" name="reported_employee_id" class="hidden-select">
                                        <option value="">Choose employee...</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" data-site-id="{{ $employee->site_id }}" {{ old('reported_employee_id', $incident->reported_employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="reported_customer_wrapper" style="{{ old('reported_by_source', $incident->reported_by_source) === 'Customer' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="reported_customer_id" class="form-label">
                                    Reported By (Customer)
                                    <span class="required">*</span>
                                </label>
                                <div class="searchable-select" data-select="reported_customer_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Choose customer...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search customers..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Choose customer...</div>
                                            @foreach($customers as $customer)
                                                <div class="select-option" data-value="{{ $customer->id }}">
                                                    {{ $customer->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="reported_customer_id" name="reported_customer_id" class="hidden-select">
                                        <option value="">Choose customer...</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('reported_customer_id', $incident->reported_customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="reported_by_other_wrapper" style="{{ old('reported_by_source', $incident->reported_by_source) === 'Other' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="reported_by_other" class="form-label">
                                    Please Specify
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="reported_by_other"
                                    name="reported_by_other"
                                    value="{{ old('reported_by_other', $incident->reported_by_other) }}"
                                    class="form-input"
                                    placeholder="Enter details about who reported it"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Administrative Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Administrative</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="coordinator_id" class="form-label">
                                    Coordinator
                                </label>
                                <div class="searchable-select" data-select="coordinator_id">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">Select coordinator...</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search coordinators..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            <div class="select-option" data-value="">Select coordinator...</div>
                                            @foreach($sheqUsers as $user)
                                                <div class="select-option" data-value="{{ $user->id }}">
                                                    {{ $user->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="coordinator_id" name="coordinator_id" class="hidden-select">
                                        <option value="">Select coordinator...</option>
                                        @foreach($sheqUsers as $user)
                                            <option value="{{ $user->id }}" {{ old('coordinator_id', $incident->coordinator_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status" class="form-label">
                                    Status
                                </label>
                                <div class="searchable-select" data-select="status">
                                    <div class="select-display" tabindex="0">
                                        <span class="select-value">{{ ucfirst($incident->status) }}</span>
                                        <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                    <div class="select-dropdown">
                                        <div class="select-search">
                                            <input type="text" placeholder="Search statuses..." class="search-input">
                                        </div>
                                        <div class="select-options">
                                            @foreach(['pending', 'investigating', 'completed', 'closed'] as $status)
                                                <div class="select-option" data-value="{{ $status }}">
                                                    {{ ucfirst($status) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select id="status" name="status" class="hidden-select">
                                        @foreach(['pending', 'investigating', 'completed', 'closed'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $incident->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Attachments</h3>
                        </div>

                        @if($incident->attachments && count($incident->attachments) > 0)
                            <div class="attachments-current">
                                <h4 class="attachments-title">Current Attachments:</h4>
                                <div class="attachments-list">
                                    @foreach($incident->attachments as $index => $attachment)
                                        <div class="attachment-item">
                                            <div class="attachment-info">
                                                <div class="attachment-icon">
                                                    @php
                                                        $fileExtension = strtolower(pathinfo($attachment['name'] ?? '', PATHINFO_EXTENSION));
                                                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                        $isPdf = $fileExtension === 'pdf';
                                                    @endphp

                                                    @if($isImage)
                                                        <svg class="icon text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    @elseif($isPdf)
                                                        <svg class="icon text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="icon text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="attachment-details">
                                                    <span class="attachment-name">{{ $attachment['name'] ?? 'Attachment ' . ($index + 1) }}</span>
                                                    @if(isset($attachment['size']))
                                                        <span class="attachment-size">({{ number_format($attachment['size'] / 1024, 1) }} KB)</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="attachment-actions">
                                                @if($isImage || $isPdf)
                                                    <a href="{{ route('incidents.view-attachment', [$incident, $index]) }}"
                                                       target="_blank"
                                                       class="attachment-btn view-btn"
                                                       title="View {{ $isImage ? 'image' : 'PDF' }}">
                                                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                <a href="{{ route('incidents.download-attachment', [$incident, $index]) }}"
                                                   class="attachment-btn download-btn"
                                                   title="Download file">
                                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('incidents.remove-attachment', [$incident, $index]) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="attachment-btn remove-btn"
                                                            onclick="return confirm('Remove this attachment?')"
                                                            title="Remove attachment">
                                                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="attachments" class="form-label">
                                Add New Attachments
                            </label>
                            <div class="file-input-container">
                                <input
                                    type="file"
                                    id="attachments"
                                    name="attachments[]"
                                    multiple
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                    class="file-input"
                                />
                                <label for="attachments" class="file-input-label">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="file-label-text">Choose Files</span>
                                </label>
                            </div>
                            <div class="form-help">
                                Accepted formats: JPG, PNG, PDF, DOC, DOCX. Max file size: 10MB each.
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <div class="button-group">
                            <a href="{{ route('incidents.register') }}" class="cancel-btn">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Cancel</span>
                            </a>
                            <button type="submit" class="submit-btn">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Update Accident</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Enhanced CSS with multi-select styles -->
    <style>
        :root {
            --primary: #131F34;
            --success: #10b981;
            --error: #ef4444;
            --warning: #f59e0b;
            --bg: #ffffff;
            --bg-alt: #f9fafb;
            --border: #e5e7eb;
            --text: #111827;
            --text-muted: #6b7280;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #1f2937;
                --bg-alt: #374151;
                --border: #4b5563;
                --text: #f9fafb;
                --text-muted: #9ca3af;
            }
        }

        .page-container {
            min-height: 100vh;
            background: var(--bg-alt);
            padding: 24px 16px;
        }

        .form-container {
            max-width: 1024px;
            margin: 0 auto;
        }

        .card {
            background: var(--bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        /* Multi-select body parts styles */
        .multi-select-container {
            position: relative;
            width: 100%;
        }

        .multi-select-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 48px;
            padding: 8px 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .multi-select-display:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .multi-select-display.is-open {
            border-color: var(--primary);
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        .selected-items {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            align-items: center;
        }

        .selected-items .placeholder {
            color: var(--text-muted);
        }

        .selected-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            background: #00334C;
            color: #1e40af;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .selected-tag .remove-tag {
            cursor: pointer;
            font-weight: bold;
            color: #1e40af;
        }

        .selected-tag .remove-tag:hover {
            color: var(--error);
        }

        .multi-select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg);
            border: 1px solid var(--primary);
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: var(--shadow);
            z-index: 1000;
            max-height: 300px;
            overflow: hidden;
            display: none;
        }

        .multi-select-dropdown.is-open {
            display: block;
        }

        .multi-select-search {
            padding: 12px;
            border-bottom: 1px solid var(--border);
        }

        .multi-select-search .search-input {
            width: 100%;
            padding: 8px 12px;
            background: var(--bg-alt);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
        }

        .multi-select-search .search-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .multi-select-options {
            max-height: 200px;
            overflow-y: auto;
        }

        .multi-select-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text);
            font-size: 14px;
            cursor: pointer;
            border-bottom: 1px solid var(--border);
            transition: background-color 0.15s ease;
        }

        .multi-select-option:hover {
            background: var(--bg-alt);
        }

        .multi-select-option.hidden {
            display: none;
        }

        .multi-select-option:last-child {
            border-bottom: none;
        }

        .multi-select-option input[type="checkbox"] {
            display: none;
        }

        .checkmark {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .multi-select-option input[type="checkbox"]:checked + .checkmark {
            background: var(--primary);
            border-color: var(--primary);
        }

        .multi-select-option input[type="checkbox"]:checked + .checkmark:after {
            content: '✓';
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .option-text {
            flex: 1;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.status-pending { background: #fef3c7; color: #92400e; }
        .status-badge.status-investigating { background: #00334C; color: #1e40af; }
        .status-badge.status-completed { background: #d1fae5; color: #065f46; }
        .status-badge.status-closed { background: var(--bg-alt); color: var(--text-muted); }

        .progress-header {
            background: linear-gradient(135deg, #131F34 0%, #131F34 100%);
            color: white;
            padding: 24px;
        }

        .progress-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .progress-icon {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-text {
            font-size: 18px;
            font-weight: 600;
        }

        .progress-date {
            font-size: 14px;
            opacity: 0.9;
        }

        .error-container {
            margin: 24px;
            padding: 16px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid var(--error);
            border-radius: 8px;
        }

        .error-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .error-icon {
            width: 20px;
            height: 20px;
            color: var(--error);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .error-title {
            font-size: 14px;
            font-weight: 600;
            color: #991b1b;
            margin: 0 0 8px 0;
        }

        .error-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .error-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 14px;
            color: #991b1b;
            margin-bottom: 4px;
        }

        .error-bullet {
            width: 4px;
            height: 4px;
            background: var(--error);
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 8px;
        }

        .form {
            padding: 32px 24px;
        }

        .section-container {
            background: var(--bg-alt);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border);
        }

        .section-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #131F34 0%, #131F34 100%);
            border-radius: 12px;
            color: white;
            flex-shrink: 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }

        .required {
            color: var(--error);
            margin-left: 4px;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: var(--text-muted);
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-help {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Searchable Select Styles */
        .searchable-select {
            position: relative;
            width: 100%;
        }

        .hidden-select {
            display: none;
        }

        .select-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 12px 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .select-display:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .select-display.is-open {
            border-color: var(--primary);
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        .select-value {
            flex: 1;
            text-align: left;
            color: var(--text);
        }

        .select-value.placeholder {
            color: var(--text-muted);
        }

        .select-chevron {
            width: 20px;
            height: 20px;
            color: var(--text-muted);
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .select-display.is-open .select-chevron {
            transform: rotate(180deg);
        }

        .select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg);
            border: 1px solid var(--primary);
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: var(--shadow);
            z-index: 1000;
            max-height: 320px;
            overflow: hidden;
            display: none;
        }

        .select-dropdown.is-open {
            display: block;
        }

        .select-search {
            padding: 12px;
            border-bottom: 1px solid var(--border);
        }

        .search-input {
            width: 100%;
            padding: 8px 12px;
            background: var(--bg-alt);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .select-options {
            max-height: 240px;
            overflow-y: auto;
        }

        .select-option {
            padding: 12px 16px;
            color: var(--text);
            font-size: 14px;
            cursor: pointer;
            border-bottom: 1px solid var(--border);
            transition: background-color 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .select-option:hover {
            background: var(--bg-alt);
        }

        .select-option.selected {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            font-weight: 500;
        }

        .select-option.hidden {
            display: none;
        }

        .select-option.dimmed {
            opacity: 0.5;
        }

        .select-option:last-child {
            border-bottom: none;
        }

        .employee-site-badge {
            font-size: 11px;
            color: var(--text-muted);
            background: var(--bg-alt);
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }

        /* Custom scrollbar for dropdown */
        .select-options::-webkit-scrollbar,
        .multi-select-options::-webkit-scrollbar {
            width: 6px;
        }

        .select-options::-webkit-scrollbar-track,
        .multi-select-options::-webkit-scrollbar-track {
            background: var(--bg-alt);
        }

        .select-options::-webkit-scrollbar-thumb,
        .multi-select-options::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px;
        }

        .select-options::-webkit-scrollbar-thumb:hover,
        .multi-select-options::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        .attachments-current {
            margin-bottom: 24px;
        }

        .attachments-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 12px;
        }

        .attachments-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        .attachment-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .attachment-icon {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }

        .attachment-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .attachment-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
        }

        .attachment-size {
            font-size: 12px;
            color: var(--text-muted);
        }

        .attachment-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .attachment-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .view-btn {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .view-btn:hover {
            background: rgba(34, 197, 94, 0.2);
        }

        .download-btn {
            background: rgba(59, 130, 246, 0.1);
            color: #131F34;
        }

        .download-btn:hover {
            background: rgba(59, 130, 246, 0.2);
        }

        .remove-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .remove-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .file-input-container {
            position: relative;
        }

        .file-input {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 120px;
            border: 2px dashed var(--border);
            border-radius: 8px;
            background: var(--bg-alt);
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            gap: 8px;
            padding: 24px;
        }

        .file-input-label:hover {
            border-color: var(--primary);
            background: var(--bg);
        }

        .file-label-text {
            font-size: 14px;
            font-weight: 500;
        }

        .file-input-label.has-files {
            border-color: var(--success);
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .action-buttons {
            position: sticky;
            bottom: 0;
            background: var(--bg);
            border-top: 2px solid var(--border);
            padding: 24px;
            margin: 0 -24px -32px -24px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        .submit-btn,
        .cancel-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submit-btn {
            background: linear-gradient(135deg, #131F34 0%, #131F34 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .submit-btn:hover {
            transform: translateY(-1px);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .cancel-btn {
            background: var(--bg-alt);
            color: var(--text-muted);
            border: 2px solid var(--border);
        }

        .cancel-btn:hover {
            background: var(--bg);
            color: var(--text);
        }

        .icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 16px 8px;
            }

            .form {
                padding: 24px 16px;
            }

            .section-container {
                padding: 16px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .button-group {
                flex-direction: column-reverse;
                gap: 12px;
            }

            .submit-btn,
            .cancel-btn {
                width: 100%;
                justify-content: center;
            }

            .header-container {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .progress-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>

    <!-- Enhanced JavaScript with FIXED employee filtering -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize multi-select body parts
            initializeMultiSelectBodyParts();

            // Handle Injury Type "Other" option
            const injuryTypeSelect = document.getElementById('injury_type_id');
            const injuryTypeOtherContainer = document.getElementById('injury_type_other_container');
            const injuryTypeOtherInput = document.getElementById('injury_type_other');

            function toggleInjuryTypeOther() {
                const isOther = injuryTypeSelect.value === 'other';
                if (isOther) {
                    injuryTypeOtherContainer.style.display = 'block';
                    injuryTypeOtherInput.setAttribute('required', 'required');
                } else {
                    injuryTypeOtherContainer.style.display = 'none';
                    injuryTypeOtherInput.removeAttribute('required');
                    injuryTypeOtherInput.value = '';
                }
            }

            // Initialize on page load
            toggleInjuryTypeOther();

            // Handle accident type "Other" visibility
            const incidentTypeSelect = document.getElementById('incident_type_id');
            const incidentTypeOtherWrapper = document.getElementById('incident_type_other_wrapper');
            const incidentTypeOtherField = document.getElementById('incident_type_other_description');

            if (incidentTypeSelect && incidentTypeOtherWrapper) {
                if (incidentTypeOtherField.value.trim() !== '') {
                    incidentTypeSelect.value = 'other';
                }

                const toggleAccidentTypeOther = () => {
                    if (incidentTypeSelect.value === 'other') {
                        incidentTypeOtherWrapper.style.display = 'block';
                        incidentTypeOtherField.required = true;
                    } else {
                        incidentTypeOtherWrapper.style.display = 'none';
                        incidentTypeOtherField.required = false;
                    }
                };

                incidentTypeSelect.addEventListener('change', toggleAccidentTypeOther);
                toggleAccidentTypeOther();
            }

            // Initialize searchable selects
            initializeSearchableSelects();

            // Initialize branch/site/employee filtering
            initializeBranchFiltering();

            // File input enhancement
            const fileInput = document.getElementById('attachments');
            const fileLabel = document.querySelector('.file-input-label');
            const fileLabelText = document.querySelector('.file-label-text');

            if (fileInput && fileLabel && fileLabelText) {
                fileInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    if (files.length > 0) {
                        fileLabel.classList.add('has-files');
                        fileLabelText.textContent = `${files.length} file(s) selected`;
                    } else {
                        fileLabel.classList.remove('has-files');
                        fileLabelText.textContent = 'Choose Files';
                    }
                });
            }

            // Form submission enhancement
            const form = document.querySelector('.form');
            const submitBtn = document.querySelector('.submit-btn');

            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                    submitBtn.innerHTML = `
                        <svg class="icon animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Updating...</span>
                    `;
                });
            }

            // Handle Affected Person Source visibility
            const affectedPersonSourceSelect = document.getElementById('affected_person_source');
            if (affectedPersonSourceSelect) {
                affectedPersonSourceSelect.addEventListener('change', function() {
                    const sourceValue = this.value;
                    document.getElementById('affected_employee_wrapper').style.display = sourceValue === 'Employee' ? 'block' : 'none';
                    document.getElementById('affected_customer_wrapper').style.display = sourceValue === 'Customer' ? 'block' : 'none';
                    document.getElementById('affected_person_other_wrapper').style.display = sourceValue === 'Other' ? 'block' : 'none';
                });
            }

            // Handle Reported By Source visibility
            const reportedBySourceSelect = document.getElementById('reported_by_source');
            if (reportedBySourceSelect) {
                reportedBySourceSelect.addEventListener('change', function() {
                    const sourceValue = this.value;
                    document.getElementById('reported_employee_wrapper').style.display = sourceValue === 'Employee' ? 'block' : 'none';
                    document.getElementById('reported_customer_wrapper').style.display = sourceValue === 'Customer' ? 'block' : 'none';
                    document.getElementById('reported_by_other_wrapper').style.display = sourceValue === 'Other' ? 'block' : 'none';
                });
            }
        });

        function initializeMultiSelectBodyParts() {
            const container = document.getElementById('body_parts_container');
            const display = container.querySelector('.multi-select-display');
            const dropdown = container.querySelector('.multi-select-dropdown');
            const selectedItems = document.getElementById('body_parts_selected');
            const searchInput = container.querySelector('.search-input');
            const options = container.querySelectorAll('.multi-select-option');
            const bodyPartOtherContainer = document.getElementById('body_part_other_container');
            const bodyPartOtherInput = document.getElementById('body_part_other');

            updateSelectedDisplay();

            display.addEventListener('click', function() {
                const isOpen = dropdown.classList.contains('is-open');
                dropdown.classList.toggle('is-open', !isOpen);
                display.classList.toggle('is-open', !isOpen);

                if (!isOpen) {
                    searchInput.focus();
                }
            });

            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    dropdown.classList.remove('is-open');
                    display.classList.remove('is-open');
                }
            });

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                options.forEach(option => {
                    const text = option.querySelector('.option-text').textContent.toLowerCase();
                    option.classList.toggle('hidden', !text.includes(searchTerm));
                });
            });

            options.forEach(option => {
                const checkbox = option.querySelector('input[type="checkbox"]');
                checkbox.addEventListener('change', function() {
                    updateSelectedDisplay();
                    updateOtherContainer();
                });
            });

            function updateSelectedDisplay() {
                const checkedBoxes = container.querySelectorAll('input[type="checkbox"]:checked');
                selectedItems.innerHTML = '';

                if (checkedBoxes.length === 0) {
                    const placeholder = document.createElement('span');
                    placeholder.className = 'placeholder';
                    placeholder.textContent = 'Select body parts...';
                    selectedItems.appendChild(placeholder);
                } else {
                    checkedBoxes.forEach(checkbox => {
                        const label = checkbox.closest('.multi-select-option').querySelector('.option-text').textContent;
                        const tag = document.createElement('span');
                        tag.className = 'selected-tag';
                        tag.innerHTML = `
                            ${label}
                            <span class="remove-tag" data-value="${checkbox.value}">×</span>
                        `;
                        selectedItems.appendChild(tag);

                        tag.querySelector('.remove-tag').addEventListener('click', function(e) {
                            e.stopPropagation();
                            checkbox.checked = false;
                            updateSelectedDisplay();
                            updateOtherContainer();
                        });
                    });
                }
            }

            function updateOtherContainer() {
                const otherCheckbox = container.querySelector('input[value="other"]');
                const isOtherChecked = otherCheckbox && otherCheckbox.checked;

                if (isOtherChecked) {
                    bodyPartOtherContainer.style.display = 'block';
                    bodyPartOtherInput.setAttribute('required', 'required');
                } else {
                    bodyPartOtherContainer.style.display = 'none';
                    bodyPartOtherInput.removeAttribute('required');
                    bodyPartOtherInput.value = '';
                }
            }

            updateOtherContainer();
        }

        function attachSearchHandler(wrapper) {
            const searchInput = wrapper.querySelector('.search-input');

            if (searchInput) {
                const newSearchInput = searchInput.cloneNode(true);
                searchInput.parentNode.replaceChild(newSearchInput, searchInput);

                newSearchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const options = wrapper.querySelectorAll('.select-option');

                    options.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            option.classList.remove('hidden');
                        } else {
                            option.classList.add('hidden');
                        }
                    });
                });
            }
        }

        function initializeSearchableSelects() {
            const searchableSelects = document.querySelectorAll('.searchable-select');

            searchableSelects.forEach(wrapper => {
                const selectId = wrapper.dataset.select;
                const hiddenSelect = wrapper.querySelector('.hidden-select');
                const display = wrapper.querySelector('.select-display');
                const dropdown = wrapper.querySelector('.select-dropdown');
                const searchInput = wrapper.querySelector('.search-input');
                const options = wrapper.querySelectorAll('.select-option');
                const valueSpan = wrapper.querySelector('.select-value');

                const currentValue = hiddenSelect.value;

                if (currentValue) {
                    const selectedOption = wrapper.querySelector(`.select-option[data-value="${currentValue}"]`);
                    if (selectedOption) {
                        const text = selectedOption.textContent.trim();
                        valueSpan.textContent = text;
                        valueSpan.classList.remove('placeholder');
                        selectedOption.classList.add('selected');
                    }
                } else {
                    const firstOption = wrapper.querySelector('.select-option[data-value=""]');
                    if (firstOption) {
                        valueSpan.textContent = firstOption.textContent.trim();
                        valueSpan.classList.add('placeholder');
                        firstOption.classList.add('selected');
                    }
                }

                attachSearchHandler(wrapper);

                display.addEventListener('click', function() {
                    const isOpen = dropdown.classList.contains('is-open');

                    document.querySelectorAll('.select-dropdown.is-open').forEach(dd => {
                        dd.classList.remove('is-open');
                        dd.parentElement.querySelector('.select-display').classList.remove('is-open');
                    });

                    if (!isOpen) {
                        dropdown.classList.add('is-open');
                        display.classList.add('is-open');
                        const currentSearchInput = wrapper.querySelector('.search-input');
                        if (currentSearchInput) {
                            currentSearchInput.focus();
                        }
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!wrapper.contains(e.target)) {
                        dropdown.classList.remove('is-open');
                        display.classList.remove('is-open');
                    }
                });

                display.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        display.click();
                    }
                });

                options.forEach(option => {
                    option.addEventListener('click', function() {
                        const value = this.dataset.value;
                        const text = this.textContent.trim();

                        valueSpan.textContent = text;
                        if (value) {
                            valueSpan.classList.remove('placeholder');
                        } else {
                            valueSpan.classList.add('placeholder');
                        }

                        hiddenSelect.value = value;

                        options.forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');

                        dropdown.classList.remove('is-open');
                        display.classList.remove('is-open');

                        if (selectId === 'injury_type_id') {
                            const injuryTypeSelect = document.getElementById('injury_type_id');
                            const injuryTypeOtherContainer = document.getElementById('injury_type_other_container');
                            const injuryTypeOtherInput = document.getElementById('injury_type_other');

                            const isOther = injuryTypeSelect.value === 'other';
                            if (isOther) {
                                injuryTypeOtherContainer.style.display = 'block';
                                injuryTypeOtherInput.setAttribute('required', 'required');
                            } else {
                                injuryTypeOtherContainer.style.display = 'none';
                                injuryTypeOtherInput.removeAttribute('required');
                                injuryTypeOtherInput.value = '';
                            }
                        }

                        const changeEvent = new Event('change', { bubbles: true });
                        hiddenSelect.dispatchEvent(changeEvent);

                        const currentSearchInput = wrapper.querySelector('.search-input');
                        if (currentSearchInput) {
                            currentSearchInput.value = '';
                            const allOptions = wrapper.querySelectorAll('.select-option');
                            allOptions.forEach(opt => opt.classList.remove('hidden'));
                        }
                    });
                });
            });
        }

        function initializeBranchFiltering() {
            const branchSelect = document.getElementById('branch_id');
            const siteSelect = document.getElementById('site_id');
            const affectedEmployeeSelect = document.getElementById('affected_employee_id');
            const reportedEmployeeSelect = document.getElementById('reported_employee_id');

            if (!branchSelect || !siteSelect || !affectedEmployeeSelect || !reportedEmployeeSelect) {
                return;
            }

            const siteWrapper = document.querySelector('.searchable-select[data-select="site_id"]');
            const affectedEmployeeWrapper = document.querySelector('.searchable-select[data-select="affected_employee_id"]');
            const reportedEmployeeWrapper = document.querySelector('.searchable-select[data-select="reported_employee_id"]');

            const originalSiteOptions = Array.from(siteWrapper.querySelectorAll('.select-option')).slice(1);
            const originalAffectedEmployeeOptions = Array.from(affectedEmployeeWrapper.querySelectorAll('.select-option')).slice(1);
            const originalReportedEmployeeOptions = Array.from(reportedEmployeeWrapper.querySelectorAll('.select-option')).slice(1);

            function filterSitesByBranch(branchId) {
                const siteOptionsContainer = siteWrapper.querySelector('.select-options');
                const siteDisplay = siteWrapper.querySelector('.select-value');
                const firstOption = siteOptionsContainer.querySelector('.select-option');
                
                siteOptionsContainer.innerHTML = '';
                siteOptionsContainer.appendChild(firstOption);

                if (!branchId) {
                    originalSiteOptions.forEach(option => {
                        siteOptionsContainer.appendChild(option.cloneNode(true));
                    });
                } else {
                    originalSiteOptions.forEach(option => {
                        if (option.dataset.branchId === branchId) {
                            siteOptionsContainer.appendChild(option.cloneNode(true));
                        }
                    });
                }

                reinitializeOptionHandlers(siteWrapper, 'site_id');

                if (branchSelect.dataset.initialized) {
                    siteSelect.value = '';
                    siteDisplay.textContent = 'Select site...';
                    siteDisplay.classList.add('placeholder');
                    siteWrapper.querySelectorAll('.select-option').forEach(opt => opt.classList.remove('selected'));
                    siteWrapper.querySelector('.select-option[data-value=""]').classList.add('selected');
                    updateEmployeeDisplay(''); // Updated - don't filter, just update display
                } else {
                    const currentSiteValue = siteSelect.value;
                    if (currentSiteValue) {
                        const currentSiteOption = siteWrapper.querySelector(`.select-option[data-value="${currentSiteValue}"]`);
                        if (currentSiteOption) {
                            siteDisplay.textContent = currentSiteOption.textContent.trim();
                            siteDisplay.classList.remove('placeholder');
                            currentSiteOption.classList.add('selected');
                        }
                    }
                }
            }

            // NEW FUNCTION: Updates employee display based on site without removing options
            function updateEmployeeDisplay(siteId) {
                function updateEmployeeDropdown(wrapper, selectId, originalOptions) {
                    const optionsContainer = wrapper.querySelector('.select-options');
                    const display = wrapper.querySelector('.select-value');
                    const hiddenSelect = document.getElementById(selectId);
                    const firstOption = optionsContainer.querySelector('.select-option');
                    const currentValue = hiddenSelect.value;

                    // Clear and rebuild - but keep ALL employees
                    optionsContainer.innerHTML = '';
                    optionsContainer.appendChild(firstOption);

                    // Sort employees: matching site first, then others
                    const matchingSite = [];
                    const otherSites = [];

                    originalOptions.forEach(option => {
                        if (!siteId || option.dataset.siteId === siteId) {
                            matchingSite.push(option.cloneNode(true));
                        } else {
                            const clonedOption = option.cloneNode(true);
                            clonedOption.classList.add('dimmed');
                            otherSites.push(clonedOption);
                        }
                    });

                    // Add matching site employees first
                    matchingSite.forEach(opt => optionsContainer.appendChild(opt));
                    
                    // Add other site employees (dimmed but still accessible)
                    otherSites.forEach(opt => optionsContainer.appendChild(opt));

                    reinitializeOptionHandlers(wrapper, selectId);

                    // Preserve current selection if it exists
                    if (currentValue) {
                        const currentOption = wrapper.querySelector(`.select-option[data-value="${currentValue}"]`);
                        if (currentOption) {
                            const employeeName = currentOption.textContent.trim().split('\n')[0].trim();
                            display.textContent = employeeName;
                            display.classList.remove('placeholder');
                            currentOption.classList.add('selected');
                        }
                    }
                }

                updateEmployeeDropdown(affectedEmployeeWrapper, 'affected_employee_id', originalAffectedEmployeeOptions);
                updateEmployeeDropdown(reportedEmployeeWrapper, 'reported_employee_id', originalReportedEmployeeOptions);
            }

            function reinitializeOptionHandlers(wrapper, selectId) {
                const hiddenSelect = document.getElementById(selectId);
                const valueSpan = wrapper.querySelector('.select-value');
                const dropdown = wrapper.querySelector('.select-dropdown');
                const display = wrapper.querySelector('.select-display');

                attachSearchHandler(wrapper);

                const newOptions = wrapper.querySelectorAll('.select-option');

                newOptions.forEach(option => {
                    const newOption = option.cloneNode(true);
                    option.parentNode.replaceChild(newOption, option);
                });

                const freshOptions = wrapper.querySelectorAll('.select-option');

                freshOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        const value = this.dataset.value;
                        const text = this.textContent.trim().split('\n')[0].trim(); // Get just the name, not the badge

                        valueSpan.textContent = text;
                        if (value) {
                            valueSpan.classList.remove('placeholder');
                        } else {
                            valueSpan.classList.add('placeholder');
                        }

                        hiddenSelect.value = value;

                        freshOptions.forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');

                        dropdown.classList.remove('is-open');
                        display.classList.remove('is-open');

                        const changeEvent = new Event('change', { bubbles: true });
                        hiddenSelect.dispatchEvent(changeEvent);

                        const currentSearchInput = wrapper.querySelector('.search-input');
                        if (currentSearchInput) {
                            currentSearchInput.value = '';
                            freshOptions.forEach(opt => opt.classList.remove('hidden'));
                        }
                    });
                });
            }

            function setBranchFromSite(siteId) {
                if (!siteId) return;

                const siteOption = originalSiteOptions.find(opt => opt.dataset.value === siteId);
                if (siteOption) {
                    const branchId = siteOption.dataset.branchId;
                    const branchWrapper = document.querySelector('.searchable-select[data-select="branch_id"]');

                    if (branchId && branchSelect.value !== branchId) {
                        branchSelect.value = branchId;
                        branchSelect.dataset.autoSet = 'true';

                        const branchOption = branchWrapper.querySelector(`.select-option[data-value="${branchId}"]`);
                        if (branchOption) {
                            const branchDisplay = branchWrapper.querySelector('.select-value');
                            branchDisplay.textContent = branchOption.textContent.trim();
                            branchDisplay.classList.remove('placeholder');

                            branchWrapper.querySelectorAll('.select-option').forEach(opt => opt.classList.remove('selected'));
                            branchOption.classList.add('selected');
                        }

                        filterSitesByBranch(branchId);

                        const siteWrapper = document.querySelector('.searchable-select[data-select="site_id"]');
                        const restoredSiteOption = siteWrapper.querySelector(`.select-option[data-value="${siteId}"]`);
                        if (restoredSiteOption) {
                            const siteDisplay = siteWrapper.querySelector('.select-value');
                            siteDisplay.textContent = restoredSiteOption.textContent.trim();
                            siteDisplay.classList.remove('placeholder');

                            siteWrapper.querySelectorAll('.select-option').forEach(opt => opt.classList.remove('selected'));
                            restoredSiteOption.classList.add('selected');
                            siteSelect.value = siteId;
                        }
                    }
                }
            }

            branchSelect.addEventListener('change', function() {
                if (this.dataset.autoSet) {
                    this.dataset.autoSet = '';
                    return;
                }
                filterSitesByBranch(this.value);
            });

            siteSelect.addEventListener('change', function() {
                setBranchFromSite(this.value);
                updateEmployeeDisplay(this.value);
            });

            // Initialize on page load
            const initialBranchId = branchSelect.value;
            const initialSiteId = siteSelect.value;

            if (initialBranchId) {
                filterSitesByBranch(initialBranchId);
            }

            if (initialSiteId) {
                updateEmployeeDisplay(initialSiteId);
            } else if (initialBranchId) {
                // If only branch is selected, show all employees from that branch
                updateEmployeeDisplay('');
            }

            branchSelect.dataset.initialized = 'true';
            siteSelect.dataset.initialized = 'true';
        }
    </script>
</x-app-layout>